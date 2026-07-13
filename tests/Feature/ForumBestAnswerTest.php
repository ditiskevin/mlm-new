<?php

namespace Tests\Feature;

use App\Models\ForumCategory;
use App\Models\ForumReply;
use App\Models\ForumTopic;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ForumBestAnswerTest extends TestCase
{
    use RefreshDatabase;

    private function scenario(): array
    {
        $topicAuthor = User::factory()->create();
        $replier = User::factory()->create();

        $category = ForumCategory::create([
            'name' => 'Zwangerschap',
            'slug' => 'zwangerschap',
            'description' => 'Alles over je zwangerschap',
        ]);

        $topic = ForumTopic::create([
            'forum_category_id' => $category->id,
            'user_id' => $topicAuthor->id,
            'author_name' => $topicAuthor->name,
            'avatar_color' => '#F7A8B5',
            'title' => 'Hulp gezocht',
            'slug' => 'hulp-gezocht',
            'body' => 'Wie kan mij helpen?',
            'last_activity_at' => now(),
        ]);

        $reply = ForumReply::create([
            'forum_topic_id' => $topic->id,
            'user_id' => $replier->id,
            'author_name' => $replier->name,
            'avatar_color' => '#CFE3F5',
            'body' => 'Ik kan je helpen!',
        ]);

        return compact('topicAuthor', 'replier', 'category', 'topic', 'reply');
    }

    public function test_topic_author_can_mark_best_and_reply_author_is_notified(): void
    {
        ['topicAuthor' => $topicAuthor, 'replier' => $replier, 'reply' => $reply] = $this->scenario();

        $this->actingAs($topicAuthor)
            ->patch("/forum-reacties/{$reply->id}/beste")
            ->assertRedirect();

        $this->assertDatabaseHas('forum_replies', ['id' => $reply->id, 'is_best' => true]);
        $this->assertDatabaseHas('mlm_notifications', [
            'user_id' => $replier->id,
            'type' => 'best_answer',
        ]);
    }

    public function test_non_author_cannot_mark_best(): void
    {
        ['replier' => $replier, 'reply' => $reply] = $this->scenario();

        // The replier (not the topic author) tries to mark best.
        $this->actingAs($replier)
            ->patch("/forum-reacties/{$reply->id}/beste")
            ->assertForbidden();

        $this->assertDatabaseHas('forum_replies', ['id' => $reply->id, 'is_best' => false]);
    }

    public function test_marking_a_second_reply_best_unsets_the_first(): void
    {
        ['topicAuthor' => $topicAuthor, 'replier' => $replier, 'topic' => $topic, 'reply' => $first] = $this->scenario();

        $second = ForumReply::create([
            'forum_topic_id' => $topic->id,
            'user_id' => $replier->id,
            'author_name' => $replier->name,
            'avatar_color' => '#CFE3F5',
            'body' => 'Nog een antwoord',
        ]);

        $this->actingAs($topicAuthor)->patch("/forum-reacties/{$first->id}/beste");
        $this->assertDatabaseHas('forum_replies', ['id' => $first->id, 'is_best' => true]);

        $this->actingAs($topicAuthor)->patch("/forum-reacties/{$second->id}/beste");

        $this->assertDatabaseHas('forum_replies', ['id' => $first->id, 'is_best' => false]);
        $this->assertDatabaseHas('forum_replies', ['id' => $second->id, 'is_best' => true]);
    }

    public function test_helpful_toggle_adds_and_removes_vote_and_notifies_once(): void
    {
        ['topicAuthor' => $topicAuthor, 'replier' => $replier, 'reply' => $reply] = $this->scenario();

        // Add a helpful vote.
        $this->actingAs($topicAuthor)
            ->post("/forum-reacties/{$reply->id}/behulpzaam")
            ->assertRedirect();

        $this->assertDatabaseHas('reply_helpful_votes', [
            'forum_reply_id' => $reply->id,
            'user_id' => $topicAuthor->id,
        ]);
        $this->assertDatabaseHas('mlm_notifications', [
            'user_id' => $replier->id,
            'type' => 'helpful',
        ]);
        $this->assertSame(1, \App\Models\Notification::where('type', 'helpful')->count());

        // Toggle off removes the vote.
        $this->actingAs($topicAuthor)
            ->post("/forum-reacties/{$reply->id}/behulpzaam")
            ->assertRedirect();

        $this->assertDatabaseMissing('reply_helpful_votes', [
            'forum_reply_id' => $reply->id,
            'user_id' => $topicAuthor->id,
        ]);
        // No duplicate notification created for the removal.
        $this->assertSame(1, \App\Models\Notification::where('type', 'helpful')->count());
    }

    public function test_guest_cannot_vote_helpful(): void
    {
        ['reply' => $reply] = $this->scenario();

        $this->post("/forum-reacties/{$reply->id}/behulpzaam")
            ->assertRedirect('/login');

        $this->assertDatabaseMissing('reply_helpful_votes', ['forum_reply_id' => $reply->id]);
    }
}
