<?php

namespace Tests\Feature;

use App\Mail\ContactMessageReceived;
use App\Models\ContactMessage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ContactTest extends TestCase
{
    use RefreshDatabase;

    public function test_contact_page_is_displayed(): void
    {
        $this->get('/contact')->assertOk();
    }

    public function test_guest_can_send_a_contact_message(): void
    {
        Mail::fake();

        $response = $this->post('/contact', [
            'name' => 'Sanne',
            'email' => 'sanne@example.com',
            'subject' => 'Een vraag',
            'message' => 'Ik heb een vraag over de zwangerschapskalender.',
        ]);

        $response->assertRedirect()->assertSessionHas('success');

        $this->assertDatabaseHas('contact_messages', [
            'email' => 'sanne@example.com',
            'subject' => 'Een vraag',
            'handled_at' => null,
        ]);

        Mail::assertSent(ContactMessageReceived::class);
    }

    public function test_logged_in_member_message_is_linked_to_account(): void
    {
        Mail::fake();
        $user = User::factory()->create();

        $this->actingAs($user)->post('/contact', [
            'name' => $user->name,
            'email' => $user->email,
            'subject' => 'Hallo',
            'message' => 'Dit is een bericht van een ingelogd lid.',
        ])->assertSessionHas('success');

        $this->assertDatabaseHas('contact_messages', [
            'user_id' => $user->id,
            'email' => $user->email,
        ]);
    }

    public function test_contact_message_requires_valid_fields(): void
    {
        $this->post('/contact', [
            'name' => '',
            'email' => 'not-an-email',
            'subject' => '',
            'message' => 'short',
        ])->assertSessionHasErrors(['name', 'email', 'subject', 'message']);

        $this->assertSame(0, ContactMessage::count());
    }

    public function test_honeypot_blocks_spam(): void
    {
        $this->post('/contact', [
            'name' => 'Bot',
            'email' => 'bot@example.com',
            'subject' => 'Spam',
            'message' => 'This is spam content from a bot.',
            'website' => 'http://spam.example',
        ])->assertSessionHasErrors('website');

        $this->assertSame(0, ContactMessage::count());
    }

    public function test_admin_can_view_and_handle_contact_messages(): void
    {
        $admin = User::factory()->admin()->create();
        $message = ContactMessage::create([
            'name' => 'Sanne',
            'email' => 'sanne@example.com',
            'subject' => 'Een vraag',
            'message' => 'Hallo daar, dit is een testbericht.',
        ]);

        $this->actingAs($admin)->get('/admin/contact')->assertOk();

        $this->actingAs($admin)->patch("/admin/contact/{$message->id}/handled")->assertRedirect();
        $this->assertNotNull($message->fresh()->handled_at);

        $this->actingAs($admin)->delete("/admin/contact/{$message->id}")->assertRedirect();
        $this->assertNull($message->fresh());
    }

    public function test_non_admin_cannot_access_contact_admin(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user)->get('/admin/contact')->assertForbidden();
    }
}
