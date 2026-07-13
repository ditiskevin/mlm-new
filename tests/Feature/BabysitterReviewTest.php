<?php

namespace Tests\Feature;

use App\Models\Babysitter;
use App\Models\BabysitterReview;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BabysitterReviewTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Create an "aanbod" babysitter profile owned by $owner.
     */
    private function makeSitter(User $owner): Babysitter
    {
        return Babysitter::create([
            'user_id' => $owner->id,
            'type' => 'aanbod',
            'name' => 'Lisa',
            'location' => 'Utrecht',
            'availability' => 'Avonden & weekenden',
            'bio' => 'Ik pas graag op en heb ervaring met baby\'s en peuters.',
        ]);
    }

    public function test_guest_cannot_review_and_is_redirected_to_login(): void
    {
        $sitter = $this->makeSitter(User::factory()->create());

        $this->post("/oppas/{$sitter->id}/beoordeling", ['rating' => 5, 'body' => 'Top!'])
            ->assertRedirect('/login');

        $this->assertSame(0, BabysitterReview::count());
    }

    public function test_member_can_review_a_sitter(): void
    {
        $sitter = $this->makeSitter(User::factory()->create());
        $member = User::factory()->create();

        $this->actingAs($member)
            ->post("/oppas/{$sitter->id}/beoordeling", ['rating' => 4, 'body' => 'Fijne oppas.'])
            ->assertRedirect();

        $this->assertDatabaseHas('babysitter_reviews', [
            'babysitter_id' => $sitter->id,
            'user_id' => $member->id,
            'author_name' => $member->name,
            'rating' => 4,
        ]);
    }

    public function test_reviewing_again_updates_the_existing_review(): void
    {
        $sitter = $this->makeSitter(User::factory()->create());
        $member = User::factory()->create();

        $this->actingAs($member)->post("/oppas/{$sitter->id}/beoordeling", ['rating' => 3]);
        $this->actingAs($member)->post("/oppas/{$sitter->id}/beoordeling", ['rating' => 5, 'body' => 'Toch beter!']);

        $this->assertSame(1, BabysitterReview::where('babysitter_id', $sitter->id)->where('user_id', $member->id)->count());
        $this->assertDatabaseHas('babysitter_reviews', [
            'babysitter_id' => $sitter->id,
            'user_id' => $member->id,
            'rating' => 5,
        ]);
    }

    public function test_member_cannot_review_their_own_profile(): void
    {
        $owner = User::factory()->create();
        $sitter = $this->makeSitter($owner);

        $this->actingAs($owner)
            ->post("/oppas/{$sitter->id}/beoordeling", ['rating' => 5])
            ->assertSessionHas('error');

        $this->assertSame(0, BabysitterReview::count());
    }

    public function test_owner_is_notified_when_someone_else_reviews(): void
    {
        $owner = User::factory()->create();
        $sitter = $this->makeSitter($owner);
        $member = User::factory()->create();

        $this->actingAs($member)
            ->post("/oppas/{$sitter->id}/beoordeling", ['rating' => 5, 'body' => 'Aanrader!']);

        $this->assertDatabaseHas('mlm_notifications', [
            'user_id' => $owner->id,
            'type' => 'review',
        ]);
    }

    public function test_rating_must_be_between_one_and_five(): void
    {
        $sitter = $this->makeSitter(User::factory()->create());
        $member = User::factory()->create();

        $this->actingAs($member)
            ->post("/oppas/{$sitter->id}/beoordeling", ['rating' => 0])
            ->assertSessionHasErrors('rating');

        $this->actingAs($member)
            ->post("/oppas/{$sitter->id}/beoordeling", ['rating' => 6])
            ->assertSessionHasErrors('rating');

        $this->assertSame(0, BabysitterReview::count());
    }

    public function test_reviewer_can_delete_their_own_review(): void
    {
        $sitter = $this->makeSitter(User::factory()->create());
        $member = User::factory()->create();
        $review = BabysitterReview::create([
            'babysitter_id' => $sitter->id,
            'user_id' => $member->id,
            'author_name' => $member->name,
            'rating' => 4,
            'body' => 'Prima.',
        ]);

        $this->actingAs($member)
            ->delete("/oppas-beoordelingen/{$review->id}")
            ->assertRedirect();

        $this->assertDatabaseMissing('babysitter_reviews', ['id' => $review->id]);
    }

    public function test_admin_can_delete_any_review(): void
    {
        $sitter = $this->makeSitter(User::factory()->create());
        $member = User::factory()->create();
        $admin = User::factory()->admin()->create();
        $review = BabysitterReview::create([
            'babysitter_id' => $sitter->id,
            'user_id' => $member->id,
            'author_name' => $member->name,
            'rating' => 2,
        ]);

        $this->actingAs($admin)
            ->delete("/oppas-beoordelingen/{$review->id}")
            ->assertRedirect();

        $this->assertDatabaseMissing('babysitter_reviews', ['id' => $review->id]);
    }

    public function test_others_cannot_delete_someone_elses_review(): void
    {
        $sitter = $this->makeSitter(User::factory()->create());
        $member = User::factory()->create();
        $intruder = User::factory()->create();
        $review = BabysitterReview::create([
            'babysitter_id' => $sitter->id,
            'user_id' => $member->id,
            'author_name' => $member->name,
            'rating' => 3,
        ]);

        $this->actingAs($intruder)
            ->delete("/oppas-beoordelingen/{$review->id}")
            ->assertForbidden();

        $this->assertDatabaseHas('babysitter_reviews', ['id' => $review->id]);
    }
}
