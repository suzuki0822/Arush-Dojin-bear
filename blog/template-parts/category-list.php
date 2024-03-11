<?php
$args = array(
    'orderby'    => 'count', // 投稿数で並べ替える
    'order'      => 'DESC',  // 降順に並べる（多い順）
    'hide_empty' => false,   // 記事がないカテゴリも含める
);

// カテゴリ一覧を取得
$categories = get_categories(array(
    'exclude' => get_cat_ID('Uncategorized') // Uncategorized カテゴリを除外
));
// 取得したカテゴリを表示
foreach ($categories as $category) {
    echo '<li><a href="' . get_category_link($category->term_id) . '">' . $category->name . '</a></li>';
}

