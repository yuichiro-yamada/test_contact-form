<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User; 
use Illuminate\Support\Facades\Hash; 

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 指定された情報で1件のユーザーを登録
        User::updateOrCreate(                           //更新か新規作成か
            ['email' => 'ichiro@test.com'],             // メールアドレスが既存でないか探す
            [
                'name' => '鈴木一郎',                    // 見つかったら上書き
                'password' => Hash::make('11111111'),   // なければ新しく作成する
            ]
        );
    }
}
