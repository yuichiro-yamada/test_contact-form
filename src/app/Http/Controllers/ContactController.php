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
        return view('index');
    }

    public function confirm(ContactRequest $request)
    {
        $contact = $request->all();

        $frontTel = implode(",", $request->only(['front-tel']));
        $middleTel = implode(",", $request->only(['middle-tel']));
        $backTel = implode(",", $request->only(['back-tel']));
        $entireTel = $frontTel . $middleTel . $backTel;

        $lastName = implode(",", $request->only(['last_name']));
        $firstName = implode(",", $request->only(['first_name']));
        $fullName = $lastName . " " . $firstName;

        return view('confirm', ['contact' => $contact, 'entireTel' => $entireTel, 'fullName' => $fullName]);
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

        $genderType = implode(",", $request->only('gender'));
        if ($genderType == "男性") {
            $contact['gender'] = 1;
        } elseif ($genderType == "女性") {
            $contact['gender'] = 2;
        } elseif ($genderType == "その他") {
            $contact['gender'] = 3;
        }

        $categoryType = implode(",", $request->only(['category_id']));
        if ($categoryType == "商品のお届けについて") {
            $contact['category_id'] = 1;
        } elseif ($categoryType == "商品の交換について") {
            $contact['category_id'] = 2;
        } elseif ($categoryType == "商品トラブル") {
            $contact['category_id'] = 3;
        } elseif ($categoryType == "ショップへのお問い合わせ") {
            $contact['category_id'] = 4;
        } elseif ($categoryType == "その他") {
            $contact['category_id'] = 5;
        }

        Contact::create($contact);
        return view('thanks');
    }

    public function admin(Request $request)
    {
        $contacts = Contact::with('category')->Paginate(10);

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
        return view('admin', compact('contacts', 'modal_data'));
    }

    public function search(Request $request)
    {
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
        $contacts = $query->paginate(10);

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
        ]);
    }

    public function delete(Request $request)
    {
        $contact = Contact::findOrFail($request->id);
        $contact->delete();
        return redirect('/admin');
    }
}
