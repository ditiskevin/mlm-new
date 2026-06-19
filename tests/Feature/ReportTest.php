<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\Report;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportTest extends TestCase
{
    use RefreshDatabase;

    private function makePost(): Post
    {
        return Post::create([
            'author_name' => 'Iemand',
            'avatar_color' => '#F7A8B5',
            'body' => 'Een bericht dat gemeld kan worden.',
        ]);
    }

    public function test_guest_cannot_report(): void
    {
        $post = $this->makePost();

        $this->post('/meldingen', [
            'type' => 'post',
            'id' => $post->id,
            'reason' => 'spam',
        ])->assertRedirect('/login');

        $this->assertSame(0, Report::count());
    }

    public function test_member_can_report_a_post(): void
    {
        $post = $this->makePost();
        $user = User::factory()->create();

        $this->actingAs($user)->post('/meldingen', [
            'type' => 'post',
            'id' => $post->id,
            'reason' => 'ongepast',
            'details' => 'Dit hoort hier niet.',
        ])->assertRedirect()->assertSessionHas('success');

        $this->assertDatabaseHas('reports', [
            'user_id' => $user->id,
            'reportable_type' => Post::class,
            'reportable_id' => $post->id,
            'reason' => 'ongepast',
            'status' => 'open',
        ]);
    }

    public function test_duplicate_open_report_is_not_created(): void
    {
        $post = $this->makePost();
        $user = User::factory()->create();

        $payload = ['type' => 'post', 'id' => $post->id, 'reason' => 'spam'];
        $this->actingAs($user)->post('/meldingen', $payload);
        $this->actingAs($user)->post('/meldingen', $payload);

        $this->assertSame(1, Report::count());
    }

    public function test_report_validates_type_and_reason(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post('/meldingen', [
            'type' => 'banaan',
            'id' => 1,
            'reason' => 'zomaar',
        ])->assertSessionHasErrors(['type', 'reason']);
    }

    public function test_admin_can_resolve_and_delete_reported_content(): void
    {
        $post = $this->makePost();
        $reporter = User::factory()->create();
        $admin = User::factory()->admin()->create();

        $report = new Report(['user_id' => $reporter->id, 'reason' => 'spam', 'status' => 'open']);
        $report->reportable()->associate($post);
        $report->save();

        $this->actingAs($admin)->get('/admin/meldingen')->assertOk();

        // Mark reviewed
        $this->actingAs($admin)->patch("/admin/meldingen/{$report->id}/status", ['status' => 'reviewed'])->assertRedirect();
        $this->assertSame('reviewed', $report->fresh()->status);

        // Delete the reported content
        $this->actingAs($admin)->delete("/admin/meldingen/{$report->id}/inhoud")->assertRedirect();
        $this->assertNull($post->fresh());
    }

    public function test_non_admin_cannot_view_reports(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user)->get('/admin/meldingen')->assertForbidden();
    }
}
