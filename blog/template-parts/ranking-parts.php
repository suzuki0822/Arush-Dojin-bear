<ul class="blog-list-slick">
    <?php
    $args = array(
        'meta_key' => 'post_views_count',
        'orderby' => 'meta_value_num',
        'order' => 'DESC',
        'posts_per_page' => 5
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
    } ?>
</ul>