<?php

namespace Tests\Feature;

use App\Filament\Resources\ProgramResource\Pages\CreateProgram;
use App\Models\ProgramCategory;
use App\Models\UniversityPartner;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ProgramFaqRepeaterCreateTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_program_with_default_empty_faq_item(): void
    {
        $user = User::factory()->create(['is_admin' => true]);
        $category = ProgramCategory::query()->create([
            'name' => 'Undergraduate',
            'slug' => 'undergraduate',
        ]);
        $partner = UniversityPartner::query()->create([
            'name' => 'Example University',
            'country' => 'United Kingdom',
            'is_active' => true,
        ]);

        Livewire::actingAs($user)
            ->test(CreateProgram::class)
            ->fillForm([
                'program_category_id' => $category->id,
                'title' => 'BBA Business Administration',
                'slug' => 'bba-business-administration-debug',
                'university_partner_id' => $partner->id,
                'level' => 'Undergraduate',
            ])
            ->call('create')
            ->assertHasNoErrors()
            ->assertNotified();

        $program = \App\Models\Program::query()->where('slug', 'bba-business-administration-debug')->first();
        $this->assertNotNull($program);
        $this->assertSame(0, $program->faqs()->count());
    }
}
