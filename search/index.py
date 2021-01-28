import requests
import time
import threading
import sys
import math
from bs4 import BeautifulSoup
from selenium import webdriver

link = "https://www.imdb.com/chart/top/"

response = requests.get(link)

html_doc = response.text

soup = BeautifulSoup(html_doc, "lxml")
res = soup.find(id="main")
titles = res.find_all("td", class_="titleColumn")

val = input("Which Movie (0 - 25)? ")

title = titles[int(val)]
txt = title.get_text()
x = txt.split(".")
txt = x[1]
txt = txt.strip()
index = txt.index("\n")
txt = txt[0:index].lower()
words = txt.split(" ")

search_url = "https://www.google.com/search?q="
for i in range(len(words)):
    search_url = search_url + words[i]
    if i != len(words)-1:
        search_url = search_url+"+"

print(search_url)

driver = webdriver.Chrome(".\\chromedriver\\chromedriver.exe")


# driver = webdriver.Chrome()
driver.maximize_window()
driver.get(search_url)

time.sleep(3)

content = driver.page_source.encode('utf-8').strip()
soup = BeautifulSoup(content,"html.parser")
officials = soup.findAll("div", {"class":"ellip bclEt"})

for entry in officials:
    print(entry.text)

# for r in titles:
#     trades_string = r.get_text()
#     print(trades_string)

