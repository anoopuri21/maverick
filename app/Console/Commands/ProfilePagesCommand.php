<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

class ProfilePagesCommand extends Command
{
    protected $signature = 'app:profile-pages
                            {--public-only : Skip Filament admin routes}
                            {--admin-only : Only Filament admin routes}
                            {--json= : Write results JSON to this path}
                            {--limit=0 : Limit number of routes (0 = all)}';

    protected $description = 'Measure SQL query count and wall time for public and Filament admin pages';

    public function handle(): int
    {
        $results = [];

        if (! $this->option('admin-only')) {
            $results = array_merge($results, $this->profilePublicRoutes());
        }

        if (! $this->option('public-only')) {
            $results = array_merge($results, $this->profileAdminRoutes());
        }

        $this->renderTable($results);

        if ($path = $this->option('json')) {
            file_put_contents($path, json_encode([
                'generated_at' => now()->toIso8601String(),
                'results' => $results,
            ], JSON_PRETTY_PRINT));
            $this->info("Wrote {$path}");
        }

        return self::SUCCESS;
    }

    /**
     * @return list<array{scope:string,method:string,uri:string,name:?string,status:int,queries:int,ms:float}>
     */
    protected function profilePublicRoutes(): array
    {
        $routes = collect(Route::getRoutes())
            ->filter(function ($route) {
                if (! in_array('GET', $route->methods(), true)) {
                    return false;
                }

                $uri = $route->uri();

                if (str_starts_with($uri, 'admin') || str_starts_with($uri, 'filament') || str_starts_with($uri, '_')) {
                    return false;
                }

                if (str_starts_with($uri, 'livewire') || $uri === 'up' || $uri === 'about-us') {
                    return false;
                }

                // Skip parameterized catch-alls that need real slugs — profile with known fixtures below.
                if (str_contains($uri, '{')) {
                    return false;
                }

                return true;
            })
            ->values();

        $limit = (int) $this->option('limit');
        if ($limit > 0) {
            $routes = $routes->take($limit);
        }

        $results = [];

        foreach ($routes as $route) {
            $uri = '/'.ltrim($route->uri(), '/');
            if ($uri === '//') {
                $uri = '/';
            }

            $results[] = $this->hit('public', 'GET', $uri, $route->getName());
        }

        // Parameterized public pages with known fixture paths (404s still measure stack cost).
        foreach ([
            ['/programs/sample-program', 'programs.show'],
            ['/faculty-voice/sample-voice', 'faculty-voice.show'],
            ['/sample-insight', 'insights.show'],
        ] as [$uri, $name]) {
            $results[] = $this->hit('public', 'GET', $uri, $name);
        }

        return $results;
    }

    /**
     * @return list<array{scope:string,method:string,uri:string,name:?string,status:int,queries:int,ms:float}>
     */
    protected function profileAdminRoutes(): array
    {
        $user = User::query()->first();

        if (! $user) {
            $user = User::factory()->create([
                'email' => 'profiler@example.com',
                'name' => 'Profiler',
            ]);
            $this->warn('Created temporary profiler user (no users existed).');
        }

        Auth::login($user);

        $routes = collect(Route::getRoutes())
            ->filter(function ($route) {
                if (! in_array('GET', $route->methods(), true)) {
                    return false;
                }

                $uri = $route->uri();
                $name = (string) $route->getName();

                if (! str_starts_with($uri, 'admin')) {
                    return false;
                }

                if (str_contains($uri, '{')) {
                    return false;
                }

                if (str_contains($name, 'logout') || str_contains($name, 'login')) {
                    return false;
                }

                return true;
            })
            ->values();

        $limit = (int) $this->option('limit');
        if ($limit > 0) {
            $routes = $routes->take($limit);
        }

        $results = [];

        foreach ($routes as $route) {
            $uri = '/'.ltrim($route->uri(), '/');
            $results[] = $this->hit('admin', 'GET', $uri, $route->getName());
        }

        Auth::logout();

        return $results;
    }

    /**
     * @return array{scope:string,method:string,uri:string,name:?string,status:int,queries:int,ms:float}
     */
    protected function hit(string $scope, string $method, string $uri, ?string $name): array
    {
        DB::flushQueryLog();
        DB::enableQueryLog();

        $started = hrtime(true);

        try {
            $symfony = SymfonyRequest::create($uri, $method);
            $request = Request::createFromBase($symfony);
            $response = app()->handle($request);
            $status = $response->getStatusCode();
            // Terminate so scoped bindings reset between pages.
            if (method_exists(app(), 'terminate')) {
                app()->terminate($request, $response);
            }
        } catch (\Throwable $e) {
            $status = 500;
            $this->warn("{$uri}: {$e->getMessage()}");
        }

        $ms = (hrtime(true) - $started) / 1e6;
        $queries = count(DB::getQueryLog());
        DB::disableQueryLog();

        // Fresh app container state for next request (scoped settings etc.).
        if (method_exists($this->laravel, 'forgetScopedInstances')) {
            $this->laravel->forgetScopedInstances();
        }

        return [
            'scope' => $scope,
            'method' => $method,
            'uri' => $uri,
            'name' => $name,
            'status' => $status,
            'queries' => $queries,
            'ms' => round($ms, 1),
        ];
    }

    /**
     * @param  list<array{scope:string,method:string,uri:string,name:?string,status:int,queries:int,ms:float}>  $results
     */
    protected function renderTable(array $results): void
    {
        $this->table(
            ['Scope', 'URI', 'Status', 'Queries', 'ms', 'Name'],
            collect($results)->map(fn ($r) => [
                $r['scope'],
                $r['uri'],
                $r['status'],
                $r['queries'],
                $r['ms'],
                $r['name'] ?? '',
            ])->all()
        );

        $public = collect($results)->where('scope', 'public');
        $admin = collect($results)->where('scope', 'admin');

        $this->newLine();
        $this->info(sprintf(
            'Public: %d routes | avg %s queries | avg %s ms | max %s queries (%s)',
            $public->count(),
            $public->avg('queries') !== null ? round($public->avg('queries'), 1) : 'n/a',
            $public->avg('ms') !== null ? round($public->avg('ms'), 1) : 'n/a',
            $public->max('queries') ?? 'n/a',
            $public->sortByDesc('queries')->first()['uri'] ?? 'n/a',
        ));

        if ($admin->isNotEmpty()) {
            $this->info(sprintf(
                'Admin:  %d routes | avg %s queries | avg %s ms | max %s queries (%s)',
                $admin->count(),
                round($admin->avg('queries'), 1),
                round($admin->avg('ms'), 1),
                $admin->max('queries'),
                $admin->sortByDesc('queries')->first()['uri'] ?? 'n/a',
            ));
        }
    }
}
