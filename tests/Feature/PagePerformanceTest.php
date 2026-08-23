<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class PagePerformanceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return array<string, array{0: string, 1: int}>
     */
    public static function publicPageBudgets(): array
    {
        return [
            'home' => ['/', 45],
            'contact' => ['/contact', 20],
            'programs' => ['/programs', 25],
            'blogs' => ['/blogs', 25],
            'news' => ['/news', 25],
            'our-story' => ['/our-story', 40],
            'faculty-voice' => ['/faculty-voice', 20],
            'accreditations' => ['/accreditations', 20],
            'dual-mba' => ['/dual-mba-online', 30],
            'csr' => ['/csr-community-impact', 25],
        ];
    }

    #[DataProvider('publicPageBudgets')]
    public function test_public_pages_stay_within_query_budget(string $uri, int $maxQueries): void
    {
        DB::flushQueryLog();
        DB::enableQueryLog();

        $response = $this->get($uri);

        $queries = count(DB::getQueryLog());
        DB::disableQueryLog();

        $response->assertSuccessful();
        $this->assertLessThanOrEqual(
            $maxQueries,
            $queries,
            "{$uri} ran {$queries} queries (budget {$maxQueries})"
        );
    }

    public function test_profiler_command_runs_for_public_routes(): void
    {
        $this->artisan('app:profile-pages', [
            '--public-only' => true,
            '--limit' => 5,
            '--json' => storage_path('app/performance/test-profile.json'),
        ])->assertSuccessful();

        $this->assertFileExists(storage_path('app/performance/test-profile.json'));
    }
}
