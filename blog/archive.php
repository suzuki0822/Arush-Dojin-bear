<?php
// タグアーカイブページで、特定のカテゴリーIDからのリンクがある場合の条件分岐
if (is_tag() && isset($_GET['from_cat']) && $_GET['from_cat'] == '455') {
    $template_path = get_template_directory() . '/template-parts/tag-category-455.php';
    if (file_exists($template_path)) {
        include($template_path); // 特定のカテゴリーID用のテンプレートを読み込む
        exit; // さらなる処理を停止
    } else {
        // ファイルが存在しない場合のエラーハンドリングやフォールバック処理
        error_log('ファイルが存在しません: ' . $template_path);
        // 必要に応じて、デフォルトのタグアーカイブテンプレートを表示するか、エラーメッセージを表示する
    }
}
get_header();
?>

<body <?php body_class(); ?>>
    <?php wp_body_open(); ?>
    <?php get_template_part('template-parts/header-parts') ?>
    <div class="content">
        <div class="container content__container">
            <main id="category-main" class="category-main">
                <h1 class="category-title">タグ：<?php single_cat_title(); ?></h1>
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
                                                        $tags = get_the_tags();
                                                        if ($tags) {
                                                            $count = 0;
                                                            foreach ($tags as $tag) {
                                                                echo '<a href="' . esc_url(get_tag_link($tag->term_id)) . '">' . esc_html($tag->name) . '</a>';

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
            </main>
        </div>
    </div>
    <?php get_template_part('template-parts/footer-parts'); ?>
    <?php get_footer(); ?>
</body>