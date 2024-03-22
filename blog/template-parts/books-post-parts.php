<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="entry-content">
		<?php
		$terms = get_the_terms(get_the_ID(), 'company');
		if ($terms && !is_wp_error($terms)) {
		?>
			<div class="coment-box">
				<?php foreach ($terms as $term) : ?>
					<img src="<?php echo get_template_directory_uri(); ?>/img/chara-icon.png" class="chara-icon" alt="chara-icon">
					<div class="chara-text">
						<p>今日は<span class="chara-text-title"><?php echo esc_html($term->name); ?></span>さんの作品、<span class="chara-text-title">『<?php the_title() ?>』</span>を紹介しちゃうよ！買う前に中身をちょっと見たいよね？そんなあなたにうってつけの記事だよ！</p>
					</div>
				<?php endforeach; ?>
			</div>

			<div class="single-content-group">
				<?php foreach ($terms as $term) : ?>
					<p><a href="<?php echo get_term_link($term); ?>">出版社:<?php echo esc_html($term->name); ?> </a></p>
				<?php endforeach; ?>
			</div>
		<?php
		}
		?>

		<div class="single-page-tag">
			<?php
			$tags = get_the_tags();
			$is_cat_455 = has_category(455); // 現在の投稿がカテゴリーID 455に属しているかをチェック

			if ($tags) {
				foreach ($tags as $tag) {
					$tag_name = esc_html($tag->name);
					$tag_id = $tag->term_id;
					$get_link = get_tag_link($tag_id);

					// カテゴリーID 455に属する場合、タグのリンクにクエリパラメータを追加
					if ($is_cat_455) {
						$get_link = add_query_arg('from_cat', '455', $get_link);
					}

					echo "<p><a href=\"$get_link\">$tag_name</a></p>";
				}
			}
			?>
		</div>
		<?php
		the_content();

		if (is_single()) {
			// ページ分割への対応
			$link_pages_args = array(
				'before'         => '<p class="entry-link-pages">',
				'next_or_number' => 'next',
			);
			wp_link_pages($link_pages_args);
		}
		?>

		<div class="single-content-group-bottom">
			<img src="<?php echo get_template_directory_uri(); ?>/img/chara-icon.png" class="chara-icon" alt="chara-icon">
			<?php foreach ($terms as $term) : ?>
				<p>他の<span class="single-content-group-bottom-title">「<a href="<?php echo get_term_link($term); ?>"><?php echo esc_html($term->name); ?>」</span>さんの作品はこちら！</a></p>
			<?php endforeach; ?>
		</div>
	</div><!-- .entry-content -->


	<h2 class="viewd-content-ttl">最近チェックした作品</h2>
	<div id="viewed-posts-container"></div>


	<script>
		jQuery(document).ready(function($) {
			var postId = <?php echo get_the_ID(); ?>; // 現在の投稿IDを取得
			var viewedPosts = localStorage.getItem('viewedPosts') ? JSON.parse(localStorage.getItem('viewedPosts')) : [];

			if (viewedPosts.indexOf(postId) === -1) { // 重複チェック
				// 配列の長さが10より大きい場合、一番古い記事（配列の最初の要素）を削除
				if (viewedPosts.length >= 10) {
					viewedPosts.shift(); // 配列の最初の要素を削除
				}
				viewedPosts.push(postId); // 配列に追加
				localStorage.setItem('viewedPosts', JSON.stringify(viewedPosts)); // 更新された配列を保存
			}
			$.ajax({
				url: theme_script_vars.ajaxurl,
				type: 'POST',
				data: {
					'action': 'fetch_viewed_posts',
					'posts': viewedPosts
				},
				success: function(response) {
					$('#viewed-posts-container').html(response);

					// Ajaxリクエスト成功後にslick sliderを初期化
					$('#viewed-posts-container ul').slick({
						centerMode: true,
						centerPadding: '20px',
						slidesToShow: 4,
						slidesToScroll: 1,
						autoplay: true,
						autoplaySpeed: 2500,
						prevArrow: '<button type="button" class="slick-prev">Previous</button>',
						nextArrow: '<button type="button" class="slick-next">Next</button>',
						responsive: [{
								breakpoint: 768,
								settings: {
									arrows: true,
									centerMode: true,
									centerPadding: '40px',
									slidesToShow: 2
								}
							},
							{
								breakpoint: 480,
								settings: {
									dots: true,
									arrows: true,
									centerMode: true,
									centerPadding: '10px',
									slidesToShow: 2
								}
							}
						]
					});
				}
				// その他のコード（Ajaxリクエストなど）はここに記述
			});
		});
	</script>


	<?php
	$current_post_categories = get_the_category();
	$category_ids = array();
	$is_cat_455 = false; // カテゴリーID 455に属しているかのフラグ

	// 現在の投稿のカテゴリーIDを取得し、カテゴリーID 455に属しているか確認
	foreach ($current_post_categories as $category) {
		$category_ids[] = $category->term_id;
		if ($category->term_id == 455) {
			$is_cat_455 = true; // 現在の投稿がカテゴリーID 455に属している
			break;
		}
	}

	// 関連する投稿のクエリを設定
	if ($is_cat_455) {
		// 現在の投稿がカテゴリーID 455に属している場合、カテゴリーID 455のみの投稿を表示
		$related_posts_args = array(
			'post_type' => 'post',
			'posts_per_page' => 4,
			'category__in' => array(455),
			'post__not_in' => array(get_the_ID()),
			'orderby' => 'rand'
		);
	} else {
		// 現在の投稿がカテゴリーID 455に属していない場合、カテゴリーID 455を除外して表示
		$related_posts_args = array(
			'post_type' => 'post',
			'posts_per_page' => 4,
			'category__in' => array(408),
			'post__not_in' => array(get_the_ID()),
			'orderby' => 'rand'
		);
	}

	$related_posts_query = new WP_Query($related_posts_args);
	if ($related_posts_query->have_posts()) :
	?>

		<div class="related_posts">
			<h2>この作品を見た人はこんな作品も見ています</h2>
			<ul>
				<?php while ($related_posts_query->have_posts()) :
					$related_posts_query->the_post();
				?>
					<li>
						<a href="<?php the_permalink(); ?>" class="post-link">
							<div class="content-box">
								<div class="tumb">
									<?php
									the_post_thumbnail();
									$attachments = get_children(array(
										'post_parent' => $post->ID,
										'post_type' => 'attachment',
										'post_mime_type' => 'image',
										'orderby' => 'menu_order',
										'order' => 'ASC',
										'offset' => 1
									));
									if (!empty($attachments)) {
										$attachments = array_values($attachments);
										$second_image = wp_get_attachment_image_src($attachments[0]->ID, 'full');
										echo '<img src="' . $second_image[0] . '" class="hover-image" style="display: none;">';
									}
									?>
								</div>
								<div class="detail-box">
									<p class="text-content"><?php the_title(); ?></p>

									<?php
									$terms = get_the_terms(get_the_ID(), 'group');
									if ($terms && !is_wp_error($terms)) :
									?>
										<div class="single-content-group">
											<?php foreach ($terms as $term) : ?>
												<p><a href="<?php echo get_term_link($term); ?>">サークル:<?php echo esc_html($term->name); ?> </a></p>
											<?php endforeach; ?>
										</div>
									<?php endif; ?>

									<!-- タグの表示 -->
									<div class="single-page-tag">
										<?php
										$tags = get_the_tags();
										$is_cat_455 = has_category(455); // 現在の投稿がカテゴリーID 455に属しているかをチェック

										if ($tags) {
											foreach ($tags as $tag) {
												$tag_name = esc_html($tag->name);
												$tag_id = $tag->term_id;
												$get_link = get_tag_link($tag_id);

												// カテゴリーID 455に属する場合、タグのリンクにクエリパラメータを追加
												if ($is_cat_455) {
													$get_link = add_query_arg('from_cat', '455', $get_link);
												}

												echo "<p><a href=\"$get_link\">$tag_name</a></p>";
											}
										}
										?>
									</div>
									<?php
									$content = get_the_content();
									$dom = new DOMDocument();
									@$dom->loadHTML(mb_convert_encoding($content, 'HTML-ENTITIES', 'UTF-8'));

									$blockquotes = $dom->getElementsByTagName('blockquote');

									if ($blockquotes->length > 0) {
										$firstBlockquote = $blockquotes->item(0); // 最初のblockquoteを取得
										$firstBlockquoteContent = ''; // 最初のblockquoteの内容を一時保存する変数

										// 最初のblockquote要素の内部HTMLを取得
										foreach ($firstBlockquote->childNodes as $child) {
											$html = $dom->saveHTML($child);
											$firstBlockquoteContent .= $html; // 内容を変数に追加
										}

										// HTMLタグを除去し、文字列の前後の空白や特殊文字を削除
										$firstBlockquoteContent = trim(strip_tags($firstBlockquoteContent));

										// 最初のblockquoteの内容が10文字を超える場合のみ表示、かつ230文字でカット
										if (strlen($firstBlockquoteContent) > 30) {
											if (strlen($firstBlockquoteContent) > 150) {
												$firstBlockquoteContent = mb_substr($firstBlockquoteContent, 0, 230) . '...';
											}
											echo '<blockquote><div class="post-blockquotes">';
											echo $firstBlockquoteContent;
											echo '</div></blockquote>';
										} else {
											// 最初のblockquoteの内容が10文字以下の場合、非表示にする
											echo '<blockquote style="display:none;"><div class="post-blockquotes"></div></blockquote>';
										}

										// 残りのblockquoteを処理
										for ($i = 1; $i < $blockquotes->length; $i++) {
											$blockquote = $blockquotes->item($i);
											$blockquoteContent = '';

											foreach ($blockquote->childNodes as $child) {
												$html = $dom->saveHTML($child);
												$blockquoteContent .= $html;
											}

											// HTMLタグを除去し、文字列の前後の空白や特殊文字を削除
											$blockquoteContent = trim(strip_tags($blockquoteContent));

											// すべての残りのblockquoteを表示、かつ230文字でカット
											if (strlen($blockquoteContent) > 230) {
												$blockquoteContent = substr($blockquoteContent, 0, 230) . '...';
											}
											echo '<blockquote><div class="post-blockquotes">';
											echo $blockquoteContent;
											echo '</div></blockquote>';
										}
									} ?>
								</div>
								<a href="<?php the_permalink(); ?>" class="related_posts-btn">無料で試し読みをする</a>
							</div>
						</a>
					</li>
				<?php endwhile;
				wp_reset_postdata();
				?>
			</ul>
		</div>
	<?php endif ?>
</article><!-- #post-<?php the_ID(); ?> -->