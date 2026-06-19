<?php

namespace Tests\Feature;

use App\Models\Babysitter;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BabysitterTypeTest extends TestCase
{
    use RefreshDatabase;

    public function test_member_can_post_an_offer(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post('/oppas', [
            'type' => 'aanbod',
            'name' => 'Lisa',
            'age' => 19,
            'location' => 'Utrecht',
            'hourly_rate' => 9,
            'experience_years' => 3,
            'has_vog' => true,
            'availability' => 'Avonden & weekenden',
            'bio' => 'Ik pas graag op en heb ervaring met baby\'s en peuters.',
        ])->assertRedirect();

        $sitter = Babysitter::first();
        $this->assertSame('aanbod', $sitter->type);
        $this->assertTrue($sitter->has_vog);
        $this->assertSame(3, $sitter->experience_years);
    }

    public function test_member_can_post_a_seeking_request(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post('/oppas', [
            'type' => 'gezocht',
            'name' => 'Familie Jansen',
            'location' => 'Amersfoort',
            'hourly_rate' => 12,
            'availability' => 'Doordeweeks na schooltijd',
            'bio' => 'Wij zoeken een lieve oppas voor onze twee kinderen van 4 en 6.',
            // Sitter-only fields intentionally omitted.
        ])->assertRedirect();

        $sitter = Babysitter::first();
        $this->assertSame('gezocht', $sitter->type);
        // Sitter-only fields are forced to neutral values for a request.
        $this->assertNull($sitter->age);
        $this->assertSame(0, $sitter->experience_years);
        $this->assertFalse($sitter->has_vog);
    }

    public function test_type_is_required_and_validated(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post('/oppas', [
            'type' => 'onzin',
            'name' => 'X',
            'location' => 'Y',
            'availability' => 'soms',
            'bio' => 'Een geldige beschrijving van voldoende lengte hier.',
        ])->assertSessionHasErrors('type');
    }

    public function test_index_can_filter_by_type(): void
    {
        $u = User::factory()->create();
        Babysitter::create(['user_id' => $u->id, 'type' => 'aanbod', 'name' => 'Aanbieder', 'location' => 'Utrecht', 'availability' => 'altijd', 'bio' => 'bio tekst hier']);
        Babysitter::create(['user_id' => $u->id, 'type' => 'gezocht', 'name' => 'Zoeker', 'location' => 'Utrecht', 'availability' => 'soms', 'bio' => 'bio tekst hier']);

        $this->get('/oppas?type=gezocht')->assertOk()->assertInertia(
            fn ($page) => $page->where('counts.aanbod', 1)->where('counts.gezocht', 1)
                ->has('sitters', 1)
                ->where('sitters.0.type', 'gezocht')
        );
    }
}
