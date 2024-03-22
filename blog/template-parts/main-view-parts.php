<?php
$thumbnail_url = get_the_post_thumbnail_url(get_the_ID(), 'full');

echo '<style>';
echo '.main-view {';

if (is_single()) {
    // 投稿ページでアイキャッチ画像があればそれを、なければデフォルト画像を表示
    if ($thumbnail_url) {
        echo '    background-image: url("' . esc_url($thumbnail_url) . '");';
    } else {
        echo '    background-image: url("' . esc_url(get_template_directory_uri()) . '/img/mainbg.jpg");';
    }
} elseif (is_category('408')) {
    // 特定のカテゴリーページの場合の画像
    echo '    background-image: linear-gradient(rgba(247, 242, 245, 0.5), rgba(247, 242, 245, 0.5)),url("' . esc_url(get_template_directory_uri()) . '/img/408-bg.jpg");';
} elseif (is_category('455')) {
    // 特定のカテゴリーページの場合の画像
    echo '    background-image: linear-gradient(rgba(247, 242, 245, 0.5), rgba(247, 242, 245, 0.5)),url("' . esc_url(get_template_directory_uri()) . '/img/tl-bg.jpg");';
} elseif (is_page(3039)) {
    // 固定ページID 3039 の場合の背景画像
    echo '    background-image: linear-gradient(rgba(247, 242, 245, 0.5), rgba(247, 242, 245, 0.5)),url("' . esc_url(get_template_directory_uri()) . '/img/tl-bg.jpg");';
} else {
    // それ以外の場合、トップページなどでデフォルトの画像を表示
    echo '    background-image: linear-gradient(rgba(247, 242, 245, 0.5), rgba(247, 242, 245, 0.5)), url("' . esc_url(get_template_directory_uri()) . '/img/mainbg.jpg");';
}

echo '}';
echo '</style>';
?>

<section id="main-view" class="main-view">
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
    } elseif (is_category('699')) { ?>
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
                                'terms' => 699,
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
    } elseif (is_category('670')) { ?>
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
                                'terms' => 670,
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
                        'category__in' => array(836, 408)
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