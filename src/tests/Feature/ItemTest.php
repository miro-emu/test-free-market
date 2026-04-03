<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Order;
use App\Models\category;
use App\Models\Condition;
use App\Models\Address;
use App\Models\Comment;
use Illuminate\Support\Facades\Storage;
use Database\Seeders\ConditionsTableSeeder;
use Database\Seeders\CategoriesTableSeeder;

class ItemTest extends TestCase
{
    use RefreshDatabase;

    // 商品一覧
    public function test_can_get_items()
    {
        $items = Item::factory()->count(3)->create();

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertViewIs('items');
        $response->assertViewHas('items');
        
        $response->assertSee($items[0]->name);
    }

    public function test_sold_label_is_displayed_for_purchased_items()
    {
        $user = User::factory()->create();

        Address::factory()->create([
            'user_id' => $user->id,
        ]);

        $item = Item::factory()->create([
            'name' => 'Test Item',
        ]);

        Order::factory()->create([
            'item_id' => $item->id,
            'user_id' => $user->id,
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);

        $response->assertSee('Test Item');

        $response->assertSee('Sold');
    }

    public function test_user_cannot_see_own_items()
    {
        $user = User::factory()->create();

        $myItem = Item::factory()->create([
            'user_id' => $user->id,
            'name' => 'My Item'
        ]);

        $otherItem = Item::factory()->create([
            'name' => 'Other Item'
        ]);

        $response = $this->actingAs($user)->get('/');

        $response->assertSee('Other Item');
        $response->assertDontSee('My Item');
    }

    // マイリスト
    public function test_can_get_mylist_items()
    {
        $user = User::factory()->create();

        $likedItem = Item::factory()->create(['name' => 'Liked']);
        $notLikedItem = Item::factory()->create(['name' => 'NotLiked']);

        $user->likes()->attach($likedItem->id);

        $response = $this->actingAs($user)->get('/?tab=mylist');

        $response->assertSee('Liked');
        $response->assertDontSee('NotLiked');
    }

    public function test_sold_label_is_displayed_in_mylist()
    {
        $user = User::factory()->create();

        Address::factory()->create([
            'user_id' => $user->id,
        ]);

        $item = Item::factory()->create([
            'name' => 'Test Item',
        ]);

        $user->likes()->attach($item->id);

        Order::factory()->create([
            'item_id' => $item->id,
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)
            ->get('/?tab=mylist');

        $response->assertStatus(200);

        $response->assertSee('Test Item');

        $response->assertSee('Sold');
    }

    public function test_guest_cannot_see_any_items_in_mylist()
    {
        $items = Item::factory()->count(3)->create();

        $response = $this->get('/?tab=mylist');

        $response->assertStatus(200);

        $response->assertViewHas('items', function ($items) {
            return $items->isEmpty();
        });
    }

    // 商品検索
    public function test_can_search_items()
    {
        Item::factory()->create(['name' => 'Apple']);
        Item::factory()->create(['name' => 'Banana']);

        $response = $this->get('/?keyword=Apple');

        $response->assertSee('Apple');
        $response->assertDontSee('Banana');
    }

    public function test_search_is_kept_in_mylist()
    {
        $user = User::factory()->create();

        $appleItem = Item::factory()->create([
            'name' => 'Apple',
        ]);

        $bananaItem = Item::factory()->create([
            'name' => 'Banana',
        ]);

        $user->likes()->attach([$appleItem->id, $bananaItem->id]);

        $response = $this->actingAs($user)
            ->get('/?tab=mylist&keyword=Apple');

        $response->assertStatus(200);

        $response->assertSee('Apple');

        $response->assertDontSee('Banana');
    }

    // 商品詳細
    public function test_can_get_item_detail()
    {
        $item = Item::factory()->create([
            'name' => 'Test Item',
            'brand' => 'Test Brand',
            'price' => 1000,
            'description' => 'Test Description',
            'image' => 'items/test.jpg',
        ]);

        $response = $this->get('/item/' . $item->id);

        $response->assertStatus(200);
        $response->assertViewIs('detail');
        $response->assertViewHas('item');

        $response->assertSee('Test Item');
        $response->assertSee('Test Brand');
        $response->assertSee('1,000');
        $response->assertSee('Test Description');
        $response->assertSee(Storage::url('items/test.jpg'));
    }

    public function test_item_detail_shows_like_count()
    {
        $user = User::factory()->create();

        $item = Item::factory()->create();

        $user->likes()->attach($item->id);

        $response = $this->get('/item/' . $item->id);

        $response->assertSee('1'); 
    }

    public function test_item_detail_shows_comment_count()
    {
        $user = User::factory()->create();

        $item = Item::factory()->create();
        
        Comment::factory()->count(2)->create([
            'item_id' => $item->id,
            'user_id' => $user->id,
        ]);

        $response = $this->get('/item/' . $item->id);

        $response->assertStatus(200);

        $response->assertSee('2'); 
    }

    public function test_item_detail_shows_category_and_condition_from_seeder()
    {
        $this->seed([
            CategoriesTableSeeder::class,
            ConditionsTableSeeder::class,
        ]);

        $category = \App\Models\Category::first();

        $condition = \App\Models\Condition::first();

        $item = Item::factory()->create([
            'condition_id' => $condition->id,
        ]);

        $item->categories()->attach($category->id);

        $response = $this->get('/item/' . $item->id);

        $response->assertStatus(200);

        $response->assertSee($category->name);
        $response->assertSee($condition->name);
    }

    public function test_item_detail_shows_comments()
    {
        Storage::fake('public');

        $user = User::factory()->create([
            'name' => 'Test User',
            'image' => 'users/test.jpg', 
        ]);

        $item = Item::factory()->create();

        Comment::factory()->count(2)->create([
            'item_id' => $item->id,
            'user_id' => $user->id,
            'content' => 'Test Comment',
        ]);

        $response = $this->get('/item/' . $item->id);

        $response->assertSee('Test Comment');
        $response->assertSee('(2)');
        $response->assertSee('Test User');
        $response->assertSee(Storage::url('users/test.jpg'));
    }

    public function test_item_detail_shows_multiple_categories()
    {
        $this->seed(CategoriesTableSeeder::class);

        $categories = \App\Models\Category::take(3)->get();

        $item = Item::factory()->create();

        $item->categories()->attach($categories->pluck('id'));

        $response = $this->get('/item/' . $item->id);

        $response->assertStatus(200);

        foreach ($categories as $category) {
            $response->assertSee($category->name);
        }
    }

    // いいね
    public function test_user_can_like_item()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('item.like', ['id' => $item->id]));

        $response->assertRedirect();

        $this->assertDatabaseHas('likes', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);
    }

    public function test_liked_item_shows_pink_heart_icon()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $user->likes()->attach($item->id);

        $response = $this->actingAs($user)
            ->get('/item/' . $item->id);

        $response->assertStatus(200);

        $response->assertSee('ハートロゴ_ピンク.png');
    }

    public function test_user_can_unlike_item()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $user->likes()->attach($item->id);

        $response = $this->actingAs($user)
            ->get(route('item.unlike', ['id' => $item->id]));

        $response->assertRedirect();

        $this->assertDatabaseMissing('likes', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $response = $this->actingAs($user)
            ->get('/item/' . $item->id);

        $response->assertSee('ハートロゴ_デフォルト.png');
    }

    // コメント
    public function test_authenticated_user_can_create_comment()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $data = [
            'content' => 'Test Content',
            'user_id' => $user->id,
        ];

        $response = $this->actingAs($user)
            ->post('/comment/'.$item->id, $data);

        $response->assertRedirect();

        $this->assertDatabaseHas('comments', [
            'content' => 'Test Content',
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);
    }

    public function test_unauthenticated_user_cannot_create_comment()
    {
        $item = Item::factory()->create();
        
        $data = [
            'content' => 'Test Content',
        ];

        $response = $this->post('/comment/'.$item->id, $data);

        $response->assertRedirect('/login');
        
        $this->assertDatabaseMissing('comments', [
            'content' => 'Test Content',
            'item_id' => $item->id,
        ]);
    }

    public function test_comment_requires_content()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $data = [
            // contentなし
        ];

        $response = $this->actingAs($user)
            ->post('/comment/'.$item->id, $data);

        $response->assertSessionHasErrors([
            'content' => 'コメントを入力してください'
        ]);
    }

    public function test_comment_max_content()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $data = [
            'content' => str_repeat('a', 256),
        ];

        $response = $this->actingAs($user)
            ->post('/comment/'.$item->id, $data);

        $response->assertSessionHasErrors([
            'content' => 'コメントは255字以下で入力してください'
        ]);
    }


}
