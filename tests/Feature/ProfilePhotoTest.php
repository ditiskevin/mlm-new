<?php

namespace Tests\Feature;

use App\Models\User;
use App\Support\AvatarProcessor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProfilePhotoTest extends TestCase
{
    use RefreshDatabase;

    public function test_avatar_processor_outputs_a_square_webp(): void
    {
        $file = UploadedFile::fake()->image('photo.jpg', 800, 500);

        $binary = AvatarProcessor::toWebp($file);

        $info = getimagesizefromstring($binary);
        $this->assertSame(AvatarProcessor::SIZE, $info[0]); // width
        $this->assertSame(AvatarProcessor::SIZE, $info[1]); // height
        $this->assertSame('image/webp', $info['mime']);
    }

    public function test_uploaded_avatar_is_stored_as_webp(): void
    {
        Storage::fake('r2');
        $user = User::factory()->create();

        $this->actingAs($user)->patch('/profile', [
            'name' => $user->name,
            'email' => $user->email,
            'parent_type' => 'ouder',
            'avatar' => UploadedFile::fake()->image('me.jpg', 600, 600),
        ])->assertSessionHasNoErrors();

        $user->refresh();
        $this->assertNotNull($user->avatar_path);
        $this->assertStringEndsWith('.webp', $user->avatar_path);
        Storage::disk('r2')->assertExists($user->avatar_path);
    }

    public function test_avatar_can_be_removed(): void
    {
        Storage::fake('r2');
        $user = User::factory()->create();

        // First upload one.
        $this->actingAs($user)->patch('/profile', [
            'name' => $user->name,
            'email' => $user->email,
            'parent_type' => 'ouder',
            'avatar' => UploadedFile::fake()->image('me.png', 400, 400),
        ]);
        $path = $user->fresh()->avatar_path;
        $this->assertNotNull($path);

        // Then remove it.
        $this->actingAs($user)->patch('/profile', [
            'name' => $user->name,
            'email' => $user->email,
            'parent_type' => 'ouder',
            'remove_avatar' => true,
        ])->assertSessionHasNoErrors();

        $this->assertNull($user->fresh()->avatar_path);
        Storage::disk('r2')->assertMissing($path);
    }

    public function test_tiny_images_are_rejected(): void
    {
        Storage::fake('r2');
        $user = User::factory()->create();

        $this->actingAs($user)->patch('/profile', [
            'name' => $user->name,
            'email' => $user->email,
            'parent_type' => 'ouder',
            'avatar' => UploadedFile::fake()->image('tiny.jpg', 40, 40),
        ])->assertSessionHasErrors('avatar');
    }
}
