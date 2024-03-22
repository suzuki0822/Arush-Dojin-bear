<?php get_header(); ?>

<body <?php body_class(); ?>>
    <?php wp_body_open(); ?>
    <?php get_template_part('template-parts/header-parts') ?>

    <div class="wrapper">
        <section id="search-archive" class="search-archive">
            <div class="search-bar-bottom">
                <?php get_search_form(); ?>
            </div>
            <div class="search-inner">
                <?php if (have_posts()) : ?>
                    <?php
                    if (isset($_GET['s']) && empty($_GET['s'])) {
                        echo '<h2>検索キーワード未入力</h2>';
                    } else {
                        echo '<h2 class="search-result-tlt">' . $_GET['s'] . 'の検索結果：</h2><p class="search-result-text">' . $wp_query->found_posts . '件</p>';
                    }
                    ?>
                    <ul>
                        <?php if ($_GET['s']) : ?>
                            <?php while (have_posts()) : the_post(); ?>
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
                            <?php endwhile; ?>
                        <?php endif; ?>
                    </ul>
                <?php else : ?>
                    検索されたキーワードにマッチする記事はありませんでした
                <?php endif; ?>
            </div>
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
            <!-- サイドバーウィジェットを呼び出す -->
            <div class="sidebar">
                <?php get_sidebar(); ?>
            </div>
        </section>
    </div>

    <?php get_template_part('template-parts/footer-parts'); ?>
    <?php wp_footer(); ?>
</body>