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
                <h1 class="category-title"><?php the_title(); ?></h1>

                <div class="card">

                    <div class="new-post-ttl">
                        <h2 class="en">New post</h2>
                        <h2 class="jp">新着記事</h2>
                    </div>
                    <ul class="blog-list">
                        <?php
                        $args = array(
                            'post_type'      => 'post',
                            'posts_per_page' => 6, // 1ページに表示する投稿数
                            'paged'          => max(1, get_query_var('paged')), // 現在のページ番号
                            'category__in' => array(455),
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
                                                <?php the_post_thumbnail(); ?>
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
                                                                if ($count > 0) {
                                                                    echo ', ';
                                                                }
                                                                echo '<a href="' . esc_url(get_category_link($category->term_id)) . '">' . esc_html($category->name) . '</a>';
                                                                $count++;
                                                                if ($count >= 2) {
                                                                    break;
                                                                }
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
                        // カスタムクエリの後処理
                        wp_reset_postdata();
                        ?>

                    </ul>
                </div>


                <div class="pagination">
                    <?php
                    echo paginate_links(array(
                        'total'   => $custom_query->max_num_pages, // カスタムクエリの最大ページ数
                        'current' => max(1, get_query_var('paged')), // 現在のページ番号
                        'prev_text' => '&laquo; 前へ',
                        'next_text' => '次へ &raquo;',
                        'mid_size'  => 6, // 現在のページの前後に表示するページ数
                        'base'      => str_replace(999999999, '%#%', esc_url(get_pagenum_link(999999999))),
                        'format'    => '?paged=%#%', // ページ番号を表示するためのフォーマット
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
                                <?php get_template_part('template-parts/tag-list-tl'); ?>
                            </ul>
                            <div class="all-categoly">
                                <?php
                                $post_id = 3039;
                                $permalink = get_permalink($post_id);
                                echo '<a href="' . esc_url($permalink) . '">TL・BL作品タグ一覧</a>';
                                ?>
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