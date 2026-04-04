<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Order;
use App\Models\Address;

class PurchaseTest extends TestCase
{
    use RefreshDatabase;

    // 商品購入
    public function test_user_can_purchase_item_with_convenience_store()
    {
        $user = User::factory()->create();

        $item = Item::factory()->create();

        $address = Address::factory()->create([
            'user_id' => $user->id,
            'type' => Address::TYPE_PROFILE,
        ]);

        $response = $this->actingAs($user)->post('/purchase/' . $item->id, [
            'payment_method' => 1,
        ]);

        $response->assertRedirect('/');

        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'payment_method' => 1,
        ]);
    }

    public function test_purchased_item_is_marked_as_sold()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        Order::factory()->create([
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $response = $this->get('/');

        $response->assertSee('Sold');
    }

    public function test_purchased_item_is_shown_in_profile_buy_list()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        Order::factory()->create([
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $response = $this->actingAs($user)
            ->get('/mypage?page=buy');

        $response->assertSee($item->name);
    }
    // 住所変更
    public function test_updated_shipping_address_is_reflected_in_purchase_page()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $this->actingAs($user)->post('/purchase/address/' . $item->id, [
            'postal_code' => '123-4567',
            'address_line' => 'Tokyo',
            'building' => 'Test Building',
        ]);

        $response = $this->actingAs($user)
            ->get('/purchase/' . $item->id);

        $response->assertSee('123-4567');
        $response->assertSee('Tokyo');
        $response->assertSee('Test Building');
    }

    public function test_order_has_shipping_address()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $address = Address::factory()->create([
            'user_id' => $user->id,
            'type' => Address::TYPE_PROFILE,
        ]);

        $this->actingAs($user)->post('/purchase/' . $item->id, [
            'payment_method' => 1,
        ]);

        $this->assertDatabaseHas('orders', [
            'item_id' => $item->id,
            'address_id' => $address->id,
        ]);
    }

    // 決済方法の反映
    public function test_payment_method_is_sent_correctly()
    {
        $user = User::factory()->create();

        $item = Item::factory()->create();

        $address = Address::factory()->create([
            'user_id' => $user->id,
            'type' => Address::TYPE_PROFILE,
        ]);

        $response = $this->actingAs($user)->post('/purchase/' . $item->id, [
            'payment_method' => 1, // コンビニ払い
        ]);

        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'payment_method' => 1,
        ]);
    }
}
