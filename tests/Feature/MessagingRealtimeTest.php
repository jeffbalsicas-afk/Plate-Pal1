<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Message;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MessagingRealtimeTest extends TestCase
{
    use RefreshDatabase;

    public function test_client_can_send_message_as_json_for_live_chat(): void
    {
        $client = User::factory()->create([
            'role' => 'client',
            'name' => 'Client User',
        ]);

        $caterer = User::factory()->create([
            'role' => 'caterer',
            'business_name' => 'Tagum Feast Co.',
            'approval_status' => 'approved',
            'is_active' => true,
        ]);

        $response = $this->actingAs($client)
            ->postJson(route('messages.store', $caterer), [
                'body' => 'Hello, are you available next Friday?',
            ]);

        $response
            ->assertCreated()
            ->assertJsonPath('message.sender', 'client')
            ->assertJsonPath('message.body', 'Hello, are you available next Friday?');

        $this->assertDatabaseHas('messages', [
            'user_id' => $client->id,
            'caterer_id' => $caterer->id,
            'sender' => 'client',
            'body' => 'Hello, are you available next Friday?',
        ]);
    }

    public function test_client_can_send_attachment_without_message_text(): void
    {
        Storage::fake('public');

        $client = User::factory()->create([
            'role' => 'client',
            'name' => 'Client User',
        ]);

        $caterer = User::factory()->create([
            'role' => 'caterer',
            'business_name' => 'Tagum Feast Co.',
            'approval_status' => 'approved',
            'is_active' => true,
        ]);

        $file = UploadedFile::fake()->create('floor-plan.png', 128, 'image/png');

        $response = $this->actingAs($client)
            ->post(route('messages.store', $caterer), [
                'attachment' => $file,
            ], [
                'Accept' => 'application/json',
            ]);

        $response
            ->assertCreated()
            ->assertJsonPath('message.sender', 'client')
            ->assertJsonPath('message.body', '')
            ->assertJsonPath('message.attachment.name', 'floor-plan.png')
            ->assertJsonPath('message.attachment.is_image', true);

        $message = Message::firstOrFail();

        Storage::disk('public')->assertExists($message->attachment_path);

        $this->assertDatabaseHas('messages', [
            'user_id' => $client->id,
            'caterer_id' => $caterer->id,
            'sender' => 'client',
            'body' => '',
            'attachment_original_name' => 'floor-plan.png',
            'attachment_mime' => 'image/png',
        ]);
    }

    public function test_only_conversation_participants_can_view_message_attachments(): void
    {
        Storage::fake('public');

        $client = User::factory()->create(['role' => 'client']);
        $otherClient = User::factory()->create(['role' => 'client']);
        $caterer = User::factory()->create([
            'role' => 'caterer',
            'approval_status' => 'approved',
            'is_active' => true,
        ]);

        Storage::disk('public')->put('message-attachments/test.pdf', 'PDF contents');

        $message = Message::create([
            'user_id' => $client->id,
            'caterer_id' => $caterer->id,
            'sender' => 'client',
            'body' => '',
            'is_read' => false,
            'attachment_path' => 'message-attachments/test.pdf',
            'attachment_original_name' => 'test.pdf',
            'attachment_mime' => 'application/pdf',
            'attachment_size' => 12,
        ]);

        $this->actingAs($caterer)
            ->get(route('messages.attachment', $message))
            ->assertOk();

        $this->actingAs($otherClient)
            ->get(route('messages.attachment', $message))
            ->assertForbidden();
    }

    public function test_opening_conversation_marks_received_messages_as_read(): void
    {
        $client = User::factory()->create(['role' => 'client']);
        $caterer = User::factory()->create([
            'role' => 'caterer',
            'approval_status' => 'approved',
            'is_active' => true,
        ]);

        $message = Message::create([
            'user_id' => $client->id,
            'caterer_id' => $caterer->id,
            'sender' => 'caterer',
            'body' => 'Yes, that date is open.',
            'is_read' => false,
        ]);

        $this->actingAs($client)
            ->get(route('messages.show', $caterer))
            ->assertOk();

        $this->assertTrue((bool) $message->refresh()->is_read);
    }

    public function test_caterer_can_send_message_to_client(): void
    {
        $client = User::factory()->create([
            'role' => 'client',
            'name' => 'Client User',
        ]);

        $caterer = User::factory()->create([
            'role' => 'caterer',
            'business_name' => 'Tagum Feast Co.',
            'approval_status' => 'approved',
            'is_active' => true,
        ]);

        $response = $this->actingAs($caterer)
            ->postJson(route('messages.store', $client), [
                'body' => 'Yes, we can customize that package.',
            ]);

        $response
            ->assertCreated()
            ->assertJsonPath('message.sender', 'caterer')
            ->assertJsonPath('message.body', 'Yes, we can customize that package.');

        $this->assertDatabaseHas('messages', [
            'user_id' => $client->id,
            'caterer_id' => $caterer->id,
            'sender' => 'caterer',
            'body' => 'Yes, we can customize that package.',
        ]);
    }

    public function test_messages_index_shows_latest_conversation_and_start_contacts(): void
    {
        $client = User::factory()->create([
            'role' => 'client',
            'name' => 'Client User',
        ]);

        $caterer = User::factory()->create([
            'role' => 'caterer',
            'business_name' => 'Tagum Feast Co.',
            'approval_status' => 'approved',
            'is_active' => true,
        ]);

        $newCaterer = User::factory()->create([
            'role' => 'caterer',
            'business_name' => 'New Feast Co.',
            'approval_status' => 'approved',
            'is_active' => true,
        ]);

        Message::create([
            'user_id' => $client->id,
            'caterer_id' => $caterer->id,
            'sender' => 'client',
            'body' => 'Old message',
            'is_read' => true,
            'created_at' => now()->subHour(),
            'updated_at' => now()->subHour(),
        ]);

        Message::create([
            'user_id' => $client->id,
            'caterer_id' => $caterer->id,
            'sender' => 'caterer',
            'body' => 'Latest reply',
            'is_read' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->actingAs($client)->get(route('messages.index'));

        $response->assertOk();
        $response->assertSee('Latest reply');
        $response->assertDontSee('Old message');
        $response->assertSee('New Feast Co.');
    }

    public function test_caterer_messages_index_suggests_clients_from_bookings(): void
    {
        $client = User::factory()->create([
            'role' => 'client',
            'name' => 'Booked Client',
        ]);

        $caterer = User::factory()->create([
            'role' => 'caterer',
            'business_name' => 'Tagum Feast Co.',
            'approval_status' => 'approved',
            'is_active' => true,
        ]);

        Booking::create([
            'user_id' => $client->id,
            'caterer_id' => $caterer->id,
            'event_title' => 'Birthday Party',
            'event_date' => now()->addWeek()->toDateString(),
            'guests' => 50,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($caterer)->get(route('messages.index'));

        $response->assertOk();
        $response->assertSee('Booked Client');
    }
}
