<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ItemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $param = [
            'user_id' => 1,
            'name' => '腕時計',
            'image' => 'images/watch.jpg',
            'brand' => 'Rolax',
            'price' => 15000,
            'description' => 'スタイリッシュなデザインのメンズ腕時計',
            'condition_id' => 1
        ];
        DB::table('items')->insert($param);
        
        $param = [
            'user_id' => 1,
            'name' => 'HDD',
            'image' => 'images/HDD.jpg',            
            'brand' => '西芝',
            'price' => 5000,
            'description' => '高速で信頼性の高いハードディスク',
            'condition_id' => '2'
        ];
        DB::table('items')->insert($param);

        $param = [
            'user_id' => 1,
            'name' => '玉ねぎ3束',
            'image' => 'images/onion.jpg',
            'price' => 300,
            'description' => '新鮮な玉ねぎ3束のセット',
            'condition_id' => 3
        ];
        DB::table('items')->insert($param);
        
        $param = [
            'user_id' => 1,
            'name' => '革靴',
            'image' => 'images/shoes.jpg',
            'price' => 4000,
            'description' => 'クラシックなデザインの革靴',
            'condition_id' => 4
        ];
        DB::table('items')->insert($param);

        $param = [
            'user_id' => 1,
            'name' => 'ノートPC',
            'image' => 'images/laptop.jpg',
            'price' => 45000,
            'description' => '高性能なノートパソコン',
            'condition_id' => 1
        ];
        DB::table('items')->insert($param);

        $param = [
            'user_id' => 1,
            'name' => 'マイク',
            'image' => 'images/microphone.jpg',
            'price' => 8000,
            'description' => '高音質のレコーディング用マイク',
            'condition_id' => 2
        ];
        DB::table('items')->insert($param);

        $param = [
            'user_id' => 1,
            'name' => 'ショルダーバッグ',
            'image' => 'images/bag.jpg',
            'price' => 3500,
            'description' => 'おしゃれなショルダーバッグ',
            'condition_id' => 3
        ];
        DB::table('items')->insert($param);

        $param = [
            'user_id' => 1,
            'name' => 'タンブラー',
            'image' => 'images/tumbler.jpg',
            'price' => 500,
            'description' => '使いやすいタンブラー',
            'condition_id' => 4
        ];
        DB::table('items')->insert($param);

        $param = [
            'user_id' => 1,
            'name' => 'コーヒーミル',
            'image' => 'images/mill.jpg',
            'brand' => 'Starbacks',
            'price' => 4000,
            'description' => '手動のコーヒーミル',
            'condition_id' => 1
        ];
        DB::table('items')->insert($param);
        
        $param = [
            'user_id' => 1,
            'name' => 'メイクセット',
            'image' => 'images/make.jpg',
            'price' => 2500,
            'description' => '便利なメイクアップセット',
            'condition_id' => 2
        ];
        DB::table('items')->insert($param);
    }
}
