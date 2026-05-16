@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/contact.css') }}" />
@endsection

@section('content')
<h1 class="page-title">Contact</h1>
<div class="input-contact">
    <form action="/confirm" method="post" novalidate>
        @csrf
        <div class="form-area">
            <table class="index-table" cellpadding="10">
                <tr class="table-line">
                    <th class="column-name">お名前<span class="attention">※</span></th>
                    <td id="name" class="table-cell">
                        <div class="name-separate">
                            <input class="input-area" type="text" name="last_name" placeholder="例）山田" value="{{ old('last_name') }}" >
                            <input class="input-area" type="text" name="first_name" placeholder="例）太郎" value="{{ old('first_name') }}">
                        </div>
                        <div class="error-message name_error">
                            <div class="last_name_error">
                                @error('last_name')
                                {{$message}}
                                @enderror
                            </div>
                            <div class="first_name_error">
                                @error('first_name')
                                {{$message}}
                                @enderror
                            </div>
                        </div>
                    </td>
                </tr>
                <tr class="table-line">
                    <th class="column-name">性別<span class="attention">※</span></th>
                    <td class="table-cell">
                        <div class="gender">
                            <div class="radio-item">
                                <input class="input-area gender-radio" type="radio" name="gender" value="1" 
                                    {{ old('gender', request('gender')) == '1' ? 'checked' : '' }}>
                                <label>男性</label>
                            </div>
                            <div class="radio-item">
                                <input class="input-area gender-radio" type="radio" name="gender" value="2" 
                                    {{ old('gender', request('gender')) == '2' ? 'checked' : '' }}>
                                <label>女性</label>
                            </div>
                            <div class="radio-item">
                                <input class="input-area gender-radio" type="radio" name="gender" value="3" 
                                {{ old('gender', request('gender')) == '3' ? 'checked' : '' }}>
                                <label>その他</label>
                            </div>
                        </div>
                        <div class="error-message">
                            @error('gender')
                            {{$message}}
                            @enderror
                        </div>
                    </td>


                </tr>
                <tr class="table-line">
                    <th class="column-name">メールアドレス<span class="attention">※</span></th>
                    <td id="email" class="table-cell">
                        <input class="input-area email-input" type="email" name="email" placeholder="test@example.com" value="{{old('email')}}">
                        <div class="error-message">
                            @error('email')
                            {{$message}}
                            @enderror
                        </div>
                    </td>
                </tr>
                <tr class="table-line">
                    <th class="column-name">電話番号<span class="attention">※</span></th>
                    <td id="tel" class="table-cell">
                        <div id="tel-align">
                            <input class="input-area tel-input" name="front-tel"
                                value="{{ old('front-tel', request('front-tel')) }}">

                            <span class="tel-bou">-</span>

                            <input class="input-area tel-input" name="middle-tel"
                                value="{{ old('middle-tel', request('middle-tel')) }}">

                            <span class="tel-bou">-</span>

                            <input class="input-area tel-input" name="back-tel"
                                value="{{ old('back-tel', request('back-tel')) }}">
                        </div>
                        <div class="error-message">
                        @if($errors->has('front-tel'))
                                {{ $errors->first('front-tel') }}
                        @elseif($errors->has('middle-tel'))
                                {{ $errors->first('middle-tel') }}
                            @elseif($errors->has('back-tel'))
                            {{ $errors->first('back-tel') }}
                        @endif
                        </div>
                    </td>
                </tr>
                <tr class="table-line">
                    <th class="column-name">住所<span class="attention">※</span></th>
                    <td id="address" class="table-cell">
                        <input class="input-area address-input" type="text" name="address" placeholder="例）東京都渋谷区千駄ヶ谷1-2-3" value="{{old('address')}}">
                        <div class="error-message">
                            @error('address')
                            {{$message}}
                            @enderror
                        </div>
                    </td>
                </tr>
                <tr class="table-line">
                    <th class="column-name">建物名</th>
                    <td id="building" class="table-cell">
                        <input class="input-area building-input" type="text" name="building" placeholder="例）千駄ヶ谷マンション101" value="{{old('building')}}">
                    </td>
                </tr>
                <tr class="table-line">
                    <th class="column-name">お問い合わせの種類<span class="attention">※</span></th>
                    <td class="table-cell">
                        <div id="category">
                            <!--カテゴリ選択-->
                            <select name="category_id" class="input-area category-select">

                                <option value=""
                                    {{ old('category_id', request('category_id')) == '' ? 'selected' : '' }}>
                                    選択してください
                                </option>

                                @foreach($categories as $category)
                                    <option
                                        value="{{ $category->id }}"
                                        {{ old('category_id', request('category_id')) == $category->id ? 'selected' : '' }}
                                    >
                                        {{ $category->content }}
                                    </option>
                                @endforeach

                            </select>
                        </div>
                        <div class="error-message">
                            @error('category_id')
                            {{$message}}
                            @enderror
                        </div>
                    </td>
                </tr>
                <tr class="table-line">
                    <th id="align-up" class="column-name">お問い合わせ内容<span class="attention">※</span></th>
                    <td id="detail" class="table-cell">
                        <textarea class="input-area detail-text" name="detail" placeholder="お問い合わせ内容をご記載ください">{{old('detail')}}</textarea>
                        <div class="error-message">
                            @error('detail')
                            {{$message}}
                            @enderror
                        </div>
                    </td>
                </tr>
            </table>
            <div class="submit-form">
                <button class="confirm_button" type="submit">確認画面</button>
            </div>
        </div>
    </form>
</div>
@endsection