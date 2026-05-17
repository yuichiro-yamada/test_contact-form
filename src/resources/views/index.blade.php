@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/contact.css') }}" />
@endsection

@section('content')
<h1 class="page-title">Contact</h1>
<div class="input-contact">
    <form action="/confirm" method="post" novalidate>
        @csrf

            <table class="index-table" cellpadding="10">
                <tr class="table-line">
                    <th class="column-name">お名前<span class="attention">※</span></th>
                    <td class="table-cell">
                        <div class="name-separate">
                            <div class="name-wrap">
                                <input class="name-input-area" type="text" name="last_name" placeholder="例）山田" value="{{ old('last_name') }}">

                                <div class="error-message">
                                    @error('last_name')
                                    {{$message}}
                                    @enderror
                                </div>

                            </div>
                            <div class="name-wrap">
                                <input class="name-input-area" type="text" name="first_name" placeholder="例）太郎" value="{{ old('first_name') }}">
                                <div class="error-message">
                                    @error('first_name')
                                    {{$message}}
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr class="table-line">
                    <th class="column-name">性別<span class="attention">※</span></th>
                    <td class="table-cell">
                        <div class="gender">
                            <div class="radio-item">
                                <label>
                                    <input class="input-area gender-radio" type="radio" name="gender" value="1" 
                                        {{ old('gender', request('gender')) == '1' ? 'checked' : '' }}>
                                    <span>男性</span>
                                </label>
                            </div>
                            <div class="radio-item">
                                <label>
                                    <input class="input-area gender-radio" type="radio" name="gender" value="2" 
                                        {{ old('gender', request('gender')) == '2' ? 'checked' : '' }}>
                                    <span>女性</span>
                                </label>
                            </div>
                            <div class="radio-item">
                                <label>
                                    <input class="input-area gender-radio" type="radio" name="gender" value="3" 
                                    {{ old('gender', request('gender')) == '3' ? 'checked' : '' }}>
                                    <span>その他</span>
                                </label>
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
                        <input class="input-area " type="email" name="email" placeholder="test@example.com" value="{{old('email')}}">
                        <div class="error-message">
                            @error('email')
                            {{$message}}
                            @enderror
                        </div>
                    </td>
                </tr>
                <tr class="table-line">
                    <th class="column-name">電話番号<span class="attention">※</span></th>
                    <td class="table-cell">
                        <div class="tel-align">
                            <div class="tel-wrap">
                                <input class="tel-input" name="front-tel"
                                    value="{{ old('front-tel', request('front-tel')) }}">
                                <div class="tel-error-area error-message">
                                    @if($errors->has('front-tel'))
                                        {{ $errors->first('front-tel') }}
                                    @endif
                                </div>
                            </div>
                            <span class="tel-bou">-</span>
                            <div class="tel-wrap">
                                <input class="tel-input" name="middle-tel"
                                    value="{{ old('middle-tel', request('middle-tel')) }}">
                                <div class="tel-error-area error-message">
                                    @if($errors->has('middle-tel'))
                                        {{ $errors->first('middle-tel') }}
                                    @endif
                                </div>
                            </div>
                            <span class="tel-bou">-</span>
                            <div class="tel-wrap">
                                <input class="tel-input" name="back-tel"
                                    value="{{ old('back-tel', request('back-tel')) }}">
                                <div class="tel-error-area error-message">
                                    @if($errors->has('back-tel'))
                                        {{ $errors->first('back-tel') }}
                                    @endif
                                </div>
                            </div>
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
                    <td class="table-cell">
                        <input class="input-area" type="text" name="building" placeholder="例）千駄ヶ谷マンション101" value="{{old('building')}}">
                    </td>
                </tr>
                <tr class="table-line">
                    <th class="column-name">お問い合わせの種類<span class="attention">※</span></th>
                    <td class="table-cell">
                        <div class="category">
                            <!--カテゴリ選択-->
                            <select name="category_id" class="category-select">

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
                    <td class="table-cell">
                        <textarea class="input-text-area detail-text" name="detail" placeholder="お問い合わせ内容をご記載ください">{{old('detail')}}</textarea>
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

    </form>
</div>
@endsection