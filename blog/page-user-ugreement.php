<?php
get_header();
?>

<body <?php body_class(); ?>>
    <?php wp_body_open(); ?>
    <?php get_template_part('template-parts/header-parts') ?>
    <?php get_template_part('template-parts/main-view-parts') ?>
    <div id="primary" class="content-area">
        <main id="main" class="site-main">
            <div class="user-ugreement">
            <?php
                while (have_posts()) :
                    the_post();
                    the_title('<h1>', '</h1>');
                    the_content();
                endwhile;
                ?>
            </div>
        </main><!-- #main -->
    </div><!-- #primary -->
    <?php get_template_part('template-parts/footer-parts'); ?>
    <?php wp_footer(); ?>
