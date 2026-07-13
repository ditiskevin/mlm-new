<?php

namespace Tests\Feature;

use App\Models\Listing;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MarketplaceFavoriteTest extends TestCase
{
    use RefreshDatabase;

    private function makeListing(?User $owner = null): Listing
    {
        $owner ??= User::factory()->create();

        return Listing::create([
            'user_id' => $owner->id,
            'title' => 'Houten trein',
            'slug' => 'houten-trein',
            'category' => 'Speelgoed',
            'offer_type' => 'aanbod',
            'location' => 'Utrecht',
            'description' => 'Mooie houten trein in goede staat.',
            'author_name' => $owner->name,
            'avatar_color' => '#F7A8B5',
            'emoji' => '🧸',
        ]);
    }

    public function test_guest_cannot_favorite_a_listing(): void
    {
        $listing = $this->makeListing();

        $this->post('/marktplaats/'.$listing->slug.'/favoriet')
            ->assertRedirect('/login');

        $this->assertDatabaseCount('listing_favorites', 0);
    }

    public function test_member_can_favorite_and_unfavorite_a_listing(): void
    {
        $listing = $this->makeListing();
        $user = User::factory()->create();

        // Favorite
        $this->actingAs($user)->post('/marktplaats/'.$listing->slug.'/favoriet')->assertRedirect();
        $this->assertDatabaseHas('listing_favorites', [
            'user_id' => $user->id,
            'listing_id' => $listing->id,
        ]);

        // Unfavorite — toggling again removes it
        $this->actingAs($user)->post('/marktplaats/'.$listing->slug.'/favoriet')->assertRedirect();
        $this->assertDatabaseMissing('listing_favorites', [
            'user_id' => $user->id,
            'listing_id' => $listing->id,
        ]);
    }

    public function test_favorites_index_renders(): void
    {
        $listing = $this->makeListing();
        $user = User::factory()->create();

        $this->actingAs($user)->post('/marktplaats/'.$listing->slug.'/favoriet');

        $this->actingAs($user)->get('/marktplaats-favorieten')->assertOk();
    }

    public function test_owner_can_update_status_to_verkocht(): void
    {
        $owner = User::factory()->create();
        $listing = $this->makeListing($owner);

        $this->actingAs($owner)
            ->patch('/marktplaats/'.$listing->slug.'/status', ['status' => 'verkocht'])
            ->assertRedirect();

        $this->assertDatabaseHas('listings', [
            'id' => $listing->id,
            'status' => 'verkocht',
        ]);
    }

    public function test_non_owner_cannot_update_status(): void
    {
        $listing = $this->makeListing();
        $other = User::factory()->create();

        $this->actingAs($other)
            ->patch('/marktplaats/'.$listing->slug.'/status', ['status' => 'verkocht'])
            ->assertForbidden();

        $this->assertDatabaseHas('listings', [
            'id' => $listing->id,
            'status' => 'beschikbaar',
        ]);
    }

    public function test_invalid_status_is_rejected(): void
    {
        $owner = User::factory()->create();
        $listing = $this->makeListing($owner);

        $this->actingAs($owner)
            ->patch('/marktplaats/'.$listing->slug.'/status', ['status' => 'banaan'])
            ->assertSessionHasErrors('status');
    }
}
