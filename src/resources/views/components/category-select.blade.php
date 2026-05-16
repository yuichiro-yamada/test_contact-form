<!--カテゴリ選択-->

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
