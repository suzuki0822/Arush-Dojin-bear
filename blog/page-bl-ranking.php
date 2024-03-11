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
                    <h1 class="category-title"><?php the_title(); ?></h1>
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
                                        "category__in" => array(670), // カテゴリID408のみを含む
                                        "post_type"             => "post",
                                        "post_status"          => "publish",
                                        "posts_per_page"        => 20,
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
                            <div class="pagination">
                                <?php
                                echo paginate_links(array(
                                    'total'   => $wp_query->max_num_pages,
                                    'current' => max(1, get_query_var('paged')),
                                    'prev_text' => '&laquo; 前へ',
                                    'next_text' => '次へ &raquo;',
                                    'mid_size'  => 1,
                                ));
                                ?>
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