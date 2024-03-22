<?php
get_header();
?>

<body <?php body_class(); ?>>
    <?php wp_body_open(); ?>
    <?php get_template_part('template-parts/header-parts') ?>
    <div class="content">
        <div class="container content__container">
            <main id="category-main" class="category-main">
                <!-- タイトルにタグ名を動的に挿入 -->
                <h1 class="category-title">タグ：<?php single_tag_title(); ?></h1>
                <div class="card">
                    <ul class="blog-list">
                        <?php
                        // タグのスラッグを取得
                        $tag_slug = get_query_var('tag');

                        // カスタムクエリの引数を設定
                        $args = array(
                            'post_type'      => 'post',
                            'posts_per_page' => 6, // 1ページに表示する投稿数
                            'paged'          => max(1, get_query_var('paged')), // 現在のページ番号
                            'category__in'   => array(455), // 特定のカテゴリーID
                            'tag'            => $tag_slug, // 特定のタグスラッグ
                        );

                        // カスタムクエリを実行
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
                                                        $tags = get_the_tags();
                                                        if ($tags) {
                                                            $count = 0;
                                                            foreach ($tags as $tag) {
                                                                if ($count > 0) {
                                                                    echo ', ';
                                                                }
                                                                echo '<a href="' . esc_url(get_tag_link($tag->term_id)) . '">' . esc_html($tag->name) . '</a>';
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
                            // 投稿データのリセット
                            wp_reset_postdata();
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
                        'total'   => $custom_query->max_num_pages, // カスタムクエリの最大ページ数
                        'current' => max(1, get_query_var('paged')),
                        'prev_text' => '&laquo; 前へ',
                        'next_text' => '次へ &raquo;',
                        'mid_size'  => 4,
                    ));
                    ?>
                </div>
            </main>
        </div>
    </div>
    <?php get_template_part('template-parts/footer-parts'); ?>
    <?php get_footer(); ?>
</body>