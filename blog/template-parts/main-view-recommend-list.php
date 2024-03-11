<li>
    <div class="content-box">
        <a href="<?php the_permalink(); ?>">
            <div class="tumb">
                <?php the_post_thumbnail(); ?>
                <div class="rank-number">
                    <p><?php echo get_query_var('rank'); ?></p>
                </div>
            </div>
            <div class="detail-box">
                <div class="recommend-detail-box-wrapper">
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
            </div>
        </a>
    </div>
</li>