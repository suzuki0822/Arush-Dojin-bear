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
                <h1 class="category-title">カテゴリ：<?php single_cat_title(); ?></h1>
                <div class="card">
                    <ul class="blog-list">
                        <?php
                        $args = array(
                            'post_type'      => 'post',
                            'posts_per_page' => 6, // 1ページに表示する投稿数
                            'paged'          => max(1, get_query_var('paged')), // 現在のページ番号
                        );

                        $custom_query = new WP_Query($args);

                        if (have_posts()) {
                            while (have_posts()) {
                                the_post();
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
                <div class="pagination">
                    <?php
                    echo paginate_links(array(
                        'total'   => $wp_query->max_num_pages,
                        'current' => max(1, get_query_var('paged')),
                        'prev_text' => '&laquo; 前へ',
                        'next_text' => '次へ &raquo;',
                        'mid_size'  => 4,
                    ));
                    ?>
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
    <?php get_template_part('template-parts/footer-parts'); ?>
    <?php get_footer(); ?>
</body>