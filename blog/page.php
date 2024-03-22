<?php
get_header();
?>

<body <?php body_class(); ?>>
    <?php wp_body_open(); ?>
    <?php get_template_part('template-parts/header-parts') ?>
    <?php get_template_part('template-parts/main-view-parts') ?>
    <div id="primary" class="content-area">
        <main id="main" class="site-main">
            <?php
            while (have_posts()) :
                the_post();
                the_title('<h1>', '</h1>');
                the_content();
            endwhile;
            ?>
            <ul class="blog-list">
                <?php
                $args = array(
                    'post_type'      => 'post',
                    'posts_per_page' => 6, // 1ページに表示する投稿数
                    'paged'          => max(1, get_query_var('paged')), // 現在のページ番号
                );

                $custom_query = new WP_Query($args);

                if ($custom_query->have_posts()) {
                    while ($custom_query->have_posts()) {
                        $custom_query->the_post();
                        get_template_part('template-parts/content');
                    }
                    wp_reset_postdata(); // クエリのリセット
                } else {
                    echo '<p>コンテンツなし</p>';
                }
                ?>
            </ul>
            <!-- ページネーションの表示 -->
            <div class="pagination">
                <?php
                // ページネーションの前にカスタムクエリを定義し、ループを開始
                $args = array(
                    'post_type'      => 'post',
                    'posts_per_page' => 6, // 1ページに表示する投稿数
                    'paged'          => max(1, get_query_var('paged')), // 現在のページ番号
                );

                $custom_query = new WP_Query($args);

                if ($custom_query->have_posts()) {
                    while ($custom_query->have_posts()) {
                        $custom_query->the_post();
                        // 投稿の表示など
                    }
                    // ページネーションの表示
                    echo paginate_links(array(
                        'total'   => $custom_query->max_num_pages,
                        'current' => max(1, get_query_var('paged')),
                        'prev_text' => '&laquo; 前へ',
                        'next_text' => '次へ &raquo;',
                        'mid_size'  => 6,
                    ));
                } else {
                    echo '<p>コンテンツなし</p>';
                }
                wp_reset_postdata(); // カスタムクエリの後にリセット
                ?>

            </div>
        </main><!-- #main -->
    </div><!-- #primary -->
    <?php get_template_part('template-parts/footer-parts'); ?>
    <?php wp_footer(); ?>