<?php
get_header();
?>

<body <?php body_class(); ?>>
    <?php wp_body_open(); ?>
    <?php get_template_part('template-parts/header-parts') ?>
    <?php get_template_part('template-parts/main-view-parts') ?>
    <section id="page-mens-contents" class="page-mens-contents">
        <div class="content">
            <div class="container content__container">
                <main id="category-main" class="category-main">
                    <div class="card">

                        <section id="ranking" class="ranking">
                            <div class="ranking-inner">
                                <div class="ranking-ttl">
                                    <h2 class="en">Ranking</h2>
                                    <h2 class="jp">ランキング</h2>
                                </div>
                                <ul class="blog-list">
                                    <?php
                                    $args = array(
                                        "category__in" => array(408), // カテゴリID408のみを含む
                                        "post_type"             => "post",
                                        "post_status"          => "publish",
                                        "posts_per_page"        => 5,
                                        "orderby"               => "post_views",
                                        "order"                 => "DESC"
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
                            </div>
                            <div class="all-post">
                                <a href="/mens-ranking/">ランキングをもっと見る</a>
                            </div>
                        </section>

                        <div class="new-post-ttl">
                            <h2 class="en">New post</h2>
                            <h2 class="jp">新着記事</h2>
                        </div>
                        <ul class="blog-list">
                            <?php
                            $args = array(
                                "category__in" => array(408),
                                'post_type'      => 'post',
                                'posts_per_page' => 5, // 1ページに表示する投稿数
                                'paged'          => max(1, get_query_var('paged')), // 現在のページ番号
                            );

                            $custom_query = new WP_Query($args);

                            if ($custom_query->have_posts()) : // ここを修正
                                while ($custom_query->have_posts()) : $custom_query->the_post(); // ここを修正
                            ?>
                                    <li>
                                        <div class="content-box">
                                            <a href="<?php the_permalink(); ?>">
                                                <div class="tumb">
                                                    <?php
                                                    if (has_post_thumbnail()) {
                                                        the_post_thumbnail();
                                                    } else {
                                                        // アイキャッチ画像が設定されていない場合、投稿内容から最初の画像URLを取得して表示
                                                        $first_image_url = get_first_image_url_from_post(get_the_content());
                                                        if ($first_image_url !== false) {
                                                            echo '<img src="' . esc_url($first_image_url) . '" alt="' . get_the_title() . '">';
                                                        }
                                                    }
                                                    ?>

                                                </div>
                                                <div class="detail-box">
                                                    <p class="text-content"><?php the_title(); ?></p>
                                                    <div class="state-box">
                                                        <p class="date"><?php the_date(); ?></p>
                                                        <p class="category">
                                                            <?php
                                                            $categories = get_the_category();
                                                            if ($categories) {
                                                                $count = 0;
                                                                foreach ($categories as $category) {
                                                                    echo '<a href="' . esc_url(get_category_link($category->term_id)) . '">' . esc_html($category->name) . '</a>';
                                                                    $count++;
                                                                    if ($count >= 2) {
                                                                        break;
                                                                    }
                                                                    echo ', ';
                                                                }
                                                            }
                                                            ?>
                                                        </p>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    </li>
                            <?php
                                endwhile;
                            else :
                                echo '<p>コンテンツなし</p>';
                            endif;
                            wp_reset_postdata(); // クエリのリセット
                            ?>
                        </ul>
                    </div>
                    <!-- ページネーションを表示 -->
                    <div class="all-post">
                        <a href="/?cat=408">男性向け新着記事をもっと見る</a>
                    </div>

                    <section id="category" class="category">
                        <div class="category-inner">
                            <div class="category-ttl">
                                <h2 class="en">Category</h2>
                                <h2 class="jp">人気のカテゴリ</h2>
                            </div>

                            <ul class="category-list">
                                <?php get_template_part('template-parts/category-list'); ?>
                            </ul>
                            <div id="tag" class="category-inner">
                                <div class="category-ttl">
                                    <h2 class="en">Tags</h2>
                                    <h2 class="jp">人気のタグ</h2>
                                </div>

                                <ul class="category-list">
                                    <?php get_template_part('template-parts/tag-list'); ?>
                                </ul>
                                <div class="all-categoly">
                                    <a href="/tags-list/">タグ一覧</a>
                                </div>
                            </div>
                    </section>
            </div>
            </main>
        </div>
        </div>
    </section>
    <?php get_template_part('template-parts/footer-parts'); ?>
    <?php get_footer(); ?>
</body>