from bs4 import BeautifulSoup
import requests
import json
import html
import unicodedata
from datetime import datetime
import os


def normalize_string(input_str):
    # HTMLエンティティをデコード
    decoded_str = html.unescape(input_str)
    # Unicode正規化
    normalized_str = unicodedata.normalize("NFC", decoded_str)
    # 三点リーダとホリゾンタル・エリプシスの統一
    unified_str = normalized_str.replace("...", "…").replace("．．．", "…")
    return unified_str


# APIキーをセット
api_id = "ahPWuAVX7vvyE00YWEUD"
affiliate_id = "dojinbear-990"

# 商品情報APIのURLを作成
item_url = "https://api.dmm.com/affiliate/v3/ItemList"
params = {
    "api_id": api_id,
    "affiliate_id": affiliate_id,
    "site": "FANZA",
    "service": "doujin",
    "floor": "digital_doujin",
    "article": "genre",
    "article_id": "558",
    "hits": 30,
    # 'gte_date': '2024-02-26T00:00:00',
    "sort": "rank",
    "output": "json",
}
# APIから商品情報を取得
response = requests.get(item_url, params=params)
data = response.json()

# WordPressのREST APIエンドポイント
blog_url = "https://dojin-bear.net/"
post_api_url = f"{blog_url}/wp-json/wp/v2/posts"
tag_api_url = f"{blog_url}/wp-json/wp/v2/tags"
media_api_url = f"{blog_url}/wp-json/wp/v2/media"
group_api_url = f"{blog_url}/wp-json/wp/v2/group"

# WordPressのユーザー認証情報
api_user = "suzuki"
api_password = "U1EA atcm azSG McSY oQXp B3WZ"


# すべての記事タイトルを取得する関数
def get_all_post_titles():
    all_titles = []
    page = 1
    while True:
        response = requests.get(
            post_api_url,
            auth=(api_user, api_password),
            params={"per_page": 100, "page": page, "status": "any"},
        )
        if response.status_code == 200:
            posts = response.json()
            if not posts:
                break
            for post in posts:
                title = normalize_string(post["title"]["rendered"])
                status = post["status"]  # ステータスを取得
                print(f"Title: {title}, Status: {status}")  # タイトルとステータスを出力
                all_titles.append(title)
            page += 1
        else:
            print(f"Failed to fetch posts, status code: {response.status_code}")
            break
    return all_titles


existing_titles = get_all_post_titles()
# 除外したいジャンルIDのリスト
excluded_genre_ids = [
    "152002",
    "4076",
    "514",
    "4127",
    "153006",
    "7110",
    "7111",
    "153008",
    "153010",
    "153011",
    "153012",
    "30104",
    "153016",
    "8",
    "12",
    "160006",
    "160004",
    "160103",
]
filtered_items = []
for item in data["result"]["items"]:
    # 各商品に対して、ジャンルIDを取得
    item_genre_ids = [
        genre["id"] for genre in item.get("iteminfo", {}).get("genre", [])
    ]

    # 除外したいジャンルIDが商品のジャンルIDリストに含まれていないかチェック
    if not any(excluded_id in item_genre_ids for excluded_id in excluded_genre_ids):
        # 除外したいジャンルIDが含まれていなければ、フィルタリングされた商品リストに追加
        filtered_items.append(item)
# フィルタリングされた商品リストを保持するためのリスト

for item in filtered_items:
    title = item["title"]
    # 作成しようとしている記事のタイトルに対して正規化を適用
    normalized_title_to_create = normalize_string(title)

    if normalized_title_to_create in existing_titles:
        print(
            f"記事「{normalized_title_to_create}」は既に存在しています。スキップします。"
        )
        continue
    category = item["category_name"]
    tags = item.get("iteminfo", {}).get("genre", [])
    tag_names = [tag["name"] for tag in tags]
    affiliate_url = item["affiliateURL"]
    image_url = item.get("imageURL", {}).get("large", "")
    makers = item.get("iteminfo", {}).get("maker", [])
    maker_names = [maker["name"] for maker in makers]
    date = item["date"]

    page = item.get("volume", "N/A")
    price = item.get("prices", {}).get("price", "")
    # 画像をダウンロードしてWordPressにアップロード
    img_response = requests.get(image_url)
    image_data = img_response.content
    files = {"file": ("fanza-image-catch.jpg", image_data, "image/jpeg")}
    response = requests.post(media_api_url, files=files, auth=(api_user, api_password))
    img_upload_data = response.json()
    first_image_id = img_upload_data.get("id")

    # 最初の画像のURLを取得
    response = requests.get(
        f"{media_api_url}/{first_image_id}", auth=(api_user, api_password)
    )
    image_data = response.json()
    first_image_url = image_data["source_url"]

    # 記事の内容を組み立てる
    post_content = f"<a href='{affiliate_url}'><img src='{first_image_url}' alt='Featured Image'></a>\n"
    sale_date = date.split(" ")[0]

    post_content += f"""
        <div class="file-type"><strong>ページ数:</strong><p class="file-type-content"> {page}ページ</p></div>
        <div class="content-age"><strong>価格:</strong> <p class="fcontent-age-content">{price}円</p></div>
        <div class="launch-day"><strong>販売日:</strong> <p class="launch-day-content">{sale_date}</p></div>
    """

    # その他の画像と記事の内容を追加
    cookies = {"age_check_done": "1"}
    affiliate_response = requests.get(affiliate_url, cookies=cookies)
    soup = BeautifulSoup(affiliate_response.text, "html.parser")

    summary_text_element = soup.select_one(
        "#l-areaVariableBoxWrap > div > div.l-areaProductSummary > div > div > div > p"
    )
    if summary_text_element:
        summary_text = summary_text_element.get_text(strip=True)
        post_content += f"<blockquote class='content-intro'><strong>漫画『{title}』の作品内容:</strong> <p class='content-intro-content'>{summary_text} 今すぐ漫画『{title}』を無料で試し読み！</p></blockquote>"

    target_elements = soup.select(
        "#l-areaVariableBoxWrap div.l-areaVariableBox div.l-areaVariableBoxGroup div.l-areaProductImage ul.productPreview li img"
    )
    image_urls = [element.get("src") for element in target_elements][
        1:
    ]  # 最初の画像を除外

    # 2枚目以降の画像を記事に挿入
    for img_url in image_urls:
        response = requests.get(img_url)
        image_data = response.content
        headers = {
            "Content-Type": "image/jpeg",
            "Content-Disposition": "attachment; filename=newimage.jpeg",
        }
        response = requests.post(
            media_api_url,
            headers=headers,
            data=image_data,
            auth=(api_user, api_password),
        )
        if response.status_code == 201:
            res_json = response.json()
            image_id = res_json.get("id")
            image_info = requests.get(
                f"{blog_url}/wp-json/wp/v2/media/{image_id}"
            ).json()
            if "media_details" in image_info:
                year = image_info["media_details"]["file"].split("/")[0]
                month = image_info["media_details"]["file"].split("/")[1]
                file_name = image_info["media_details"]["file"].split("/")[-1]
                image_url_absolute = (
                    f"{blog_url}/wp-content/uploads/{year}/{month}/{file_name}"
                )
                post_content += (
                    f'<img src="{image_url_absolute}" alt="Additional Image">\n'
                )

    post_content += f"<br><br><a href='{affiliate_url}'class ='dl-btn'>無料で更に漫画『{title}』を試し読み！</a><br><br>\n"

    # タグとカテゴリーの処理（ここでは省略。実際には、必要に応じてタグやカテゴリーを作成または検索し、そのIDを使用します）
    tag_ids = []  # tag_idsを初期化

    for tag_name in tag_names:
        response = requests.get(
            tag_api_url, params={"search": tag_name}, auth=(api_user, api_password)
        )
        tags = response.json()

        if len(tags) > 0:
            # タグが存在する場合、そのIDをリストに追加
            tag_ids.append(tags[0]["id"])
        else:
            # タグが存在しない場合、新しいタグを作成
            tag_data = {"name": tag_name}
            response = requests.post(
                tag_api_url, json=tag_data, auth=(api_user, api_password)
            )
            if response.status_code == 201:
                new_tag = response.json()
                tag_ids.append(new_tag["id"])
            # WordPressに記事を投稿

        group_ids = []

    for maker_name in maker_names:
        response = requests.get(
            group_api_url, params={"search": maker_name}, auth=(api_user, api_password)
        )

        if response.status_code == 200:
            existing_categories = response.json()
            if existing_categories:
                existing_category = existing_categories[
                    0
                ]  # 一致するカテゴリが複数ある場合は最初のものを取得
                group_ids.append(existing_category["id"])
                print(f'Category "{maker_name}" already exists. Skipping creation.')
            else:
                print(f"Creating new category: {maker_name}")
                category_data = {"name": maker_name}
                response = requests.post(
                    group_api_url, auth=(api_user, api_password), json=category_data
                )

                if response.status_code == 201:
                    category_id = response.json()["id"]
                    group_ids.append(category_id)
                    print(f'Category "{maker_name}" created successfully.')
                else:
                    print(f'Failed to create category "{maker_name}".')

    default_cate = 670
    default_cate2 = 455
    default_cate3 = 161
    categories = [default_cate, default_cate2, default_cate3]
    post_data = {
        "title": title,
        "content": post_content,
        "status": "draft",
        "group": group_ids,
        "tags": [tag_id for tag_id in tag_ids],  # 実際のタグIDを設定
        "categories": categories,  # 実際のカテゴリIDを設定
        "featured_media": first_image_id,
    }
    post_response = requests.post(
        post_api_url, json=post_data, auth=(api_user, api_password)
    )
    if post_response.status_code == 201:
        print(f"記事「{title}」が正常に投稿されました。")
    else:
        print(
            f"記事「{title}」の投稿に失敗しました。ステータスコード: {post_response.status_code}"
        )
else:
    print("商品情報の取得に失敗しました。ステータスコード:", response.status_code)
