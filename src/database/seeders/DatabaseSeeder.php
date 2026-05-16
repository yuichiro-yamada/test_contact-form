<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Contact;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // 5つのカテゴリを登録
        $this->call(CategoriesTableSeeder::class);

        // ContactモデルのFactoryを呼び出し、35件のダミーデータを生成
        Contact::factory(35)->create();

        // ユーザーを登録（鈴木一郎さん 1件）
        $this->call(UsersTableSeeder::class);
    }
}
