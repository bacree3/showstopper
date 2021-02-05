import requests
import time
import threading
import sys
import math
from bs4 import BeautifulSoup
from selenium import webdriver

##################### TEMPORARY #######################
# This accesses imdb's list of the 250 top rated movies
# of all time and pulls the titles. Just for testing
# purposes, the user is asked to choose one of the first
# 26 movies to search for.
#
# This is only for the purpose of developing the google
# search functionality, the real app will be based on user
# imputs for titles.

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
yearIndex = txt.index("(")
year = txt[yearIndex+1:-1]
txt = txt[0:index].lower()
words = txt.split(" ")
words.append(year)

#######################################################

"""
Primary Function
----------------
Takes in words (movie title) and builds a search
query to send to google. Then uses a headless (UI-less)
browser through selenium webDriver to perform the search.

Returns a list of objects with the name and links to
streaming services where the movie is available.

"""

search_url = "https://www.google.com/search?q="
for i in range(len(words)):
    search_url = search_url + words[i]
    if i != len(words)-1:
        search_url = search_url+"+"

print(search_url)

options = webdriver.ChromeOptions()
options.add_argument('headless')

driver = webdriver.Chrome(".\\chromedriver\\chromedriver.exe", options=options)

driver.get(search_url)

time.sleep(1)

content = driver.page_source.encode('utf-8').strip()
soup = BeautifulSoup(content,"html.parser")
platforms = soup.findAll("div", {"class":"ellip bclEt"})
linkDiv = soup.findAll('div', jsname='gI9xcc')
links = linkDiv[0].findAll('a')

ls = []

indeces = len(platforms)
if len(platforms) > len(links):
    indeces = len(links)

for i in range(indeces):
    ls.append({"name": platforms[i].text, "link": links[i].get('href')})

print(ls)

