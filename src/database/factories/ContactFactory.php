<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Contact;
use \App\Models\Category;

class ContactFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        // 1. category_id（1〜5）をランダムに決定して変数に分ける
        $categoryId = Category::inRandomOrder()->first()->id;

        // 2. カテゴリごとの問い合わせ内容
        $detailsByCategory = [
            1 => [ // 商品のお届けについて
                "注文した商品がまだ届かないのですが、発送状況を教えていただけますか？\n配送伝票番号が分かれば合わせて教えてほしいです。",
                "本日届く予定の荷物について、配送先の住所を間違えて登録してしまいました。\n今からでもお届け先の変更は可能でしょうか？\n注文番号は #12345 です。",
                "先ほど注文を完了したのですが、お届け日時の指定を忘れてしまいました。\n最短の日程で発送していただくことは可能でしょうか？",
            ],
            2 => [ // 商品の交換について
                "届いた商品のサイズが合わなかったため、別のサイズに交換したいです。\n往復の送料はどちらの負担になりますか？",
                "プレゼント用に購入したのですが、相手が同じものを持っていたため別の商品と交換することは可能でしょうか？\n未開封・未使用の状態です。\n手順を教えてください。",
                "注文したものとは違う色（ブラックではなくホワイト）の商品が届きました。\n正しい商品への交換手続きをお願いいたします。",
            ],
            3 => [ // 商品トラブル
                "本日商品が届きましたが、開封したところ一部に破損（ひび割れ）がありました。\n初期不良だと思われますが、どのように対応すればよろしいでしょうか？\n写真を送る方法も教えてください。",
                "届いた商品の電源が入りません。\n説明書通りに充電を行いましたが、ランプが点灯しない状態です。",
                "衣類を購入したのですが、ボタンが一つ取れかかっていました。\n交換用の在庫があるか確認をお願いします。",
            ],
            4 => [ // ショップへのお問い合わせ
                "法人での大口注文を検討しているのですが、見積書を発行していただくことは可能でしょうか？\n可能であれば、問い合わせ用の窓口を教えてください。",
                "実店舗での販売や、商品の直接受け取りができる場所はありますか？",
                "メディア取材の件でご相談がありご連絡いたしました。\n広報担当者様、または責任者様のご連絡先を教えていただけますでしょうか？\nよろしくお願いいたします。",
            ],
            5 => [ // その他
                "メルマガの配信停止を行いたいのですが、マイページの設定画面が見当たりません。\n解除方法を教えてください。",
                "領収書の発行をお願いしたいです。\n宛名は「〇〇株式会社」でお願いいたします。",
                "ログインパスワードを忘れてしまい、登録したメールアドレスも現在使われていないため再設定ができません。\n対応方法を教えてください。",
            ],
        ];

        // 3. 決定したcategory_idのリストから、ランダムに1つ文章を選ぶ
        $detail = $this->faker->randomElement($detailsByCategory[$categoryId]);

        // 💡 修正：15件で止まってしまう重複エラーを防ぐため、文章の最後にランダムな「問い合わせ番号」を付与
        $detail .= "\n(お問い合わせID: " . $this->faker->unique()->numberBetween(1000, 9999) . ")";

        return [
            'category_id' => $categoryId,
            'first_name' => $this->faker->unique()->firstName(), // 重複エラー対策
            'last_name' => $this->faker->unique()->lastName(),   // 重複エラー対策
            'gender' => $this->faker->numberBetween(1, 3),
            'email' => $this->faker->unique()->safeEmail(),       // 重複エラー対策
            'tel' => $this->faker->unique()->phoneNumber(),       // 重複エラー対策
            // 変更点：都道府県 ＋ 市区町村 ＋ 番地（建物名なし）を結合して生成
            'address' => $this->faker->prefecture() . $this->faker->city() . $this->faker->streetAddress(),
            'building' => $this->faker->optional(0.8)->secondaryAddress(),
            'detail' => $detail
        ];
    }
}
