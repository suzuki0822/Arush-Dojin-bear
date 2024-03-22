from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
from bs4 import BeautifulSoup
import requests
import time
from html import unescape
import re
import traceback

# 認証情報
api_user = "suzuki"
api_password = "U1EA atcm azSG McSY oQXp B3WZ"
blog_url = "https://dojin-bear.net/"
post_api_url = f"{blog_url}/wp-json/wp/v2/posts"
media_api_url = f"{blog_url}/wp-json/wp/v2/media"
company_api_url = f"{blog_url}/wp-json/wp/v2/company"
tag_api_url = f"{blog_url}/wp-json/wp/v2/tags"
login_url = "https://login.dlsite.com/login"
email = "doujin.iwa@gmail.com"
password = "Doujiniwa0209"


def login_and_get_data(login_url, email, password):
    # WebDriverの設定
    driver = webdriver.Chrome()

    # ログインページにアクセス
    driver.get(login_url)

    # ユーザー名とパスワードを入力
    driver.find_element(By.ID, "form_id").send_keys(email)
    driver.find_element(By.ID, "form_password").send_keys(password)

    # ログインボタンをクリック
    driver.find_element(By.CSS_SELECTOR, "button[type='submit']").click()

    return driver  # WebDriverセッションを返す


def fetch_affiliate_data(driver, work_url, is_first_time=True):
    try:
        print(f"アクセス中のURL: {work_url}")
        driver.get(work_url)
        print("作品ページにアクセスしました。")
        if is_first_time:
            # WebDriverWaitとECを使って、btn_approval要素が見つかるまで待機し、見つかったらクリック
            print("承認ボタンを探しています...")
            btn_approval = WebDriverWait(driver, 20).until(
                EC.presence_of_element_located(
                    (By.CSS_SELECTOR, "li.btn_yes.btn-approval a")
                )
            )
            btn_approval.click()
            print("承認ボタンをクリックしました。")

            # ページ更新後に特定の要素が表示されるのを待つ
            print("ページ更新を待機しています...")
            WebDriverWait(driver, 20).until(
                EC.presence_of_element_located(
                    (
                        By.CSS_SELECTOR,
                        ".work_buy_guide.type_affiliate ul.guide_list li a",
                    )
                )
            )
            print("ページが更新されました。")

        # ページのHTMLを取得して解析
        soup = BeautifulSoup(driver.page_source, "html.parser")
        affiliate_link_elem = soup.select_one(
            ".work_buy_guide.type_affiliate ul.guide_list li a"
        )
        if affiliate_link_elem and "href" in affiliate_link_elem.attrs:
            affiliate_link = affiliate_link_elem["href"]
            print(f"アフィリエイトリンクを取得しました: {affiliate_link}")

            # アフィリエイトページにアクセス
            driver.get(affiliate_link)
            print(f"アフィリエイトページにアクセスしました: {affiliate_link}")

            # 必要な情報を抽出
            soup = BeautifulSoup(driver.page_source, "html.parser")
            text_wname_elem = soup.find("textarea", {"id": "text_wname"})

        if text_wname_elem:
            text_wname = text_wname_elem.get_text(strip=True)
            # hrefのURLを抽出するための正規表現パターン
            href_pattern = r'href="([^"]+)"'
            # 正規表現でhrefのURLを抽出
            href_match_wname = re.search(href_pattern, text_wname)
            if href_match_wname:
                href_url_wname = href_match_wname.group(1)  # 抽出したURL
                print("text_wnameから抽出したURL:", href_url_wname)
            else:
                print("text_wnameからURLが見つかりませんでした。")
        else:
            print("text_wname要素が見つかりませんでした。")

        text_main_elem = soup.find("textarea", {"id": "text_main"})
        if text_main_elem:
            text_main_encoded = text_main_elem.get_text(strip=True)
            # HTMLエンティティをデコード
            text_main_html = unescape(text_main_encoded)  # この変数に HTML 内容を保持
            # hrefのURLを抽出するための正規表現パターン
            href_pattern = r'href="([^"]+)"'
            # 正規表現でhrefのURLを抽出
            href_match_main = re.search(href_pattern, text_main_html)
            if href_match_main:
                href_url_main = href_match_main.group(1)  # 抽出したURL
                print("text_mainから抽出したURL:", href_url_main)
            else:
                print("text_mainからURLが見つかりませんでした。")
        else:
            print("text_main要素が見つかりませんでした。")

        return (
            text_wname,
            text_main_html,
            href_url_wname if href_match_wname else None,
            href_url_main if href_match_main else None,
        )  # text_main_html を返す

    except Exception as e:
        print(f"エラーが発生しました: {e}")
        print(f"スタックトレース: {traceback.format_exc()}")
        return None, None, None


def scrape_info(link):
    response = requests.get(link)
    soup = BeautifulSoup(response.text, "html.parser")
    # 辞書を初期化
    info_dict = {}
    work_parts_container = soup.find("div", class_="work_parts_area").text
    # サンプルボタンのHTMLを取得して辞書に追加
    sample_btn = soup.find("div", class_="recommend_list type_comipo type_work")
    info_dict["sample_btn_html"] = str(sample_btn) if sample_btn else "Not Found"
    # サイト内の他の情報を抽出して辞書に追加
    work_right_inner = soup.find("div", id="work_right_inner")
    print(sample_btn)
    slider_item = soup.find_all("div", class_="product-slider-data")
    sample_images = []  # サンプル画像のURLを格納するリスト
    for item in slider_item:
        image_data = item.find_all("div", {"data-src": True})
        for img in image_data:
            img_url = f"https:{img['data-src']}"
            sample_images.append(img_url)
    if work_right_inner:
        # サークル名
        circle_name_elem = work_right_inner.find("span", class_="maker_name")
        if circle_name_elem:
            info_dict["出版社"] = circle_name_elem.text.strip()

        # 販売日
        sale_date_elem = work_right_inner.find("th", string="販売日")
        if sale_date_elem and sale_date_elem.find_next("td"):
            info_dict["販売日"] = sale_date_elem.find_next("td").text.strip()

        # 年齢指定
        age_rating_elem = work_right_inner.find("th", string="年齢指定")
        if age_rating_elem and age_rating_elem.find_next("div", class_="work_genre"):
            info_dict["年齢指定"] = age_rating_elem.find_next(
                "div", class_="work_genre"
            ).text.strip()

        # 作品形式
        work_format_elem = work_right_inner.find("th", string="作品形式")
        if work_format_elem and work_format_elem.find_next("div", class_="work_genre"):
            info_dict["作品形式"] = work_format_elem.find_next(
                "div", class_="work_genre"
            ).text.strip()

        # ファイル形式
        file_format_elem = work_right_inner.find("th", string="ファイル形式")
        if file_format_elem and file_format_elem.find_next("div", class_="work_genre"):
            info_dict["ファイル形式"] = file_format_elem.find_next(
                "div", class_="work_genre"
            ).text.strip()

        # ジャンル
        genre_th = work_right_inner.find("th", string="ジャンル")
        if genre_th and genre_th.find_next("div", class_="main_genre"):
            genre = genre_th.find_next("div", class_="main_genre").text.strip()
            print("genreeeeeeeeeeeeeeeeeeee", genre)
        else:
            genre = "ジャンル不明"  # ジャンルが見つからなかった場合のデフォルト値

        info_dict["ジャンル"] = genre
        # ファイル容量
        file_size = (
            work_right_inner.find("th", string="ファイル容量")
            .find_next("div", class_="main_genre")
            .text.strip()
        )
        info_dict["ファイル容量"] = file_size
        info_dict["サンプル画像"] = sample_images
        info_dict["作品内容"] = work_parts_container

        return info_dict


def get_existing_post_titles():
    page = 1
    existing_titles = set()
    while True:
        response = requests.get(
            post_api_url,
            params={"per_page": 100, "page": page},
            auth=(api_user, api_password),
        )
        if response.status_code == 200:
            posts = response.json()
            if not posts:  # ページの終わりに達したらループを終了
                break
            existing_titles.update({post["title"]["rendered"] for post in posts})
            page += 1  # 次のページへ
        else:
            print(
                f"既存の投稿を取得できませんでした。ステータスコード: {response.status_code}"
            )
            break
    print(f"取得した既存の投稿タイトル: {existing_titles}")  # デバッグ情報
    return existing_titles


def upload_image(image_url):
    response = requests.get(image_url)
    image_data = response.content
    headers = {
        "Content-Type": "image/jpeg",
        "Content-Disposition": "attachment; filename=newimage.jpeg",
    }
    response = requests.post(
        media_api_url, headers=headers, data=image_data, auth=(api_user, api_password)
    )
    if response.status_code == 201:
        return response.json()["id"]
    else:
        print("画像のアップロードに失敗しました。")
        return None


def create_or_get_category(category_name):
    response = requests.get(
        f"{blog_url}/wp-json/wp/v2/categories",
        params={"search": category_name},
        auth=(api_user, api_password),
    )
    if response.status_code == 200 and response.json():
        return response.json()[0]["id"]
    else:
        category_data = {"name": category_name}
        response = requests.post(
            f"{blog_url}/wp-json/wp/v2/categories",
            auth=(api_user, api_password),
            json=category_data,
        )
        if response.status_code == 201:
            return response.json()["id"]
        else:
            print(f'Failed to create category "{category_name}".')
            return None


def create_or_get_group(company_name):
    response = requests.get(
        company_api_url, params={"search": company_name}, auth=(api_user, api_password)
    )
    if response.status_code == 200 and response.json():
        company_id = response.json()[0]["id"]
        print(f'グループ "{company_name}" は既に存在します。ID: {company_id}')
        return company_id
    else:
        company_data = {"name": company_name}
        response = requests.post(
            company_api_url, auth=(api_user, api_password), json=company_data
        )
        if response.status_code == 201:
            new_company_id = response.json()["id"]
            print(
                f'新しいグループ "{company_name}" が作成されました。ID: {new_company_id}'
            )
            return new_company_id
        else:
            print(f'グループ "{company_name}" の作成に失敗しました。')
            return None


# wordpress上の記事をすべて取得して作成する記事のタイトルと比較
def get_existing_post_titles():
    page = 1
    existing_titles = set()
    total_pages = 1  # 初期値を1ページとする

    while page <= total_pages:
        params = {
            "per_page": 100,
            "page": page,
            "status": "any",  # すべての状態の投稿を取得（下書き状態含む）
        }
        response = requests.get(
            post_api_url, params=params, auth=(api_user, api_password)
        )
        if response.status_code == 200:
            posts = response.json()
            existing_titles.update({post["title"]["rendered"] for post in posts})

            # 初回のリクエストで総ページ数を取得
            if page == 1:
                total_pages = int(response.headers.get("X-WP-TotalPages", 1))

            page += 1  # 次のページへ
        else:
            print(
                f"既存の投稿を取得できませんでした。ステータスコード: {response.status_code}, ページ: {page}"
            )
            break

    print(f"取得した既存の投稿タイトル: {existing_titles}")  # デバッグ情報
    return existing_titles


def create_or_get_tag(tag_name):
    response = requests.get(
        tag_api_url, params={"search": tag_name}, auth=(api_user, api_password)
    )
    if response.status_code == 200 and response.json():
        # タグが既に存在する場合
        tag_id = response.json()[0]["id"]
        print(
            f'タグ "{tag_name}" は既に存在します。ID: {tag_id}'
        )  # 既存のタグのIDをログに出力
        return tag_id
    else:
        # 新しいタグを作成
        tag_data = {"name": tag_name}
        response = requests.post(
            tag_api_url, auth=(api_user, api_password), json=tag_data
        )
        if response.status_code == 201:
            new_tag_id = response.json()["id"]
            print(
                f'新しいタグ "{tag_name}" が作成されました。ID: {new_tag_id}'
            )  # 新規に作成されたタグのIDをログに出力
            return new_tag_id
        else:
            print(f'タグ "{tag_name}" の作成に失敗しました。')
            return None


def get_image_url(image_id):
    response = requests.get(
        f"{media_api_url}/{image_id}", auth=(api_user, api_password)
    )
    if response.status_code == 200:
        image_data = response.json()
        if "media_details" in image_data and "sizes" in image_data["media_details"]:
            # 'full' サイズの画像 URL を取得 (他のサイズも選べます)
            image_url = image_data["media_details"]["sizes"]["full"]["source_url"]
            return image_url
    return None


def create_post(
    title, scraped_info, existing_titles, text_wname, text_main_html, driver, sample_btn
):  # 引数名を text_main_html に変更
    # タイトルが既に存在する場合はスキップ
    if title in existing_titles:
        print(f"記事「{title}」は既に存在しています。スキップします。")
        return

    # ジャンルを改行(\n)で分割し、それぞれのジャンルに対してタグを取得
    genres = scraped_info["ジャンル"].split("\n")
    tag_ids = [create_or_get_tag(genre.strip()) for genre in genres if genre.strip()]
    sale_date = scraped_info["販売日"].split(" ")[0]

    print("text_mainの内容:", text_main)
    post_content = text_main

    # 投稿の内容
    post_content += f"""
        <div class="file-size"><strong>ファイル容量:</strong> <p class="file-size-content">{scraped_info.get('ファイル容量', 'N/A')}</p></div>
        <div class="file-type"><strong>ファイル形式:</strong><p class="file-type-content"> {scraped_info.get('ファイル形式', 'N/A')}</p></div>
        <div class="content-age"><strong>年齢指定:</strong> <p class="content-age-content">{scraped_info.get('年齢指定', 'N/A')}</p></div>
        <div class="launch-day"><strong>販売日:</strong> <p class="launch-day-content">{sale_date}</p></div>
        <div class="sample-btn">{scraped_info.get('sample_btn_html', 'N/A')}</div>
        <blockquote class="content-intro">
            <strong>漫画『{title}』の作品内容:</strong> <p class="content-intro-content">{scraped_info['作品内容']}
            今すぐ漫画『{title}』を無料で試し読み！</p>
        </blockquote>
    """

    if scraped_info["サンプル画像"]:
        for img_url in scraped_info["サンプル画像"]:
            post_content += f'<img src="{img_url}" alt="サンプル画像" />'

    post_content += f"<a href='{text_wname}'class='dl-btn'>無料で更に漫画『{title}』を試し読み！</a>"

    # カテゴリとグループのIDを取得
    category_id = create_or_get_category(scraped_info["作品形式"])
    company_id = create_or_get_group(scraped_info["出版社"])
    post_excerpt = scraped_info["作品内容"]
    print(f"'抜粋を作成しました'{post_excerpt}")
    default_cate = 408
    default_cate2 = 1291
    categories = [default_cate, default_cate2]
    if category_id:
        categories.append(category_id)
    # 投稿データの生成
    post_data = {
        "title": title,
        "content": post_content,
        "tags": tag_ids,
        "categories": categories,
        "company": [company_id] if company_id else [],  # group_idを投稿データに含める
        "status": "draft",
        "excerpt": post_excerpt,
    }

    # 投稿の作成
    response = requests.post(
        post_api_url, auth=(api_user, api_password), json=post_data
    )
    if response.status_code == 201:
        print("記事を投稿しました！")
    else:
        print("記事の投稿に失敗しました。エラーメッセージ:", response.text)


if __name__ == "__main__":
    existing_titles = get_existing_post_titles()
    driver = login_and_get_data(login_url, email, password)
    is_first_time = True
    links = ["https://www.dlsite.com/books/ranking/week"]
    for link in links:
        response = requests.get(link)
        soup = BeautifulSoup(response.text, "html.parser")
        dt_elements = soup.find_all("dt", class_="work_name")
        for dt in dt_elements:
            a_tag = dt.find("a")
            if a_tag:
                title = a_tag.text.strip()  # 作品のタイトルを取得
                print(title)
                work_url = a_tag.get("href")
                scraped_info = scrape_info(work_url)
                if scraped_info:
                    text_wname, text_main, href_url_wname, href_url_main = (
                        fetch_affiliate_data(driver, work_url, is_first_time)
                    )  # 修正: 4つの変数で受け取る
                    is_first_time = False
                    if text_wname and text_main and (href_url_wname or href_url_main):
                        print("テキスト WName:", text_wname)
                        print("テキスト Main:", text_main)
                        print(
                            "Href URL (WName):", href_url_wname
                        )  # text_wnameから抽出したURLを出力
                        print(
                            "Href URL (Main):", href_url_main
                        )  # text_mainから抽出したURLを出力
                        create_post(
                            title,
                            scraped_info,
                            existing_titles,
                            href_url_wname,
                            href_url_main,
                            driver,
                            scraped_info.get("sample_btn_html", ""),
                        )
    # 処理完了後、ブラウザを閉じます
    driver.quit()
