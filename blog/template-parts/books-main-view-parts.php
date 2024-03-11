<?php
$first_image_url = get_first_image_url_from_post(get_the_content());
?>
<style>
#books-main-view {
    background: no-repeat center/100% url('<?php echo esc_url($first_image_url); ?>');
    background-size: contain;
}

@media (max-width: 480px) {
    #books-main-view {
        background-size: cover;
        background-position: top;
    }
}
</style>
<section id="books-main-view" class="main-view" style="<?php echo $background_image_style; ?>">


    <?php if (is_single()) { ?>
        <div class="text-back">
            <h1><?php the_title(); ?></h1>
            <?php
            $excerpt = get_the_excerpt();  // 抜粋を取得
            ?>
            <p class="excerpt"><?php echo $excerpt; ?></p>
        </div>
    <?php
    } elseif (is_category('408')) { ?>
        <section id="recommend-post-contents" class="recommend-post-contents">
            <div class="recommend-post-contents-inner">
                <!-- <div class="recommend-post-ttl">
                    <h2 class="en">Popular post</h2>
                    <h2 class="jp">定番記事</h2>
                </div> -->
                <ul class="blog-list-slick">
                    <?php
                    $args = array(
                        'post_type' => 'post', // 投稿タイプを指定
                        'posts_per_page' => 13, // 表示する投稿数
                        'orderby' => 'rand', // 日付順にソート
                        'order' => 'DESC', // 新しい投稿を先に表示
                        'tax_query' => array(
                            'relation' => 'AND', // すべての条件を満たす投稿を取得
                            array(
                                'taxonomy' => 'category', // タクソノミーの種類
                                'field' => 'term_id', // IDでカテゴリを指定
                                'terms' => 836, // カテゴリID
                            ),
                            array(
                                'taxonomy' => 'category',
                                'field' => 'term_id',
                                'terms' => 408,
                            ),
                        ),
                    );
                    $the_query = new WP_Query($args);
                    $rank = 1;
                    if ($the_query->have_posts()) {
                        while ($the_query->have_posts()) {
                            $the_query->the_post();
                            set_query_var('rank', $rank);
                            get_template_part('template-parts/main-view-recommend-list');
                            $rank++;
                        }
                        wp_reset_postdata();
                    } else {
                        echo '<p>コンテンツなし</p>';
                    } ?>
                </ul>
            </div>
        </section>
    <?php
    } elseif (is_category('455')) { ?>
        <section id="recommend-post-contents" class="recommend-post-contents">
            <div class="recommend-post-contents-inner">
                <!-- <div class="recommend-post-ttl">
                    <h2 class="en">Popular post</h2>
                    <h2 class="jp">定番記事</h2>
                </div> -->
                <ul class="blog-list-slick">
                    <?php
                    $args = array(
                        'post_type' => 'post', // 投稿タイプを指定
                        'posts_per_page' => 13, // 表示する投稿数
                        'orderby' => 'rand', // 日付順にソート
                        'order' => 'DESC', // 新しい投稿を先に表示
                        'tax_query' => array(
                            'relation' => 'AND', // すべての条件を満たす投稿を取得
                            array(
                                'taxonomy' => 'category', // タクソノミーの種類
                                'field' => 'term_id', // IDでカテゴリを指定
                                'terms' => 836, // カテゴリID
                            ),
                            array(
                                'taxonomy' => 'category',
                                'field' => 'term_id',
                                'terms' => 455,
                            ),
                        ),
                    );
                    $the_query = new WP_Query($args);
                    $rank = 1;
                    if ($the_query->have_posts()) {
                        while ($the_query->have_posts()) {
                            $the_query->the_post();
                            set_query_var('rank', $rank);
                            get_template_part('template-parts/main-view-recommend-list');
                            $rank++;
                        }
                        wp_reset_postdata();
                    } else {
                        echo '<p>コンテンツなし</p>';
                    } ?>
                </ul>
            </div>
        </section>
    <?php } else { ?>

        <section id="recommend-post-contents" class="recommend-post-contents">
            <div class="recommend-post-contents-inner">
                <!-- <div class="recommend-post-ttl">
                    <h2 class="en">Popular post</h2>
                    <h2 class="jp">定番記事</h2>
                </div> -->
                <ul class="blog-list-slick">
                    <?php
                    $args = array(
                        'orderby' => 'rand', // 日付順に変更
                        'order' => 'DESC', // 新しい投稿から表示
                        'posts_per_page' => 13, // 表示する投稿数
                        'category__in' => array(836)
                    );
                    $the_query = new WP_Query($args);
                    $rank = 1;
                    if ($the_query->have_posts()) {
                        while ($the_query->have_posts()) {
                            $the_query->the_post();
                            set_query_var('rank', $rank);
                            get_template_part('template-parts/main-view-recommend-list');
                            $rank++;
                        }
                        wp_reset_postdata();
                    } else {
                        echo '<p>コンテンツなし</p>';
                    } ?>
                </ul>
            </div>
        </section>
    <?php } ?>
</section>