<li>
    <div class="content-box">
        <a href="<?php the_permalink(); ?>" class="post-link">

            <div class="tumb">
                <?php
                if (has_post_thumbnail()) {
                    the_post_thumbnail();
                    $attachments = get_children(array(
                        'post_parent' => $post->ID,
                        'post_type' => 'attachment',
                        'post_mime_type' => 'image',
                        'orderby' => 'menu_order',
                        'order' => 'ASC',
                        'offset' => 1
                    ));
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

                <?php
                $terms = get_the_terms(get_the_ID(), 'group');
                if ($terms && !is_wp_error($terms)) {
                ?>
                    <div class="content-group">
                        <?php foreach ($terms as $term) : ?>
                            <p><a href="<?php echo get_term_link($term); ?>"><?php echo esc_html($term->name); ?> </a></p>
                        <?php endforeach; ?>
                    </div>
                <?php
                }
                ?>

                <div class="state-box">
                    <p class="date"><?php echo get_the_date('Y年n月j日'); ?></p>
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