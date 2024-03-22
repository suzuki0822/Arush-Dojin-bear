<?php get_header(); ?>



<body <?php body_class(); ?>>
    <?php wp_body_open(); ?>

    <?php get_template_part('template-parts/header-parts') ?>

    <?php
    // 現在の投稿がカテゴリID 1271 を持っているかチェック
    if (has_category(1291)) {
        // カテゴリID 1271 を持つ場合のテンプレートパーツ
        get_template_part('template-parts/books-main-view-parts');
    } else {
        // カテゴリID 1271 を持たない場合のテンプレートパーツ
        get_template_part('template-parts/main-view-parts');
    }
    ?>

    <main id="single-main" class="single-main">
        <?php if (function_exists('aioseo_breadcrumbs')) aioseo_breadcrumbs(); ?>

        <section id="single">
            <?php
            if (have_posts()) {
                while (have_posts()) {
                    the_post();
                    set_post_views(get_the_ID());
                    if (has_category(1291)) {
                        // 特定のカテゴリのテンプレートパーツを読み込む
                        get_template_part('template-parts/books-post-parts');
                    } else {
                        // 標準のテンプレートパーツを読み込む
                        get_template_part('template-parts/post-parts');
                    }
                }
            } else {
                echo '<p>コンテンツがありません。</p>';
            }
            ?>


            <?php get_sidebar(); ?>


        </section>
        <?php get_template_part('template-parts/footer-parts'); ?>


        <?php wp_footer(); ?>