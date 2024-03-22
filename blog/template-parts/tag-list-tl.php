<?php

// 特定のカテゴリーIDに属する投稿を取得
$posts_in_category = get_posts(array(
    'posts_per_page' => -1, // 全投稿を取得
    'category'       => 669, // 特定のカテゴリーID
));

// 投稿に紐付けられたタグを集める
$tag_counts = array();
foreach ($posts_in_category as $post) {
    $post_tags = wp_get_post_tags($post->ID);
    foreach ($post_tags as $post_tag) {
        if (!array_key_exists($post_tag->term_id, $tag_counts)) {
            $tag_counts[$post_tag->term_id] = 1;
        } else {
            $tag_counts[$post_tag->term_id]++;
        }
    }
}

// タグを使用頻度でソート（降順）
arsort($tag_counts);

// 上位12件のタグIDを取得
$top_tag_ids = array_slice(array_keys($tag_counts), 0, 12, true);

// 上位12件のタグ情報を取得し、リスト表示
if (!empty($top_tag_ids)) {
    $tags = get_tags(array(
        'orderby' => 'count',
        'order'   => 'DESC',
        'include' => $top_tag_ids, // 上位12件のタグIDのみを含める
    ));

    if ($tags) {
        foreach ($tags as $tag) {
            echo '<li><a href="' . esc_url(add_query_arg('from_cat', '669', get_tag_link($tag->term_id))) . '">' . esc_html($tag->name) . '</a></li>';
        }
    }
}
