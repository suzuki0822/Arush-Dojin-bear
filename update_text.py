import requests

# 認証情報
username = 'suzuki'
application_password = 'U1EA atcm azSG McSY oQXp B3WZ'

# WordPressのサイトURL
base_url = "https://dojin-bear.net/wp-json/wp/v2"

# すべての記事を取得する
def get_all_posts():
    all_posts = []
    page = 1
    while True:
        response = requests.get(f"{base_url}/posts?per_page=100&page={page}&status=any", auth=(username, application_password))
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
    data = {'content': new_content}
    update_response = requests.post(f"{base_url}/posts/{post_id}", auth=(username, application_password), json=data)
    print(f"Updating Post {post_id} response code: {update_response.status_code}")
    return update_response.status_code == 200

# 特定のパターンに基づいて記事の内容を変更する
def modify_and_update_posts():
    posts = get_all_posts()
    if posts:
        for post in posts:
            content = post['content']['rendered']
            title = post['title']['rendered']
            # "の作品内容"が完全一致で存在するか確認し、存在する場合は更新をスキップ
            if '』の作品内容:' in content:
                print(f"Post {post['id']} contains '』の作品内容:'. Skipping update.")
                continue
            new_content = content.replace('作品内容:', f'漫画『{title}』の作品内容:')
            if '詳しくはこちら！' in new_content:
                new_content = new_content.replace('詳しくはこちら！', f'無料で更に漫画『{title}』を試し読み！')
            if '作品内容:' in content and '</blockquote>' in new_content:
                new_content = new_content.replace('</blockquote>', f'<p> 今すぐ漫画『{title}』を無料で試し読み！</p></blockquote>')
            # 更新された内容で記事を更新する
            if update_post_content(post['id'], new_content):
                print(f"Post {post['id']} updated.")
            else:
                print(f"Post {post['id']} not updated or not found.")

# 実行
modify_and_update_posts()
