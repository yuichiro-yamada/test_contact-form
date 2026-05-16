<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Category;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 登録したい5つのカテゴリデータをID固定で定義
        $categories = [
            ['id' => 1, 'content' => '商品のお届けについて'],
            ['id' => 2, 'content' => '商品の交換について'],
            ['id' => 3, 'content' => '商品トラブル'],
            ['id' => 4, 'content' => 'ショップへのお問い合わせ'],
            ['id' => 5, 'content' => 'その他'],
        ];

        foreach ($categories as $category) {
            // すでにIDが存在する場合は上書き、なければ新規作成（重複エラー防止）
            Category::updateOrCreate(
                ['id' => $category['id']],
                ['content' => $category['content']]
            );
        }
    }
}
