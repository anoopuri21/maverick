<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ManageZohoSettingsTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_zoho_settings_page_loads_with_additional_recipient_repeater(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $this->actingAs($admin)
            ->get('/admin/manage-zoho-settings')
            ->assertOk()
            ->assertSee('Additional recipients (BCC)');
    }
}
