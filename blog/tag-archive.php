<?php
/*
 * Template Name: Tag Archive Template
 * Template Post Type: page
 */
get_header();
?>


<body <?php body_class(); ?>>
    <?php wp_body_open(); ?>

    <?php get_template_part('template-parts/header-parts') ?>

    <?php get_template_part('template-parts/main-view-parts') ?>


    <div class="wrapper">

        <section id="category" class="category">

            <div id="tag" class="category-inner">
                <div class="category-ttl">
                    <?php
                    while (have_posts()) :
                        the_post();
                        the_title('<h1>', '</h1>');
                        the_content();
                    endwhile;
                    ?>
                </div>
            </div>
        </section>
    </div>
    <?php get_template_part('template-parts/footer-parts'); ?>


    <?php wp_footer(); ?>