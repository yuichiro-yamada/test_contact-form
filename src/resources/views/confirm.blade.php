@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/confirm.css') }}">
@endsection

@section('content')
<h1 class="page-title">Confirm</h1>
<div class="confirm-contact">
    <form action="/thanks" method="post">
        @csrf
        <table class="confirm-table">
            <tr class="table-line">
                <th class="column-name">お名前</th>
                <td class="table-cell">
                    <span class="read-input">{{ $fullName }}</span>
                    <input type="hidden" name="last_name" value="{{ $contact['last_name'] }}">
                    <input type="hidden" name="first_name" value="{{ $contact['first_name'] }}">
                </td>
            </tr>
            <tr class="table-line">
                <th class="column-name">性別</th>
                <td class="table-cell">
                    <span class="read-input">{{ $genderName }}</span>
                    <input type="hidden" name="gender" value="{{ $contact['gender'] }}">
                </td>
            </tr>
            <tr class="table-line">
                <th class="column-name">メールアドレス</th>
                <td class="table-cell">
                    <span class="read-input">{{ $contact['email'] }}</span>
                    <input type="hidden" name="email" value="{{ $contact['email'] }}">
                </td>
            </tr>
            <tr class="table-line">
                <th class="column-name">電話番号</th>
                <td class="table-cell">
                    <span class="read-input">{{ $entireTel }}</span>
                    <input type="hidden" name="front-tel" value="{{ $contact['front-tel'] }}">
                    <input type="hidden" name="middle-tel" value="{{ $contact['middle-tel'] }}">
                    <input type="hidden" name="back-tel" value="{{ $contact['back-tel'] }}">
                </td>
            </tr>
            <tr class="table-line">
                <th class="column-name">住所</th>
                <td class="table-cell">
                    <span class="read-input">{{ $contact['address'] }}</span>
                    <input type="hidden" name="address" value="{{ $contact['address'] }}">
                </td>
            </tr>
            <tr class="table-line">
                <th class="column-name">建物名</th>
                <td class="table-cell">
                    <span class="read-input">{{ $contact['building'] }}</span>
                    <input type="hidden" name="building" value="{{ $contact['building'] }}">
                </td>
            </tr>
            <tr class="table-line">
                <th class="column-name">お問い合わせの種類</th>
                <td class="table-cell">
                    <span class="read-input">{{ $category->content }}</span>
                    <input type="hidden" name="category_id" value="{{ $contact['category_id'] }}">
                </td>
            </tr>
            <tr class="table-line">
                <th class="column-name">お問い合わせ内容</th>
                <td class="table-cell">
                    <span class="read-text text-area-view">{!! nl2br(e($contact['detail'])) !!}</span>
                    <input type="hidden" name="detail" value="{{ $contact['detail'] }}">
                </td>
            </tr>
        </table>
        <div class="buttons">
            <button class="submit-button" type="submit" name="action" value="post">送信</button>
            <button class="back-button" type="submit" name="action" value="modify">修正</button>
        </div>
    </form>
</div>
@endsection