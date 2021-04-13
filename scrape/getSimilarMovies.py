"""
**NOTE**
This file is hosted on AWS Lambda. You will not find any references to
"getSimilarMovies.py" anywhere else, instead its function is referenced when
needed using the AWS API.
********

This file contains the function that takes in the title of a movie, and performs
a serach for related movies on Google.com. The results of this search are
scraped, their full titles retrieved from a scrape of IMDb.com if needed,
and returned.

Requires the json, requests, time, and BeautifulSoup python libraries in order
to execute the HTTP requests and properly scrape the response.

Also requires the lxml python library to efficiently parse the pages returned
from HTTP requests.

@author Team 0306

@since 1.0
"""
import json
import requests
import time
import lxml
from bs4 import BeautifulSoup

"""
Function that removes punctuation from movie titles which can interfere with
constructing the IMDb search url.

@param  string   the_str Title of the movie to be searched
@return string   Title of the movie without punctuation
"""
def removePunct(the_str):
    punct = '''!()-[]{};:'"\,<>./?@#$%^&*_~'''
    for character in the_str:
        if character in punct:
            the_str = the_str.replace(character, "")
    return the_str

"""
Function that removes searches partial movie titles to find their full titles
through IMDb.com search. This is used rather than another Google search as
the HTTP pages returned from IMDb searches are relatively small, and the request
and scrape can be executed very quickly.

@param  string   title Partial title of the movie to be searched
@return string   Full title of the movie
"""
def searchIncomplete(title):
    moviestr = removePunct(title)
    words = moviestr.split(" ")
    search_url = "https://www.imdb.com/find?q="
    for i in range(len(words)):
        search_url = search_url + words[i]
        if i != len(words)-1:
            search_url = search_url+"+"
    page = requests.get(search_url)

    content = page.content
    soup = BeautifulSoup(content,"lxml")
    movieResults = soup.find('td', class_="result_text")
    fullTitle = movieResults.find('a')
    return fullTitle.text

"""
Function that recieves a request with the title of a particular movie as exists
in IMDb's OMDb database, uses it to construct the url of a Google search for
related movies, and retrieves the list of titles Google provides.

If titles are too long, Google may return some part of them followed by an
ellipsis "...". In this scenario, the searchIncomplete function is called
to retrieve full titles from IMDb.com.

@param  JSON     event    The json object containing title of movie
@param  Context  context  Object providing information on execution environment

@return JSON     Error or title list
"""
def lambda_handler(event, context):
    data = json.loads(event['body'])
    movieTitle = data['title']
    words = movieTitle.split(" ")

    search_url = "https://www.google.com/search?q=movies+like+"

    for i in range(len(words)):
        search_url = search_url + words[i]
        if i != len(words)-1:
            search_url = search_url+"+"

    page = requests.get(search_url)

    content = page.content
    # Only 1/5 of the page is scraped as the content returned by Google is very
    # large due to the number of actual search results returned. Because we are
    # only interested in the titles returned by Google, we only need the first
    # 1/8 of the page, but 1/5 provides room for error.
    soup = BeautifulSoup(content[0:len(content)//5],"lxml")

    try:
        textBoxes = soup.findAll('div', class_="BNeawe s3v9rd AP7Wnd")
        index = 0
        ls = []
        while "·" not in textBoxes[index].text and index < len(textBoxes):
            title = textBoxes[index].text
            if title[-3:] == "...":
                title = searchIncomplete(title[:-3])
            if title not in ls:
                ls.append(title)
            index = index + 1

        return {
            'statusCode': 200,
            'body': json.dumps(ls)
        }
    except:
        print("An exception occurred")
