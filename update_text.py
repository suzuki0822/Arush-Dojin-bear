import requests
from requests.auth import HTTPBasicAuth
from lxml import html
import html as html_converter

# 認証情報
username = "suzuki"
application_password = "U1EA atcm azSG McSY oQXp B3WZ"

# WordPressのサイトURL
base_url = "https://dojin-bear.net/wp-json/wp/v2"


# すべての記事を取得する
def get_all_posts():
    all_posts = []
    page = 1
    while True:
        response = requests.get(
            f"{base_url}/posts?per_page=100&page={page}&status=any",
            auth=(username, application_password),
        )
        if response.status_code == 200:
            posts = response.json()
            if not posts:
                break
            all_posts.extend(posts)
            print(f"Page {page}: {len(posts)} posts fetched")
            page += 1
        else:
            print(f"Failed to fetch posts: {response.status_code}")
            break
    return all_posts


# 記事の内容を更新する
def update_post_content(post_id, new_content):
    data = {"content": new_content}
    update_response = requests.post(
        f"{base_url}/posts/{post_id}", auth=(username, application_password), json=data
    )
    print(f"Updating Post {post_id} response code: {update_response.status_code}")
    return update_response.status_code == 200


# 特定のパターンに基づいて記事の内容を変更する
def modify_and_update_posts():
    posts = get_all_posts()
    if posts:
        for post in posts:
            post_id = post["id"]
            content = post["content"]["rendered"]
            title = post["title"]["rendered"]
            tree = html.fromstring(content)

            # ここでXPathを使って特定の要素を探し、条件に応じて置換する
            # 例えば、XPathで指定された要素のテキストが特定の文字列と一致する場合に置換する
            # ※以下のXPathは例です。実際の要件に合わせて調整してください。
            xpath_expressions = [
                (
                    f'//*[@id="post-{post_id}"]/div[1]/p[2]/a',
                    "詳しくはこちら！",
                    f"無料で更に漫画『{title}』を試し読み！",
                ),
                (
                    f'//*[@id="post-{post_id}"]/div[1]/blockquote/p[1]/strong',
                    "作品内容:",
                    f"漫画『{title}』の作品内容：",
                ),
            ]

            for xpath, match, replacement in xpath_expressions:
                elements = tree.xpath(xpath)
                for element in elements:
                    if element.text == match:
                        element.text = replacement

            new_content = html_converter.unescape(
                html.tostring(
                    tree, pretty_print=True, method="html", encoding="unicode"
                )
            )

            # 更新された内容で記事を更新する
            if update_post_content(post_id, new_content):
                print(f"Post {post_id} updated.")
            else:
                print(f"Post {post_id} not updated or not found.")


# 実行
modify_and_update_posts()
