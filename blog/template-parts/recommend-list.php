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
                <div class="rank-number">
                    <p><?php echo get_query_var('rank'); ?></p>
                </div>
            </div>
            <div class="detail-box">
                <p class="text-content"><?php the_title(); ?></p>
                <?php $terms = get_the_terms(get_the_ID(), 'group'); ?>
                <?php if ($terms && !is_wp_error($terms)) : ?>
                    <div class="content-group">
                        <?php foreach ($terms as $term) : ?>
                            <p><a href="<?php echo get_term_link($term); ?>"><?php echo esc_html($term->name); ?></a></p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                <div class="state-box">
                    <p class="category">
                        <?php the_category(', '); ?>
                    </p>
                </div>
            </div>
        </a>
    </div>
</li>