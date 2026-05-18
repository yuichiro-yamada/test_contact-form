<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Requests\ContactRequest;

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
            'address',
            'building',
            'detail',
        ]);

        $contact['tel'] = $request->input('front-tel') . $request->input('middle-tel') . $request->input('back-tel');

        Contact::create($contact);
        return view('thanks');
    }
}