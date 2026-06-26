<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuctionApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register_and_receive_token(): void
    {
        $response = $this->postJson('/api/register', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'message',
                'token',
                'user' => ['id', 'name', 'email'],
            ]);

        $this->assertDatabaseHas('users', ['email' => 'john@example.com']);
    }

    public function test_user_can_login_and_fetch_auctions(): void
    {
        $user = User::factory()->create([
            'email' => 'login@example.com',
            'password' => bcrypt('password123'),
        ]);

        $loginResponse = $this->postJson('/api/login', [
            'email' => 'login@example.com',
            'password' => 'password123',
        ]);

        $loginResponse->assertStatus(200)
            ->assertJsonStructure(['message', 'token', 'user']);

        $auctionResponse = $this->getJson('/api/auctions');

        $auctionResponse->assertStatus(200)
            ->assertJsonStructure(['message', 'data']);
    }

    public function test_authenticated_user_can_create_auction(): void
    {
        $user = User::factory()->create();

        $response = $this->withHeader('Authorization', 'Bearer ' . $user->api_token)
            ->postJson('/api/auctions', [
                'title' => 'Kamera Canon EOS R',
                'description' => 'Kamera bekas dalam kondisi baik',
                'price' => 15000000,
                'category' => 'Elektronik',
                'image_url' => 'https://example.com/image.jpg',
            ]);

        $response->assertStatus(201)
            ->assertJsonStructure(['message', 'data']);

        $this->assertDatabaseHas('auctions', ['title' => 'Kamera Canon EOS R']);
    }

    public function test_authenticated_user_can_list_their_auctions(): void
    {
        $user = User::factory()->create();

        $this->withHeader('Authorization', 'Bearer ' . $user->api_token)
            ->postJson('/api/auctions', [
                'title' => 'Jam Tangan Vintage',
                'description' => 'Jam tangan klasik',
                'price' => 2500000,
                'category' => 'Fashion',
                'image_url' => 'https://example.com/jam.jpg',
            ]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $user->api_token)
            ->getJson('/api/my-auctions');

        $response->assertStatus(200)
            ->assertJsonStructure(['message', 'data'])
            ->assertJsonFragment(['title' => 'Jam Tangan Vintage']);
    }
}
