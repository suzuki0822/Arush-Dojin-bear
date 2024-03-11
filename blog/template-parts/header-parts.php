<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-PH432966" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
<header>
    <nav class="navbar">
        <div class="logo"><a href="<?php echo esc_attr(home_url('/')); ?>"><img src="<?php echo get_template_directory_uri(); ?>/img/logo.png" alt="logo Image">
            </a></div>
        <div class="sp-category-menu">
            <ul>
                <li class="mens-list-content"><?php
                $post_id = 14824;
                $permalink = get_permalink($post_id);
                echo '<a href="' . esc_url($permalink) . '">BL作品</a>';
                ?>
            </li>
                <li class="tlbl-list-content"><?php
                $post_id = 14820;
                $permalink = get_permalink($post_id);
                echo '<a href="' . esc_url($permalink) . '">TL作品</a>';
                ?></li>
            </ul>
        </div>
        <ul class="menu">
            <li><a href="/new-posts/" class="close-menu">総合新着記事</a></li>
            <li><li class="tlbl-list-content"><?php
                $post_id = 14820;
                $permalink = get_permalink($post_id);
                echo '<a href="' . esc_url($permalink) . '">TL作品</a>';
                ?></li>
            <li><?php
                $post_id = 14824;
                $permalink = get_permalink($post_id);
                echo '<a href="' . esc_url($permalink) . '">BL作品</a>';
                ?></li>
            <li><a href="/?cat=1291">成年コミック記事一覧</a></li>
            <li><a href="<?php echo esc_url(home_url('/#ranking')); ?>">総合ランキング</a></li>
            <li><a href="/?cat=408/#ranking">男性向けランキング</a></li>
            <li><a href="/?cat=455/#ranking">TL・BL漫画ランキング</a></li>
        </ul>
        <div class="search-bar">
            <?php get_search_form(); ?>
        </div>

        <div class="hamburger-menu" onclick="myFunction(this)">
            <div class="bar1"></div>
            <div class="bar2"></div>
            <div class="bar3"></div>
        </div>

        <div id="overlay" class="overlay">
            <ul class="sp-menu">
                <li><a href="/new-posts/" class="close-menu">総合新着記事</a></li>
                <li><a href="/?cat=408">男性向け記事一覧</a></li>
                <li><a href="/?cat=455">TL・BL記事一覧</a></li>
                <li><a href="/?cat=1291">成年コミック記事一覧</a></li>
                <li><a href="<?php echo esc_url(home_url('/#ranking')); ?>" class="close-menu">総合ランキング</a></li>
                <li><a href="/?cat=408/#ranking">男性向けランキング</a></li>
                <li><a href="/?cat=455/#ranking">TL・BL漫画ランキング</a></li>

            </ul>
        </div>
    </nav>
</header>