<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Requests\ContactRequest;
use Normalizer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class ContactController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return view('index',compact('categories'));
    }

    public function confirm(ContactRequest $request)
    {
        $contact = $request->all();
        $category = Category::find($request->category_id);

        $frontTel = implode(",", $request->only(['front-tel']));
        $middleTel = implode(",", $request->only(['middle-tel']));
        $backTel = implode(",", $request->only(['back-tel']));
        $entireTel = $frontTel . $middleTel . $backTel;

        $lastName = implode(",", $request->only(['last_name']));
        $firstName = implode(",", $request->only(['first_name']));
        $fullName = $lastName . " " . $firstName;

    // 1. 性別のマッピング配列を定義
    $genderList = [
        '1' => '男性',
        '2' => '女性',
        '3' => 'その他',
    ];

    $genderName = $genderList[$request->gender];

        return view('confirm', compact('contact','entireTel','fullName','category','genderName'));
    }

    public function store(Request $request)
    {
        if ($request->get('action') === 'modify') {
            return redirect()->route('rewrite')->withInput();
        }

        $contact = $request->only([
            'category_id',
            'first_name',
            'last_name',
            'gender',
            'email',
            'tel',
            'address',
            'building',
            'detail',
        ]);

        Contact::create($contact);
        return view('thanks');
    }

    public function admin(Request $request)
    {
        $contacts = Contact::with('category')->Paginate(7);

        // カテゴリマスタ取得
        $categories = Category::all();

        $modal_data = null;

        if ($request->modal_id) {
            $modal_data = Contact::with('category')
            ->find($request->modal_id);
        }

        foreach($contacts->items() as $contact) {
            if ($contact->gender == 1) {
                $contact->gender = "男性";
            } elseif ($contact->gender == 2) {
                $contact->gender = "女性";
            } elseif ($contact->gender == 3) {
                $contact->gender = "その他";
            }
        }
        return view('admin', compact('contacts', 'modal_data','categories'));
    }

    public function search(Request $request)
    {
        $categories = Category::all();

        $query = Contact::with('category');

        $name_email_filter = $request->name_email_filter;
        $gender_dropdown = $request->gender_dropdown;
        $category_dropdown = $request->category_dropdown;
        $date_calendar = $request->date_calendar;

        // 名前・メール検索
        if (!empty($name_email_filter)) {
            $normalized_filter = Normalizer::normalize(
                $name_email_filter,
                Normalizer::FORM_C
            );

            $query->where(function ($query) use ($normalized_filter) {

                $query->where(
                    DB::raw("CONCAT(last_name, first_name)"),
                    'like',
                    '%' . $normalized_filter . '%'
                )
                ->orWhere(
                    'email',
                    'like',
                    '%' . $normalized_filter . '%'
                );
            });
        }
        // 性別の絞り込み（画面から「全て」が選ばれたときは絞り込まないよう考慮）
        if (!empty($gender_dropdown)&& $gender_dropdown !== '0') {
            $query->where('gender', $gender_dropdown);
        }
        // カテゴリ
        if (!empty($category_dropdown)) {
            $query->where('category_id', $category_dropdown);
        }
        // 日付
        if (!empty($date_calendar)) {
            $query->whereDate('created_at', '=', $date_calendar);
        }
        // 検索条件を維持したまま10件ずつページネーション
        $contacts = $query->paginate(7);

        //★★★安全な foreach 文に書き換え（items() を使用し、バグを100%回避）
        foreach ($contacts->items() as $contact) {
            if ($contact->gender == 1) {
                $contact->gender = "男性";
            } elseif ($contact->gender == 2) {
                $contact->gender = "女性";
            } elseif ($contact->gender == 3) {
                $contact->gender = "その他";
            }
        }
        // モーダル用データ
        $modal_data = null;

        if ($request->modal_id) {

            $modal_data = Contact::with('category')
                ->find($request->modal_id);

            // モーダル側も性別変換
            if ($modal_data) {

                if ($modal_data->gender == 1) {
                    $modal_data->gender = '男性';

                } elseif ($modal_data->gender == 2) {
                    $modal_data->gender = '女性';

                } elseif ($modal_data->gender == 3) {
                    $modal_data->gender = 'その他';
                }
            }
        }
        return view('admin', [
            'name_email_filter' => $name_email_filter,
            'gender_dropdown' => $gender_dropdown,
            'category_dropdown' => $category_dropdown,
            'date_calendar' => $date_calendar,
            'contacts' => $contacts,
            'modal_data' => $modal_data,
            'categories' => $categories,
        ]);
    }

    public function reset()
    {
        return redirect('/admin');
    }


    public function delete(Request $request)
    {
        $contact = Contact::findOrFail($request->id);
        $contact->delete();
        return redirect('/admin');
    }

    public function export(Request $request)
    {
        $query = Contact::with('category');

        $name_email_filter = $request->name_email_filter;
        $gender_dropdown = $request->gender_dropdown;
        $category_dropdown = $request->category_dropdown;
        $date_calendar = $request->date_calendar;

        // 1. 名前・メール検索（searchメソッドと完全に同一のロジックに修正）
        if (!empty($name_email_filter)) {
            $normalized_filter = Normalizer::normalize(
                $name_email_filter,
                Normalizer::FORM_C
            );

            $query->where(function ($query) use ($normalized_filter) {
                $query->where(
                    DB::raw("CONCAT(last_name, first_name)"),
                    'like',
                    '%' . $normalized_filter . '%'
                )
                ->orWhere(
                    'email',
                    'like',
                    '%' . $normalized_filter . '%'
                );
            });
        }

        // 2. 性別の絞り込み（画面から「全て(0)」が選ばれたときは絞り込まない）
        if (!empty($gender_dropdown) && $gender_dropdown !== '0') {
            $query->where('gender', $gender_dropdown);
        }

        // 3. カテゴリ
        if (!empty($category_dropdown)) {
            $query->where('category_id', $category_dropdown);
        }

        // 4. 日付
        if (!empty($date_calendar)) {
            $query->whereDate('created_at', '=', $date_calendar);
        }

        // 全件ではなく、絞り込んだ結果を取得（exportなのでpaginateではなくget）
        $contacts = $query->get();

        $fileName = 'contacts.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$fileName}",
        ];

        $callback = function () use ($contacts) {
            $stream = fopen('php://output', 'w');

            // Excel文字化け対策
            fwrite($stream, "\xEF\xBB\xBF");

            // ヘッダー行
            fputcsv($stream, [
                'お名前',
                '性別',
                'メールアドレス',
                'お問い合わせ種類',
            ]);

            // 性別のマッピング配列
            $genderList = [
                '1' => '男性',
                '2' => '女性',
                '3' => 'その他',
            ];

            // データ行
            foreach ($contacts as $contact) {
                // 性別の数値をテキスト（男性/女性/その他）に変換
                $genderName = $genderList[$contact->gender] ?? '不明';

                fputcsv($stream, [
                    $contact->last_name . ' ' . $contact->first_name, // 半角スペースを挟む場合は適宜調整してください
                    $genderName, // 変換後のテキストを出力
                    $contact->email,
                    $contact->category ? $contact->category->content : '', // もしくは $contact->category->getCategory() 等リレーションに応じて調整
                ]);
            }

            fclose($stream);
        };

        return response()->stream($callback, 200, $headers);
    }
}
