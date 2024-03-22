<?php
get_header();
?>

<body <?php body_class(); ?>>
    <?php wp_body_open(); ?>
    <?php get_template_part('template-parts/header-parts') ?>
    <?php get_template_part('template-parts/main-view-parts') ?>
    <div class="content">
        <div class="container content__container">
            <main id="category-main" class="category-main">
                <div class="search-bar-bottom">
                    <?php get_search_form(); ?>
                </div>

                <h1 class="category-title"><?php single_cat_title(); ?>エロ漫画</h1>

                <div class="card">

                    <div class="new-post-ttl">
                        <h2 class="en">New post</h2>
                        <h2 class="jp">新着記事</h2>
                    </div>
                    <ul class="blog-list">
                        <?php
                        $args = array(
                            'post_type'      => 'post',
                            'posts_per_page' => 12, // 1ページに表示する投稿数
                            'paged'          => max(1, get_query_var('paged')), // 現在のページ番号
                        );

                        $custom_query = new WP_Query($args);

                        if ($custom_query->have_posts()) {
                            while ($custom_query->have_posts()) {
                                $custom_query->the_post();
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
                            }
                        } else {
                            echo '<p>コンテンツなし</p>';
                        }
                        ?>
                    </ul>
                </div>
                <!-- ページネーションを表示 -->



        </div>
        </main>
    </div>
    </div>

    <?php get_template_part('template-parts/footer-parts'); ?>
    <?php get_footer(); ?>
</body>