<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Order;
use App\Models\Address;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;


class UserTest extends TestCase
{
    use RefreshDatabase;

    // マイページ
    public function test_profile_shows_user_info()
    {
        Storage::fake('public');

        $user = User::factory()->create([
            'name' => 'Test User',
            'image' => 'users/test.jpg',
        ]);

        $this->withoutExceptionHandling();

        $response = $this->actingAs($user)
            ->get('/mypage');

        $response->assertStatus(200);

        $response->assertSee('Test User');

        $response->assertSee(Storage::url('users/test.jpg'));
    }

    public function test_profile_shows_selling_items()
    {
        $user = User::factory()->create();

        Item::factory()->create([
            'user_id' => $user->id,
            'name' => 'My Item',
        ]);

        $otherUser = User::factory()->create();

        Item::factory()->create([
            'user_id' => $otherUser->id,
            'name' => 'Other Item',
        ]);

        $response = $this->actingAs($user)
            ->get('/mypage?page=sell');

        $response->assertSee('My Item');
        $response->assertDontSeeText('Other Item');
    }

    public function test_profile_shows_purchased_items()
    {
        $user = User::factory()->create();

        $item = Item::factory()->create([
            'name' => 'Purchased Item',
        ]);

        Order::factory()
            ->for($user)
            ->for($item)
            ->create();

        $otherUser = User::factory()->create();

        $otherItem = Item::factory()->create([
            'name' => 'Other Item',
        ]);

        Order::factory()
            ->for($otherUser)
            ->for($otherItem)
            ->create();

        $response = $this->actingAs($user)
            ->get('/mypage?page=buy');

        $response->assertSee('Purchased Item');
        $response->assertDontSeeText('Other Item');
    }

    // プロフィール編集
    public function test_updated_profile_is_reflected_in_edit_form()
    {
        Storage::fake('public');

        $user = User::factory()->create([
            'name' => 'Old Name',
            'image' => null,
        ]);

        Address::factory()->create([
            'user_id' => $user->id,
            'type' => Address::TYPE_PROFILE,
            'postal_code' => '111-1111',
            'address_line' => 'Old Address',
            'building' => 'Old Building',
        ]);

        $this->actingAs($user)->post('/profile/update', [
            'name' => 'New Name',
            'postal_code' => '123-4567',
            'address_line' => 'Tokyo',
            'building' => 'New Building',
        ]);

        $response = $this->actingAs($user)->get('/mypage/profile');

        $response->assertStatus(200);

        $response->assertSee('New Name');
        $response->assertSee('123-4567');
        $response->assertSee('Tokyo');
        $response->assertSee('New Building');
    }
    
    public function test_updated_profile_image_is_reflected()
    {
        Storage::fake('public');

        $user = User::factory()->create();

        $file = UploadedFile::fake()->create('test.jpg', 100);

        $this->actingAs($user)->post('/profile/update', [
            'name' => 'Test User',
            'postal_code' => '123-4567',
            'address_line' => 'Tokyo',
            'building' => 'Test',
            'image' => $file,
        ]);

        $response = $this->actingAs($user)->get('/mypage/profile');

        $response->assertSee('profile/');
    }

}
