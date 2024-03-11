<?php
if (is_active_sidebar('main-sidebar')) : ?>
    <aside id="site-aside" class="site-aside">
        <div class="widget-column main-sidebar">
            <!-- <ins class="dmm-widget-placement" data-id="390125dc3958fb945aea1069196ccea5" style="background:transparent"></ins>
            <script src="https://widget-view.dmm.co.jp/js/placement.js" class="dmm-widget-scripts" data-id="390125dc3958fb945aea1069196ccea5"></script> -->
            <?php dynamic_sidebar('main-sidebar'); ?>
        </div>


        <div sidebar-tag-list>
            <h2>おすすめのタグ</h2>
            <ul class="category-list">
                <?php
                if (is_single() && has_category(455)) {
                    $query_args = array(
                        'category'       => 455,
                        'posts_per_page' => -1,
                        'fields'         => 'ids',
                    );

                    $post_ids = get_posts($query_args);
                    $tag_ids = array();

                    foreach ($post_ids as $post_id) {
                        $post_tags = wp_get_post_tags($post_id);
                        foreach ($post_tags as $post_tag) {
                            if (!array_key_exists($post_tag->term_id, $tag_ids)) {
                                $tag_ids[$post_tag->term_id] = $post_tag; // タグの重複を避ける
                            }
                        }
                    }

                    // タグの重複を除外した後、30件のタグだけを取得して表示
                    if (!empty($tag_ids)) {
                        $tag_ids = array_slice($tag_ids, 0, 30, true);
                        foreach ($tag_ids as $tag_id => $tag) {
                            $get_link = add_query_arg('from_cat', '455', get_tag_link($tag_id));
                            echo '<li><a href="' . esc_url($get_link) . '">' . esc_html($tag->name) . '</a></li>';
                        }
                    }
                } else {
                    // カテゴリーID 455を持たないページでは全てのタグを表示（最大30件）
                    $tags = get_tags(array(
                        'orderby' => 'count',
                        'order'   => 'DESC',
                        'number'  => 30, // 最大30件のタグを取得
                    ));
                    function is_in_category_408()
                    {
                        // 個別の投稿ページであるかどうかを確認
                        if (is_single()) {
                            $categories = get_the_category();
                            foreach ($categories as $category) {
                                if ($category->term_id == 408) {
                                    return true; // 投稿がカテゴリーID 408に属している
                                }
                            }
                        }
                        return false; // その他の条件
                    }

                    if ($tags) {
                        foreach ($tags as $tag) {
                            // カテゴリーIDが408のページにいる場合に`from_cat=408`を追加
                            $link = is_in_category_408() ? esc_url(add_query_arg('from_cat', '408', get_tag_link($tag->term_id))) : esc_url(get_tag_link($tag->term_id));
                            echo '<li><a href="' . $link . '">' . esc_html($tag->name) . '</a></li>';
                        }
                    }
                }
                ?>
            </ul>
        </div>
        <div class="popular_search">
            <?php
            sm_list_popular_searches('


                <h2 class="popular_search_ttl">人気の検索ワード</h2>
                    ', '


            ', 10) ?>
        </div>


        <div class="populer-list">
            <h2>人気の記事ランキング</h2>
            <?php
            $categories = get_the_category();
            $has_cat_455 = false; // フラグを初期化

            // すべてのカテゴリをループして、ID 455を持つかどうかをチェック
            foreach ($categories as $category) {
                if ($category->cat_ID == 455) {
                    $has_cat_455 = true;
                    break; // カテゴリID 455が見つかった場合、ループを抜ける
                }
            }

            // 結果に基づいて処理を分岐
            if ($has_cat_455) {
                // カテゴリID 455を持つ場合の処理
                // 例: 特定のカテゴリに基づいて人気の投稿を表示
                wpp_get_mostpopular("range=last30days&limit=5&cat=455&thumbnail_width=500&thumbnail_height=500");
            } else {
                // カテゴリID 455を持たない場合の処理
                // 例: カテゴリID 455を除いた人気の投稿を表示
                wpp_get_mostpopular("range=last30days&limit=5&taxonomy=category&term_id=-455&thumbnail_width=500&thumbnail_height=500");
            }
            ?>
        </div>
        <?php
        $categories = get_the_category();
        $has_cat_455 = false; // フラグを初期化

        // すべてのカテゴリをループして、ID 455を持つかどうかをチェック
        foreach ($categories as $category) {
            if ($category->cat_ID == 455) {
                $has_cat_455 = true;
                break; // カテゴリID 455が見つかった場合、ループを抜ける
            }
        }

        // 結果に基づいて処理を分岐
        if ($has_cat_455) { ?>
            <script type="text/javascript">
                blogparts = {
                    "base": "https://www.dlsite.com/",
                    "type": "ranking",
                    "site": "girls",
                    "query": {
                        "period": "week"
                    },
                    "title": "ランキング",
                    "display": "vertical",
                    "detail": "1",
                    "column": "h",
                    "image": "small",
                    "count": "3",
                    "wrapper": "1",
                    "autorotate": true,
                    "aid": "dojinbear"
                }
            </script>
            <script type="text/javascript" src="https://www.dlsite.com/js/blogparts.js" charset="UTF-8"></script>
            <ins class="dmm-widget-placement" data-id="96df19c62f89f934bb62b37216efab9d" style="background:transparent"></ins>
            <script src="https://widget-view.dmm.co.jp/js/placement.js" class="dmm-widget-scripts" data-id="96df19c62f89f934bb62b37216efab9d"></script>
        <?php ;
        } else { ?>
            <script type="text/javascript">
                blogparts = {
                    "base": "https://www.dlsite.com/",
                    "type": "ranking",
                    "site": "maniax",
                    "query": {
                        "period": "week"
                    },
                    "title": "ランキング",
                    "display": "vertical",
                    "detail": "1",
                    "column": "h",
                    "image": "small",
                    "count": "3",
                    "wrapper": "1",
                    "autorotate": true,
                    "aid": "dojinbear"
                }
            </script>
            <script type="text/javascript" src="https://www.dlsite.com/js/blogparts.js" charset="UTF-8"></script>
            <ins class="dmm-widget-placement" data-id="5e53bc0eff6d8219f07fdd10ab30d8f8" style="background:transparent"></ins>
            <script src="https://widget-view.dmm.co.jp/js/placement.js" class="dmm-widget-scripts" data-id="5e53bc0eff6d8219f07fdd10ab30d8f8"></script>
        <?php ;
        } ?>
    </aside>
<?php endif; ?>