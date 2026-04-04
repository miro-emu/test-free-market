<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Item;
use App\Models\User;
use App\Models\Category;
use App\Models\Condition;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Database\Seeders\ConditionsTableSeeder;
use Database\Seeders\CategoriesTableSeeder;


class SellTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_item()
    {
        Storage::fake('public');

        $user = User::factory()->create();

        $condition = Condition::factory()->create();
        $categories = Category::factory()->count(2)->create();

        $data = [
            'name' => 'Test Name',
            'image' => UploadedFile::fake()->create('test.jpg', 100),
            'brand' => 'Test brand',
            'price' => 1000,
            'description' => 'Test Description',
            'condition_id' => $condition->id,
            'category_ids' => $categories->pluck('id')->toArray(),
        ];

        $response = $this->actingAs($user)
            ->post('/sell', $data);

        $response->assertRedirect();
        $response->assertStatus(302);
        $response->assertSessionHasNoErrors(); 

        $this->assertDatabaseHas('items', [
            'name' => 'Test Name',
            'condition_id' => $condition->id,
        ]);

        $this->assertDatabaseHas('category_item', [
            'category_id' => $categories[0]->id,
        ]);
    }
}
