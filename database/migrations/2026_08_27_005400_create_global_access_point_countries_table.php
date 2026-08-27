<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('global_access_point_countries', function (Blueprint $table) {
            $table->id();
            $table->string('iso_numeric', 3)->unique();
            $table->char('iso2', 2);
            $table->string('name');
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        $countries = [
            ['498', 'MD', 'Moldova'],
            ['348', 'HU', 'Hungary'],
            ['458', 'MY', 'Malaysia'],
            ['156', 'CN', 'China'],
            ['462', 'MV', 'Maldives'],
            ['344', 'HK', 'Hong Kong'],
            ['104', 'MM', 'Myanmar'],
            ['036', 'AU', 'Australia'],
            ['288', 'GH', 'Ghana'],
            ['566', 'NG', 'Nigeria'],
            ['818', 'EG', 'Egypt'],
            ['760', 'SY', 'Syria'],
            ['887', 'YE', 'Yemen'],
            ['642', 'RO', 'Romania'],
            ['178', 'CG', 'Congo'],
            ['682', 'SA', 'Saudi Arabia'],
            ['784', 'AE', 'UAE'],
            ['356', 'IN', 'India'],
            ['144', 'LK', 'Sri Lanka'],
            ['826', 'GB', 'UK'],
            ['840', 'US', 'USA'],
            ['756', 'CH', 'Switzerland'],
            ['152', 'CL', 'Chile'],
            ['604', 'PE', 'Peru'],
            ['800', 'UG', 'Uganda'],
            ['716', 'ZW', 'Zimbabwe'],
            ['704', 'VN', 'Vietnam'],
        ];

        $now = now();
        $rows = [];
        foreach ($countries as $index => [$isoNumeric, $iso2, $name]) {
            $rows[] = [
                'iso_numeric' => $isoNumeric,
                'iso2' => $iso2,
                'name' => $name,
                'sort_order' => $index + 1,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        DB::table('global_access_point_countries')->insert($rows);
    }

    public function down(): void
    {
        Schema::dropIfExists('global_access_point_countries');
    }
};
