<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $driver = DB::getDriverName();

        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE partner_logos MODIFY COLUMN type ENUM('alumni', 'accreditation', 'recognition', 'award') NOT NULL");
        }
    }

    public function down(): void
    {
        $driver = DB::getDriverName();

        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE partner_logos MODIFY COLUMN type ENUM('alumni', 'accreditation') NOT NULL");
        }
    }
};
