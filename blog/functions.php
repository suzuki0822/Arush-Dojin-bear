<?php

// スタイルとスクリプトの読み込み

function blog_scripts()
{
    $theme_version = wp_get_theme()->get('Version');
    $css_file_path = get_stylesheet_directory() . '/style.css';
    $js_file_path = get_template_directory() . '/assets/js/scripts.js';

    // CSSファイルの最終変更時刻を取得
    $css_version = filemtime($css_file_path) ? filemtime($css_file_path) : $theme_version;

    // JavaScriptファイルの最終変更時刻を取得
    $js_version = filemtime($js_file_path) ? filemtime($js_file_path) : $theme_version;

    wp_enqueue_style('blog-style', get_stylesheet_uri(), array(), $css_version, 'all');
    wp_enqueue_script('blog-script', get_template_directory_uri() . '/assets/js/scripts.js', array('jquery'), $js_version, true);
    wp_enqueue_script('infinite-scroll', get_template_directory_uri() . '/assets/js/infinite-scroll.js', array('jquery'), filemtime(get_template_directory() . '/assets/js/infinite-scroll.js'), true);

    wp_localize_script('infinite-scroll', 'ajax_params', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'noposts' => __('No more posts to load.', 'your-text-domain'),
    ));
}

add_action('wp_enqueue_scripts', 'blog_scripts');



/**
 * アイキャッチ画像の機能を有効化
 */

add_theme_support('post-thumbnails');

function custom_default_thumbnail($html, $post_id, $post_thumbnail_id, $size, $attr)
{
    if (empty($html)) {
        $default_image = 'http://blog.local/wp-content/uploads/2023/12/22471995_m.jpg'; // デフォルトの画像のURLを指定
        $html = '<img src="' . esc_url($default_image) . '" alt="No Thumbnail">';
    }
    return $html;
}
add_filter('post_thumbnail_html', 'custom_default_thumbnail', 10, 5);



// function load_slick_scripts()
// {
//     wp_enqueue_script('slick-js', 'https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js', array('jquery'), '1.8.1', true);
//     wp_enqueue_style('slick-css', 'https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css', array(), '1.8.1', 'all');
// }
// add_action('wp_enqueue_scripts', 'load_slick_scripts');

function add_slick_slider_scripts()
{
    wp_enqueue_style('slick-css', 'https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css');
    wp_enqueue_style('slick-theme-css', 'https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css');
    wp_enqueue_script('slick-js', 'https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js', array('jquery'), '', true);
}

add_action('wp_enqueue_scripts', 'add_slick_slider_scripts');


function blog_setup()
{
    add_theme_support('title_tag');
    add_theme_support('wp-block-styles');
    add_theme_support('align-wide');
    add_theme_support('editor-style');
    $editor_stylesheet_path = './assets/css/style-editor.css';
    add_editor_style($editor_stylesheet_path);
    add_theme_support(
        'editor-font-sizes',
        array(
            array(
                'name' => '小',
                'shortName' => 'S',
                'size' => '13',
                'slug' => 'small',
            ),
            array(
                'name' => '中',
                'shortName' => 'M',
                'size' => '16',
                'slug' => 'normal',
            ),
            array(
                'name' => '大',
                'shortName' => 'L',
                'size' => '36',
                'slug' => 'large',
            ),
        )
    );

    add_theme_support('responsive-embeds');
    add_theme_support('custom-line-height');
    add_theme_support('experimental-link-color');
    add_theme_support('custom-spacing');
    add_theme_support('custom-units');

    register_nav_menus(
        array(
            'primary' => 'メインナビゲーション'
        )
    );
}

add_action('after_setup_theme', 'blog_setup');


function blog_widgets_init()
{
    register_sidebar(
        array(
            'name' => 'サイドバー',
            'id' => 'main-sidebar',
            'discription' => 'サイドバーで表示する内容をウィジェットで指定',
            'before_widget' => '<section id="%1$s" class="widget %2$s"',
            'after_widget' => '</section>',
            'before_title' => '<h2 class="widget-title"',
            'after_title' => '</h2>'
        )
    );
}


add_action('widgets_init', 'blog_widgets_init');




function set_post_views($postID)
{
    $count_key = 'post_views_count';
    $count = get_post_meta($postID, $count_key, true);
    if ($count == '') {
        $count = 0;
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
    } else {
        $count++;
        update_post_meta($postID, $count_key, $count);
    }
}


function show_date_for_all_posts($query)
{
    if (is_home() && $query->is_main_query()) {
        $query->set('orderby', 'date');
        $query->set('order', 'DESC');
    }
}
add_action('pre_get_posts', 'show_date_for_all_posts');


// 検索結果の表示数を5件に制限する
function modify_search_posts_per_page($query)
{
    if ($query->is_search && !is_admin()) {
        $query->set('posts_per_page', 5);
    }
}
add_action('pre_get_posts', 'modify_search_posts_per_page');

add_action('init', function () {
    register_taxonomy('group', 'post', [
        'label' => 'サークル名',
        'hierarchical' => true,
        'show_in_rest' => true,
    ]);
});


add_action('init', function () {
    register_taxonomy('company', 'post', [
        'label' => '出版社',
        'hierarchical' => true,
        'show_in_rest' => true,
    ]);
});

add_filter('redirect_canonical', 'disable_redirect_canonical');
function disable_redirect_canonical($redirect_url)
{
    if (is_404()) {
        return false;
    }
    return $redirect_url;
}

function wpb_search_filter($query)
{
    if ($query->is_search && !is_admin()) {
        if (isset($_GET['category']) && $_GET['category'] != 'all') {
            $query->set('cat', $_GET['category']);
        }
    }
    return $query;
}
add_filter('pre_get_posts', 'wpb_search_filter');


add_filter('wpcf7_validate_text*', 'custom_text_validation_filter', 20, 2);
add_filter('wpcf7_validate_text', 'custom_text_validation_filter', 20, 2);

function custom_text_validation_filter($result, $tag)
{
    if ('your-name' == $tag->name || 'your-furigana' == $tag->name) {
        $value = isset($_POST[$tag->name]) ? trim(wp_unslash(str_replace("\n", "", $_POST[$tag->name]))) : '';
        if (!preg_match('/^[^\x01-\x7E\xA1-\xDF]+$/u', $value)) {
            $result->invalidate($tag, "「氏名とふりがな」は全角文字を使用してください。");
        }
    }

    return $result;
}


class Custom_Tag_Cloud_Widget extends WP_Widget
{

    public function __construct()
    {
        parent::__construct(
            'custom_tag_cloud_widget',
            'Custom Tag Cloud',
            array('description' => __('A custom tag cloud widget for specific category.', 'text_domain'),)
        );
    }

    public function widget($args, $instance)
    {
        $title = apply_filters('widget_title', $instance['title']);

        echo $args['before_widget'];
        if (!empty($title)) {
            echo $args['before_title'] . $title . $args['after_title'];
        }

        // カテゴリID455に属する投稿からタグを集める
        $posts_in_category = get_posts(array(
            'posts_per_page' => -1,
            'category'       => 455,
            'fields'         => 'ids', // Only get post IDs to improve performance
        ));

        $tag_ids = array();
        foreach ($posts_in_category as $post_id) {
            $post_tags = wp_get_post_tags($post_id);
            foreach ($post_tags as $post_tag) {
                $tag_ids[$post_tag->term_id] = $post_tag->term_id;
            }
        }

        if (!empty($tag_ids)) {
            $tags = get_tags(array(
                'orderby' => 'count',
                'order'   => 'DESC',
                'include' => $tag_ids,
            ));

            echo '<div class="tagcloud">';
            foreach ($tags as $tag) {
                $link = esc_url(add_query_arg('from_cat', '455', get_tag_link($tag->term_id)));
                echo '<a href="' . $link . '">' . $tag->name . '</a> ';
            }
            echo '</div>';
        }

        echo $args['after_widget'];
    }

    public function form($instance)
    {
        $title = isset($instance['title']) ? $instance['title'] : __('New title', 'text_domain');
?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
        </p>
    <?php
    }

    public function update($new_instance, $old_instance)
    {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';

        return $instance;
    }
}

function register_custom_tag_cloud_widget()
{
    register_widget('Custom_Tag_Cloud_Widget');
}
add_action('widgets_init', 'register_custom_tag_cloud_widget');




function embed_shortcode($atts)
{
    // ショートコード内で指定された属性を取得
    $url = isset($atts['url']) ? esc_url($atts['url']) : '';

    // ショートコードの処理や表示内容を定義
    $output = '<div class="embedded-content">' . esc_html($url) . '</div>';

    return $output;
}
add_shortcode('embed', 'embed_shortcode');




function fetch_viewed_posts()
{
    if (isset($_POST['posts']) && is_array($_POST['posts'])) {
        $viewed_posts = array_map('intval', $_POST['posts']);

        $args = array(
            'post__in' => $viewed_posts,
            'orderby' => 'post__in',
            'posts_per_page' => 10,
        );
        $query = new WP_Query($args);

        if ($query->have_posts()) {
            echo '<ul>'; // リストの開始
            while ($query->have_posts()) {
                $query->the_post();
                // アイキャッチ画像の表示（存在する場合）
                if (has_post_thumbnail()) {
                    echo '<li><a href="' . get_permalink() . '">';
                    the_post_thumbnail('thumbnail'); // アイキャッチ画像をサムネイルサイズで表示
                    echo '<span>' . get_the_title() . '</span></a></li>';
                } else {
                    // アイキャッチ画像がない場合の処理
                    $first_image_url = get_first_image_url_from_post(get_the_content());
                    if ($first_image_url !== false) {
                        echo '<li><a href="' . get_permalink() . '">';
                        echo '<img width="150" height="150" src="' . esc_url($first_image_url) . '" alt="' . get_the_title() . '">';
                        echo '<span>' . get_the_title() . '</span></a></li>';
                    }
                }
            }
            echo '</ul>'; // リストの終了
        } else {
            echo '閲覧した記事はありません。';
        }

        wp_reset_postdata();
    }
    wp_die();
}

add_action('wp_ajax_fetch_viewed_posts', 'fetch_viewed_posts'); // ログインしているユーザー用
add_action('wp_ajax_nopriv_fetch_viewed_posts', 'fetch_viewed_posts'); // ログインしていないユーザー用



function theme_scripts()
{
    wp_enqueue_script('theme-script', get_template_directory_uri() . '/assets/js/scripts.js', array('jquery'), null, true);

    // PHPからJavaScriptへデータを渡す
    wp_localize_script('theme-script', 'theme_script_vars', array(
        'ajaxurl' => admin_url('admin-ajax.php')
    ));
}
add_action('wp_enqueue_scripts', 'theme_scripts');



function filter_posts_by_category_in_tag_archive($query)
{
    // 管理画面のクエリやメインクエリ以外は対象外
    if (is_admin() || !$query->is_main_query()) {
        return;
    }

    // タグアーカイブページで、かつURLに特定のクエリパラメータがある場合のみ処理
    if ($query->is_tag() && isset($_GET['from_cat']) && $_GET['from_cat'] == '408') {
        // カテゴリーID 408に属する投稿のみをクエリに含める
        $query->set('cat', '408');
    }
}
add_action('pre_get_posts', 'filter_posts_by_category_in_tag_archive');

function add_custom_js()
{
    ?>
    <script>
        jQuery(document).ready(function($) {
            // URLから_xuidの値を取得してクッキーに保存する関数
            function setXuidCookie() {
                var xuid = new URLSearchParams(window.location.search).get('_xuid');
                if (xuid) {
                    // クッキーに_xuidを保存（有効期限を1年とする例）
                    var d = new Date();
                    d.setTime(d.getTime() + (30 * 24 * 60 * 60));
                    var expires = "expires=" + d.toUTCString();
                    document.cookie = "_xuid=" + xuid + ";" + expires + ";path=/";
                }
            }

            // クッキーから_xuidの値を取得する関数
            function getXuidFromCookie() {
                var name = "_xuid=";
                var decodedCookie = decodeURIComponent(document.cookie);
                var ca = decodedCookie.split(';');
                for (var i = 0; i < ca.length; i++) {
                    var c = ca[i];
                    while (c.charAt(0) == ' ') {
                        c = c.substring(1);
                    }
                    if (c.indexOf(name) == 0) {
                        return c.substring(name.length, c.length);
                    }
                }
                return "";
            }

            // クッキーに_xuidが保存されている場合、すべてのリンクにその値を付与する
            function appendXuidToLinks() {
                var xuid = getXuidFromCookie();
                if (xuid) {
                    $('a').each(function() {
                        var href = $(this).attr('href');
                        if (href && href.indexOf('javascript:') !== 0) { // JavaScriptリンクは除外
                            var delimiter = href.indexOf('?') !== -1 ? '&' : '?';
                            $(this).attr('href', href + delimiter + '_xuid=' + xuid);
                        }
                    });
                }
            }

            // 関数の実行
            setXuidCookie();
            appendXuidToLinks();
        });
    </script>
    <?php
}
add_action('wp_footer', 'add_custom_js');



function get_first_image_url_from_post($content)
{
    preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $content, $matches);
    if (!empty($matches[1])) {
        // 最初の画像URLを返す
        return $matches[1][0];
    }
    return false;
}


function more_post_ajax()
{
    $ppp = (isset($_POST["ppp"])) ? $_POST["ppp"] : 6;
    $page = (isset($_POST['pageNumber'])) ? $_POST['pageNumber'] : 0;

    header("Content-Type: text/html");

    $args = array(
        'post_type' => 'post',
        'posts_per_page' => $ppp,
        'paged' => $page,
    );

    $loop = new WP_Query($args);

    if ($loop->have_posts()) : while ($loop->have_posts()) : $loop->the_post();
    ?>
            <li>
                <div class="content-box">
                    <a href="<?php the_permalink(); ?>">
                        <div class="thumb">
                            <?php
                            if (has_post_thumbnail()) {
                                the_post_thumbnail();
                            } else {
                                // アイキャッチ画像がない場合の処理
                                echo '<img src="' . get_template_directory_uri() . '/path/to/default-image.png" alt="' . get_the_title() . '">';
                            }
                            ?>
                        </div>
                        <div class="detail-box">
                            <p class="text-content"><?php the_title(); ?></p>
                            <div class="state-box">
                                <p class="date"><?php echo get_the_date(); ?></p>
                                <p class="category">
                                    <?php
                                    $categories = get_the_category();
                                    if ($categories) {
                                        $separator = ', ';
                                        $output = '';
                                        foreach ($categories as $category) {
                                            $output .= '<a href="' . esc_url(get_category_link($category->term_id)) . '">' . esc_html($category->name) . '</a>' . $separator;
                                        }
                                        echo trim($output, $separator);
                                    }
                                    ?>
                                </p>
                            </div>
                        </div>
                    </a>
                </div>
            </li>
<?php
        endwhile;
    endif;
    wp_reset_postdata();
    die();
}
add_action('wp_ajax_nopriv_more_post_ajax', 'more_post_ajax');
add_action('wp_ajax_more_post_ajax', 'more_post_ajax');
