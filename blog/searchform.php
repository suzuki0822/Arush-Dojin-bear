<form action="/" method="get" class="search-form">
    <input type="search" name="s" placeholder="検索…" class="search-field" value="<?php the_search_query(); ?>" />
    <button type="submit" class="search-submit">検索</button>
    <div class="select-container">
        <select name="category" class="search-category">
            <option value="">男性向け/TL・BL作品選択</option>
            <?php
            $include_categories = array(669, 670, 408); // 表示したいカテゴリーIDを配列にセット
            $categories = get_categories(array(
                'include' => $include_categories,
            ));
            foreach ($categories as $category) {
                echo '<option value="' . $category->term_id . '">' . $category->name . '</option>';
            }
            ?>
        </select>
    </div>
</form>