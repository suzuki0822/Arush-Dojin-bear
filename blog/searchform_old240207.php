<?php

/**
 * Template for displaying search forms in your theme.
 */
?>

<form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">
    <label>
        <span class="screen-reader-text"><?php echo _x('Search for:', 'label'); ?></span>
        <input type="search" class="search-field" placeholder="<?php echo esc_attr_x('Search &hellip;', 'placeholder'); ?>" value="<?php echo get_search_query(); ?>" name="s" />
    </label>
    <!-- <?php
    // $args = array(
    //     'show_option_all' => __('All Categories'), // ドロップダウンに表示されるデフォルトの項目のテキスト
    //     'orderby' => 'name',
    //     'order' => 'ASC',
    //     'name' => 'category', // ドロップダウンのselectタグに設定されるname属性
    //     'class' => 'postform', // ドロップダウンのselectタグに設定されるclass属性
    //     'value_field' => 'slug', // オプションのvalue属性に設定される値
    //     'hierarchical' => true, // カテゴリーの階層構造を保持するかどうか
    // );
    // wp_dropdown_categories($args);
    ?> -->
    <button type="submit" class="search-submit"><?php echo _x('Search', 'submit button'); ?></button>
</form>