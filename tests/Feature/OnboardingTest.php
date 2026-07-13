<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OnboardingTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_from_welcome_to_login(): void
    {
        $this->get('/welkom')->assertRedirect('/login');
    }

    public function test_member_store_saves_answers_and_marks_onboarded(): void
    {
        $user = User::factory()->create(['onboarded_at' => null]);

        $this->actingAs($user)->post('/welkom', [
            'is_expecting' => true,
            'due_date' => '2026-12-01',
            'children_count' => 2,
            'parent_type' => 'ouder',
            'parenting_role' => 'moeder',
        ])->assertRedirect('/dashboard');

        $user->refresh();
        $this->assertTrue((bool) $user->is_expecting);
        $this->assertSame('2026-12-01', \Illuminate\Support\Carbon::parse($user->due_date)->toDateString());
        $this->assertSame(2, (int) $user->children_count);
        $this->assertSame('moeder', $user->parenting_role);
        $this->assertNotNull($user->onboarded_at);
    }

    public function test_not_expecting_member_does_not_store_due_date(): void
    {
        $user = User::factory()->create(['onboarded_at' => null]);

        $this->actingAs($user)->post('/welkom', [
            'is_expecting' => false,
            'due_date' => '2026-12-01',
            'children_count' => 1,
        ])->assertRedirect('/dashboard');

        $user->refresh();
        $this->assertFalse((bool) $user->is_expecting);
        $this->assertNull($user->due_date);
        $this->assertNotNull($user->onboarded_at);
    }

    public function test_already_onboarded_user_is_redirected_to_dashboard(): void
    {
        $user = User::factory()->create(['onboarded_at' => now()]);

        $this->actingAs($user)->get('/welkom')->assertRedirect('/dashboard');
    }
}
