<footer>

    <nav class="footer-navbar">
        <a href="<?php echo esc_attr(home_url('/')); ?>">
            <div class="footer-logo"><img src="<?php echo get_template_directory_uri(); ?>/img/logo.png" alt="logo Image">
            </div>
        </a>

        <ul class="menu">
            <li><a href="/new-posts/" class="close-menu">総合新着記事</a></li>
            <li><a href="/?cat=408">男性向け記事一覧</a></li>
            <li><a href="/?cat=455">TL・BL記事一覧</a></li>
            <li><a href="/?cat=1291">成年コミック記事一覧</a></li>
            <li><a href="<?php echo esc_url(home_url('/#ranking')); ?>">総合ランキング</a></li>
            <li><a href="/?cat=408/#ranking">男性向けランキング</a></li>
            <li><a href="/?cat=455/#ranking">TL・BL漫画ランキング</a></li>
            <li><?php
                $post_id = 589;
                $permalink = get_permalink($post_id);
                echo '<a href="' . esc_url($permalink) . '">プライバシーポリシー</a>';
                ?>
            </li>
            <li><?php
                $post_id = 584;
                $permalink = get_permalink($post_id);
                echo '<a href="' . esc_url($permalink) . '">利用規約</a>';
                ?>
            </li>
            <li><?php
                $post_id = 576;
                $permalink = get_permalink($post_id);
                echo '<a href="' . esc_url($permalink) . '">運営者情報</a>';
                ?>
            </li>
            <li><a href="/contact/">お問い合わせ</a>
            </li>
        </ul>

        <div class="back-top">
            <a href="#" id="scrollToTop"><img src="<?php echo get_template_directory_uri(); ?>/img/arrow-top.png">TOP</a>
        </div>


        <ul class="sp-foot-menu">
            <li><a href="/new-posts/" class="close-menu">総合新着記事</a></li>
            <li><a href="/?cat=408">男性向け記事一覧</a></li>
            <li><a href="/?cat=455">TL・BL記事一覧</a></li>
            <li><a href="/?cat=1291">成年コミック記事一覧</a></li>
            <li><a href="<?php echo esc_url(home_url('/#ranking')); ?>">総合ランキング</a></li>
            <li><a href="/?cat=408/#ranking">男性向けランキング</a></li>
            <li><a href="/?cat=455/#ranking">TL・BL漫画ランキング</a></li>
            <li><?php
                $post_id = 589;
                $permalink = get_permalink($post_id);
                echo '<a href="' . esc_url($permalink) . '">プライバシーポリシー</a>';
                ?>
            </li>
            <li><?php
                $post_id = 584;
                $permalink = get_permalink($post_id);
                echo '<a href="' . esc_url($permalink) . '">利用規約</a>';
                ?>
            </li>
            <li><?php
                $post_id = 576;
                $permalink = get_permalink($post_id);
                echo '<a href="' . esc_url($permalink) . '">運営者情報</a>';
                ?>
            </li>
            <li><a href="/contact/">お問い合わせ</a>
            </li>
        </ul>
    </nav>
    <p class="copy-light">© えちえち同人ベアーブログ All rights reserved.</p>
</footer>