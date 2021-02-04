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
# print(txt)
x = txt.split(".")
txt = x[1]
txt = txt.strip()
index = txt.index("\n")
yearIndex = txt.index("(")
year = txt[yearIndex+1:-1]
# print(year)
txt = txt[0:index].lower()
words = txt.split(" ")
words.append(year)

# search_url = ""
# # search_url = "https://www.reelgood.com/movie/"
# for i in range(len(words)):
#     search_url = search_url + words[i]
#     if i != len(words)-1:
#         search_url = search_url+"+"

# print(search_url)

# response = requests.get(search_url)

# time.sleep(2)

# html_doc = response.text

# f = open("thatswhatsup.txt", "w")
# f.write(html_doc)
# f.close()

# print(html_doc)

# soup = BeautifulSoup(html_doc,"lxml")
# officials = soup.findAll("span", class_="css-3g9tm3 e1udhou113")
# print(len(officials))

# for entry in officials:
#     print(entry.text)

############################################################
search_url = "https://www.google.com/search?q="
for i in range(len(words)):
    search_url = search_url + words[i]
    if i != len(words)-1:
        search_url = search_url+"+"

print(search_url)

options = webdriver.ChromeOptions()
options.add_argument('headless')

driver = webdriver.Chrome(".\\chromedriver\\chromedriver.exe", options=options)



# driver = webdriver.Chrome()
# driver.maximize_window()
driver.get(search_url)

time.sleep(1)

content = driver.page_source.encode('utf-8').strip()
soup = BeautifulSoup(content,"html.parser")
platforms = soup.findAll("div", {"class":"ellip bclEt"})
linkDiv = soup.findAll('div', jsname='gI9xcc')
links = linkDiv[0].findAll('a')

# x = testststs.find('<a')
# firstA = testststs[x:]
# hrefS = firstA.find('href')
# hrefE = firstA[hrefS:].find('data')
# print(firstA[hrefS:hrefE])

# print(len(testststs))
# print(len(links))
ls = []
for i in range(len(platforms)):
    ls.append({"name": platforms[i].text, "link": links[i].get('href')})

print(ls)

# for entry in platforms:
#     print(entry.text)
# for link in links:
#     print(link.get('href'))
    # print(entry.href)

# # for r in titles:
#     trades_string = r.get_text()
#     print(trades_string)
###################################################################
