<ul class="blog-list-slick">
    <?php
    // 現在のカテゴリーIDを取得
    $current_category_id = get_queried_object_id();

    $args = array(
        'posts_per_page' => 10, // 表示したいランキングの数
        'post_type' => 'post', // または 'page', 'custom_post_type'
        'meta_key' => 'post_views_count', // Post Views Counter が使用するメタキー
        'orderby' => 'meta_value_num',
        'order' => 'DESC',
        'category__in' => array($current_category_id), // 現在のカテゴリーに絞り込む
        'ignore_sticky_posts' => true
    );
    $the_query = new WP_Query($args);
    $rank = 1;
    if ($the_query->have_posts()) {
        while ($the_query->have_posts()) {
            $the_query->the_post();
            set_query_var('rank', $rank);
            get_template_part('template-parts/ranking');
            $rank++;
        }
        wp_reset_postdata();
    } else {
        echo '<p>コンテンツなし</p>';
    }
    ?>
</ul>



