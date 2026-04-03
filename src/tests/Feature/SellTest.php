<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Item;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Database\Seeders\ConditionsTableSeeder;
use Database\Seeders\CategoriesTableSeeder;


class SellTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_item()
    {
        $this->seed([
            ConditionsTableSeeder::class,
            CategoriesTableSeeder::class,
        ]);

        Storage::fake('public');

        $user = User::factory()->create();

        $data = [
            'name' => 'Test Name',
            'image' => UploadedFile::fake()->create('test.jpg', 100),
            'brand' => 'Test brand',
            'price' => 1000,
            'description' => 'Test Description',
            'condition_id' => 1,
            'category_ids' => [1, 2],
        ];

        $response = $this->actingAs($user)
            ->post('/sell', $data);

        $response->assertRedirect();
        $response->assertStatus(302);
        $response->assertSessionHasNoErrors(); 

        $this->assertDatabaseHas('items', [
            'name' => 'Test Name',
            'condition_id' => 1,
        ]);

        $this->assertDatabaseHas('category_item', [
            'category_id' => 1,
        ]);
    }
}
