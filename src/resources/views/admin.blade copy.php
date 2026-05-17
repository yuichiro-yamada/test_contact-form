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
    <form action="/search" method="get">
        <div class="search-form">
            <div class="name-email">
                <input name="name_email_filter" type="text" class="name_email_filter" 
                    placeholder="名前やメールアドレスを入力してください" 
                    value="{{ request('name_email_filter', '') }}">
                <input type="submit" value="🔍" class="search-button">
            </div>
            <div class="gender">
                <select name="gender_dropdown" class="gender_dropdown">
                    <option value="" {{ request('gender_dropdown') === null || request('gender_dropdown') === '' ? 'selected' : '' }}>
                        性別
                    </option>

                    <option value="0" {{ request('gender_dropdown') === '0' ? 'selected' : '' }}>
                        全て
                    </option>

                    <option value="1" {{ request('gender_dropdown') === '1' ? 'selected' : '' }}>
                        男性
                    </option>

                    <option value="2" {{ request('gender_dropdown') === '2' ? 'selected' : '' }}>
                        女性
                    </option>

                    <option value="3" {{ request('gender_dropdown') === '3' ? 'selected' : '' }}>
                        その他
                    </option>
                </select>
            </div>
            <div class="category_id">
                <!--カテゴリ選択-->
                <select name="category_dropdown" class="category_dropdown">
                    <option value="" {{ request('category_dropdown') === null || request('category_dropdown') === '' ? 'selected' : '' }}>
                        お問い合わせ種類
                    </option>
                    @foreach($categories as $category)
                        <option
                            value="{{ $category->id }}"
                            {{ request('category_dropdown') == $category->id ? 'selected' : '' }}
                        >
                            {{ $category->content }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="date">
                <input type="date" name="date_calendar" class="date_calendar" 
                    value="{{ request('date_calendar', '') }}">
            </div>
            <button type="submit" value="検索">
            <button type="submit" value="検索">
            <div class="reset">
                <a href="/reset" class="reset-link">リセット</a>
            </div>
        </div>

        <div class="contacts-table">
            <div class="above-table">
                <button type="submit" class="export" formaction="/export">エクスポート</button>
                <div class="pagination">
                    {{$contacts->appends(request()->query())->links()}}
                </div>
            </div>
        </div>
    </form>
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
                <!-- 各行専用の小さなフォームを作ってサーバーに送信する -->
                <form action="/search" method="get" style="display: inline;">
                    <!-- 現在の検索条件を隠しデータ（input type="hidden"）として引き継ぐ -->
                    <input type="hidden" name="name_email_filter" value="{{ request('name_email_filter') }}">
                    <input type="hidden" name="gender_dropdown" value="{{ request('gender_dropdown') }}">
                    <input type="hidden" name="category_dropdown" value="{{ request('category_dropdown') }}">
                    <input type="hidden" name="date_calendar" value="{{ request('date_calendar') }}">
                    
                    <!-- モーダルで表示したいIDを送信 -->
                    <input type="hidden" name="modal_id" value="{{ $contact->id }}">
                    
                    <!-- ボタン（見た目はCSSで今まで通りに調整してください） -->
                    <button type="submit" class="detail-view" style="border: none; background: none; cursor: pointer;">
                        詳細
                    </button>
                </form>
            </td>
        </tr>
        <!-- モーダルやJSで呼び出すための隠しデータ -->
        <input type="hidden" class="tel_get{{$contact->id}}" value="{{$contact->tel}}">
        <input type="hidden" class="address_get{{$contact->id}}" value="{{$contact->address}}">
        <input type="hidden" class="building_get{{$contact->id}}" value="{{$contact->building}}">
        <input type="hidden" class="detail_get{{$contact->id}}" value="{{$contact->detail}}">
        @endforeach
    </table>

    <!-- モーダル表示 -->
    <div class="modal {{ request('modal_id') ? 'active' : '' }}">
        <a href="{{ request()->fullUrlWithQuery(['modal_id' => null]) }}" class="close-button" style="text-decoration: none; color: inherit;">
            ×
        </a>
        <table class="modal-table">
            <tr>
                <th class="modal-title">お名前</th>
                    <td class="full-name-modal modal-cell">
                        {{ ($modal_data?->last_name ?? '') . ($modal_data?->first_name ?? '') }}
                    </td>
            </tr>
            <tr>
                <th class="modal-title">性別</th>
                <td class="gender-modal modal-cell">
                    {{ data_get($modal_data, 'gender', '') }}
                </td>
            </tr>
            <tr>
                <th class="modal-title">メールアドレス</th>
                <td class="email-modal modal-cell">
                    {{ data_get($modal_data, 'email', '') }}
                </td>
            </tr>
            <tr>
                <th class="modal-title">電話番号</th>
                <td class="tel-modal modal-cell">
                    {{ data_get($modal_data, 'tel', '') }}
                </td>
            </tr>
            <tr>
                <th class="modal-title">住所</th>
                <td class="address-modal modal-cell">
                    {{ data_get($modal_data, 'address', '') }}
                </td>
            </tr>
            <tr>
                <th class="modal-title">建物名</th>
                <td class="building-modal modal-cell">
                    {{ data_get($modal_data, 'building', '') }}
                </td>
            </tr>
            <tr>
                <th class="modal-title">お問い合わせの種類</th>
                    <td class="category-modal modal-cell">
                        {{ $modal_data?->category?->getCategory() ?? '' }}
                    </td>
            </tr>
            <tr>
                <th class="modal-title detail-title">お問い合わせ内容</th>
                <td class="detail-modal modal-cell">
                    <div class="detail-text-modal">{{ data_get($modal_data, 'detail', '') }}</div>
                </td>
            </tr>
        </table>
        <div class="delete">
            <form action="/delete" method="post">
                @csrf
                <input name="id" type="hidden" value="{{ $modal_data->id ?? '' }}">
                <button class="delete-button" type="submit">削除</button>
            </form>
        </div>
    </div>
    <div class="reset">
        <a href="/reset" class="reset-link">リセット</a>
    </div>
</div>
@endsection