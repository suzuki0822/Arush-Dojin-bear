<?php
// タクソノミーの項目を取得
$args = array(
    'taxonomy'   => 'actor', // ここにカスタムタクソノミーのスラッグを入れる
    'orderby'    => 'count', // 投稿数による並び替え
    'order'      => 'DESC',  // 降順で表示
    'number'     => 10,      // 表示する項目数
    'hide_empty' => true,    // 投稿が登録されていないタームは非表示
);

$terms = get_terms($args);

if (!empty($terms) && !is_wp_error($terms)) {

    foreach ($terms as $term) {
        $term_link = get_term_link($term); // タームのリンクを取得
        if (!is_wp_error($term_link)) {
            echo '<li><a href="' . esc_url($term_link) . '">' . esc_html($term->name) . ' (' . $term->count . ')</a></li>';
        }
    }

} else {
    echo '<p>該当するタクソノミーがありません。</p>';
}
?>