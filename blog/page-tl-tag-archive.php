<?php
get_header();
?>

<body <?php body_class(); ?>>
    <?php wp_body_open(); ?>
    <?php get_template_part('template-parts/header-parts') ?>
    <?php get_template_part('template-parts/main-view-parts') ?>
    <div id="primary" class="content-area">
        <div id="page-tlbl-tag-archive" class="page-tlbl-tag-archive">
            <ul class="category-list">
                <?php get_template_part('template-parts/tag-list-tl-all'); ?>
            </ul>
        </div>
    </div><!-- #primary -->
    <?php get_template_part('template-parts/footer-parts'); ?>
    <?php wp_footer(); ?>