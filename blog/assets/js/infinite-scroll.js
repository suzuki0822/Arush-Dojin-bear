jQuery(function ($) {
    var page = 2;
    var loading = false;
    var $window = $(window);
    var $content = $(".blog-list, .search-inner ul"); // 投稿リストを包含する要素のセレクタ
    var categoryID = $('body').('category'); // bodyタグからカテゴリーIDを読み取る
    var data = {
        'action': 'more_post_ajax',
        'pageNumber': page,
        'category__in': [categoryID], // 読み取ったカテゴリーIDを使用
        'ppp': 6
    }; {
        var load_posts = function () {
            $.ajax({
                type: "POST",
                data: {
                    pageNumber: page,
                    ppp: 6, // ページごとの投稿数
                    action: "more_post_ajax",
                },
                url: ajax_params.ajax_url, // 修正箇所
                success: function (data) {
                    if (data) {
                        $content.append(data);
                        page++;
                        loading = false;
                    } else {
                        // 投稿がもうない場合
                        loading = true; // これ以上読み込む投稿がないことを示す
                    }
                }
            });
        }
    };

    $window.scroll(function () {
        var content_offset = $content.offset();
        if (!loading && ($window.scrollTop() + $window.height()) > (content_offset.top + $content.height())) {
            loading = true;
            load_posts();
        }
    });
});
