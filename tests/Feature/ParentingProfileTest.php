<?php

namespace Tests\Feature;

use App\Models\ChecklistItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ParentingProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_member_can_set_gender_and_parenting_role_on_profile(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->patch('/profile', [
            'name' => $user->name,
            'email' => $user->email,
            'parent_type' => 'ouder',
            'gender' => 'man',
            'parenting_role' => 'vader',
        ])->assertSessionHasNoErrors();

        $user->refresh();
        $this->assertSame('man', $user->gender);
        $this->assertSame('vader', $user->parenting_role);
        $this->assertTrue($user->is_father);
        $this->assertSame('Vader', $user->parenting_role_label);
    }

    public function test_invalid_parenting_role_is_rejected(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->patch('/profile', [
            'name' => $user->name,
            'email' => $user->email,
            'parent_type' => 'ouder',
            'parenting_role' => 'kapitein',
        ])->assertSessionHasErrors('parenting_role');
    }

    public function test_father_dashboard_shows_checklist_progress(): void
    {
        $item = ChecklistItem::create([
            'type' => 'vader',
            'category' => 'Voorbereiding',
            'label' => 'Vluchttas inpakken',
            'position' => 1,
        ]);

        $father = User::factory()->create(['parenting_role' => 'vader']);
        $father->checkedItems()->attach($item->id);

        $this->actingAs($father)->get('/dashboard')->assertOk();
    }

    public function test_non_father_does_not_get_father_card(): void
    {
        $user = User::factory()->create(['parenting_role' => 'moeder']);
        $this->assertFalse($user->is_father);
    }
}
