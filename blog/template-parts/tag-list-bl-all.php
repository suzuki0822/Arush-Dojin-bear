<?php

// 特定のカテゴリーIDに属する投稿を取得
$posts_in_category = get_posts(array(
    'posts_per_page' => -1, // 全投稿を取得
    'category'       => 670, // 特定のカテゴリーID
));

// 投稿に紐付けられたタグを集める
$tag_ids = array();
foreach ($posts_in_category as $post) {
    $post_tags = wp_get_post_tags($post->ID);
    foreach ($post_tags as $post_tag) {
        if (!in_array($post_tag->term_id, $tag_ids)) {
            $tag_ids[] = $post_tag->term_id; // 重複しないようにタグIDを追加
        }
    }
}

// 特定のカテゴリーIDに属する投稿からタグを取得し、それらのタグに紐づく投稿数をカウント
$tag_counts = array();
foreach ($posts_in_category as $post) {
    $post_tags = wp_get_post_tags($post->ID);
    foreach ($post_tags as $post_tag) {
        if (!array_key_exists($post_tag->term_id, $tag_counts)) {
            $tag_counts[$post_tag->term_id] = 1; // 初めてこのタグIDが見つかった場合、カウントを1とする
        } else {
            $tag_counts[$post_tag->term_id] += 1; // 既にこのタグIDのカウントがある場合、カウントを増やす
        }
    }
}

// タグ情報を取得し、それぞれのタグに紐づくカテゴリーID455の投稿数を表示
if (!empty($tag_counts)) {
    $tags = get_tags(array(
        'orderby' => 'count',
        'order'   => 'DESC',
        'include' => array_keys($tag_counts), // 集めたタグIDのみを含める
    ));

    if ($tags) {
        foreach ($tags as $tag) {
            // タグ名とそのタグに紐づいているカテゴリーID455の投稿数を表示
            $tag_count = array_key_exists($tag->term_id, $tag_counts) ? $tag_counts[$tag->term_id] : 0;
            echo '<li><a href="' . esc_url(add_query_arg('from_cat', '670', get_tag_link($tag->term_id))) . '">' . esc_html($tag->name) . ' (' . $tag_count . ')</a></li>';
        }
    }
}
