<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <!-- Google Tag Manager -->
    <script>
        (function(w, d, s, l, i) {
            w[l] = w[l] || [];
            w[l].push({
                'gtm.start': new Date().getTime(),
                event: 'gtm.js'
            });
            var f = d.getElementsByTagName(s)[0],
                j = d.createElement(s),
                dl = l != 'dataLayer' ? '&l=' + l : '';
            j.async = true;
            j.src =
                'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
            f.parentNode.insertBefore(j, f);
        })(window, document, 'script', 'dataLayer', 'GTM-PH432966');
    </script>
    <!-- End Google Tag Manager -->
    <meta charset="<?php bloginfo('charset'); ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php if (is_home() || is_front_page()) : ?>
        <title>無料同人ベアーブログ</title>
        <meta name="description" content="あなたが求める男性向け・TL・BLの同人がすべてここに！" />
    <?php else : ?>
        <title><?php the_title(); ?></title>
    <?php endif; ?>
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- Google tag (gtag.js) -->
    <!-- <script async src="https://www.googletagmanager.com/gtag/js?id=G-349CRCL1EH"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());
        gtag('config', 'G-349CRCL1EH');
    </script> -->
    <?php wp_head(); ?>
</head>