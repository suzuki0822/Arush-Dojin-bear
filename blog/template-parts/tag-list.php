<?php

$tags = get_tags(array(
    'orderby' => 'count', 
    'order'   => 'DESC', 
    'number'  => 12
));

if ($tags) {
    foreach ($tags as $tag) {
        // カテゴリーID 408のアーカイブページの場合のみクエリパラメータを追加
        $link = is_category(408) ? esc_url(add_query_arg('from_cat', '408', get_tag_link($tag->term_id))) : esc_url(get_tag_link($tag->term_id));
        echo '<li><a href="' . $link . '">' . esc_html($tag->name) . '</a></li>';
    }
}
?>
