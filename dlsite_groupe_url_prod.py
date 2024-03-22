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

if __name__ == "__main__":
    driver = login_and_get_data(login_url, email, password)
    is_first_time = True
    links = ["https://www.dlsite.com/maniax/circle/profile/=/maker_id/RG01013936.html",
             "https://www.dlsite.com/maniax/circle/profile/=/maker_id/RG49949.html",
             "https://www.dlsite.com/maniax/circle/profile/=/maker_id/RG01012594.html",
             "https://www.dlsite.com/maniax/circle/profile/=/maker_id/RG31931.html",
             "https://www.dlsite.com/maniax/circle/profile/=/maker_id/RG06917.html",            
             ]
    titles = []  # タイトルを格納するリスト
    hrefs = []  # URLを格納するリスト
    for link in links:
        response = requests.get(link)
        soup = BeautifulSoup(response.text, "html.parser")
        dt_elements = soup.find_all("div", class_="multiline_truncate")
        for dt in dt_elements:
            a_tag = dt.find("a")
            if a_tag:
                titles.append(f"\"{a_tag.text.strip()}\",")  # タイトルをリストに追加
                hrefs.append(f"\"{a_tag.get('href').strip()}\",")  # URLをリストに追加
    # タイトルを一括で出力
    for title in titles:
        print(title)
    # URLを一括で出力
    for href in hrefs:
        print(href)
    driver.quit()