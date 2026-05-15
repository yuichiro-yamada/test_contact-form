@extends('layouts.authapp')


@section('css')
<link rel="stylesheet" href="{{asset('css/admin.css')}}">
@endsection

@section('header')
@if(Auth::check())
<form action="/logout" method="post">
    @csrf
    <button type="submit" class="logout">Logout</button>
</form>
@endif
@endsection

@section('content')
<h1 class="page-title">Admin</h1>
<div class="contains">
    <form action="/admin/search" method="get">
        @csrf
        <div class="search-form">
            <div class="name-email">
                <input name="name_email_filter" type="text" class="name_email_filter" placeholder="名前やメールアドレスを入力してください">
                <input type="submit" value="🔍" class="search-button">
            </div>
            <div class="gender">
                <select name="gender_dropdown" class="gender_dropdown">
                    <option selected disabled>性別</option>
                    <option value="0">全て</option>
                    <option value="1">男性</option>
                    <option value="2">女性</option>
                    <option value="3">その他</option>
                </select>
            </div>
            <div class="category_id">
                <select name="category_dropdown" class="category_dropdown">
                    <option disabled selected>お問い合わせ種類</option>
                    <option value="1">商品のお届けについて</option>
                    <option value="2">商品の交換について</option>
                    <option value="3">商品トラブル</option>
                    <option value="4">ショップへのお問い合わせ</option>
                    <option value="5">その他</option>
                </select>
            </div>
            <div class="date">
                <input type="date" name="date_calendar" class="date_calendar">
            </div>
        </div>
    </form>
    <div class="contacts-table">
        <div class="above-table">
            <form action="/admin/csv-download" method="get">
                @csrf
                <button type="submit" class="export">エクスポート</button>
            </form>
            <div class="pagination">
                {{$contacts->appends(request()->query())->links()}}
            </div>
        </div>
        <table class="contacts-database" cellspacing="0">
            <tr>
                <th class="column-name">お名前</th>
                <th class="column-name">性別</th>
                <th class="column-name">メールアドレス</th>
                <th class="column-name">お問い合わせの種類</th>
                <th class="column-name"></th>
                <th class="column-name"></th>
            </tr>
            <!--
            「詳細」ボタンを押した際、一覧で見えている「お名前」や「お問い合わせ種類」も
            モーダル側に簡単にコピーできるよう、<td> タグに識別用IDを付与
            -->
            @foreach($contacts as $contact)
            <tr>
                <td class="name_get" id="name-{{$contact->id}}">{{$contact->last_name}}{{$contact->first_name}}</td>
                <td class="gender_get" id="gender-{{$contact->id}}">{{$contact->gender}}</td>
                <td class="email_get" id="email-{{$contact->id}}">{{$contact->email}}</td>
                <td class="category_get" id="category-{{$contact->id}}">{{$contact->category->getCategory()}}</td>
                <td class="created_get">
                    <input type="hidden" class="date_get{{$contact->id}}" name="date" value="{{$contact->created_at->format('Y-m-d')}}">
                </td>
                <td class="detail_get">
                    <div class="detail-view" id="detail-btn-{{ $contact->id }}" data-id="{{ $contact->id }}">
                        詳細
                    </div>
                </td>
                <!-- モーダルやJSで呼び出すための隠しデータ -->
                <input type="hidden" class="tel_get{{$contact->id}}" value="{{$contact->tel}}">
                <input type="hidden" class="address_get{{$contact->id}}" value="{{$contact->address}}">
                <input type="hidden" class="building_get{{$contact->id}}" value="{{$contact->building}}">
                <input type="hidden" class="detail_get{{$contact->id}}" value="{{$contact->detail}}">
            </tr>
            @endforeach
        </table>
        <!-- 以下モーダル -->
        <div class="modal">
            <div class="close-button">
                ×
            </div>
            <table class="modal-table">
                <tr>
                    <th class="modal-title">お名前</th>
                    <td class="full-name-modal modal-cell"></td>
                </tr>
                <tr>
                    <th class="modal-title">性別</th>
                    <td class="gender-modal modal-cell"></td>
                </tr>
                <tr>
                    <th class="modal-title">メールアドレス</th>
                    <td class="email-modal modal-cell"></td>
                </tr>
                <tr>
                    <th class="modal-title">電話番号</th>
                    <td class="tel-modal modal-cell"></td>
                </tr>
                <tr>
                    <th class="modal-title">住所</th>
                    <td class="address-modal modal-cell"></td>
                </tr>
                <tr>
                    <th class="modal-title">建物名</th>
                    <td class="building-modal modal-cell"></td>
                </tr>
                <tr>
                    <th class="modal-title">お問い合わせの種類</th>
                    <td class="category-modal modal-cell"></td>
                </tr>
                <tr>
                    <th class="modal-title detail-title">お問い合わせ内容</th>
                    <td class="detail-modal modal-cell"><textarea class="detail-text-modal" readonly></textarea></td>
                </tr>
            </table>
            <div class="delete">
                <form action="/admin/delete" method="post">
                    @csrf
                    <input name="id" type="hidden" value="" class="delete-id">
                    <button class="delete-button" type="submit">削除</button>
                </form>
            </div>
        </div>
        <div class="reset">
            <a href="/admin" class="reset-link">リセット</a>
        </div>
    </div>
</div>
@endsection