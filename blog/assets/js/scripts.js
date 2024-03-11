function myFunction(x) {
    x.classList.toggle("change");
    var overlay = document.getElementById('overlay');
    var menu = document.querySelector('.sp-menu');
    if (overlay.style.opacity === "0" || overlay.style.opacity === '') {
        overlay.style.display = "block";
        setTimeout(function () {
            overlay.style.opacity = "1";
            menu.style.opacity = "1";
        }, 50);
    } else {
        overlay.style.opacity = "0";
        menu.style.opacity = "0";
        setTimeout(function () { overlay.style.display = "none"; }, 500);
    }
}



jQuery(document).ready(function ($) {
    $(document).on('ContentLoaded', function () {
        $('.blog-list-slick').not('.slick-initialized').slick({
            centerMode: true,
            centerPadding: '60px',
            slidesToShow: 4,
            slidesToScroll: 1,
            autoplay: true,
            autoplaySpeed: 3000,
            prevArrow: false,
            nextArrow: false,
            responsive: [
                {
                    breakpoint: 768,
                    settings: {
                        arrows: false,
                        centerMode: true,
                        centerPadding: '40px',
                        slidesToShow: 3
                    }
                },
                {
                    breakpoint: 480,
                    settings: {
                        dots: true,
                        arrows: false,
                        centerMode: true,
                        centerPadding: '35px',
                        slidesToShow: 1
                    }
                }]
        });
    });
    $(document).trigger('ContentLoaded');
});


var hamburgerMenu = document.querySelector('.hamburger-menu');

// メニュー内のリンクすべてを取得
var menuLinks = document.querySelectorAll('.sp-menu a');

// メニュー内のリンクすべてに対して処理を行う
menuLinks.forEach(function (link) {
    link.addEventListener('click', function (e) {
        // ハンバーガーメニューが開いている場合は、閉じる
        if (hamburgerMenu.classList.contains('change')) {
            hamburgerMenu.click();
        }
    });
});


document.getElementById('scrollToTop').addEventListener('click', function (e) {
    // デフォルトのアンカー動作を無効化
    e.preventDefault();

    // トップへスムーズにスクロール
    window.scrollTo({
        top: 0,
        behavior: 'smooth' // スムーズスクロールを有効化
    });
});



// document.addEventListener("DOMContentLoaded", function() {
//     var postItems = document.querySelectorAll('.tumb');

//     postItems.forEach(function(item) {
//         var hoverImage = item.querySelector('.hover-image'); // '.internal-image' から '.hover-image' に変更

//         window.addEventListener('scroll', function() {
//             var itemRect = item.getBoundingClientRect();
//             var windowHeight = window.innerHeight;

//             if(itemRect.top < windowHeight && itemRect.bottom > 0) { // 要素が表示領域に入った時
//                 hoverImage.style.display = 'block'; // display: none を解除して表示
//                 hoverImage.style.opacity = '1';
//             } else {
//                 hoverImage.style.opacity = '0';
//                 setTimeout(function() { hoverImage.style.display = 'none'; }, 500); // 透明になった後に非表示
//             }
//         });
//     });
// });

document.addEventListener('DOMContentLoaded', function() {
    // ".popular_search a"内のすべてのaタグに対する参照を取得
    var searchLinks = document.querySelectorAll('.popular_search a');

    // 各リンクに対して処理を行う
    searchLinks.forEach(function(link) {
        link.addEventListener('click', function(event) {
            // デフォルトのリンク動作を阻止
            event.preventDefault();

            // リンクのテキスト（検索ワード）を取得
            var searchWord = this.textContent || this.innerText;

            // 検索ワードが空でない場合のみ処理を実行
            if (searchWord.trim().length > 0) {
                // 検索ワードをURLエンコード
                var encodedSearchWord = encodeURIComponent(searchWord.trim());

                // 検索結果ページのURLを構築
                var searchUrl = `https://dojin-bear.net/?s=${encodedSearchWord}&category=`;

                // 構築したURLにリダイレクト
                window.location.href = searchUrl;
            }
        });
    });
});

