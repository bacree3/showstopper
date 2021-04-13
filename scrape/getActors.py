"""
**NOTE**
This file is hosted on AWS Lambda. You will not find any references to
"getActors.py" anywhere else, instead its function is referenced when
needed using the AWS API.
********

This file contains the function that scrapes the cast list from the a movie's
IMDb page.

Requires the json, requests, and BeautifulSoup python libraries in order
to execute the HTTP request and properly scrape the response.

@author Team 0306

@since 1.0
"""
import json
import requests
from bs4 import BeautifulSoup

"""
Function that recieves a request with the id of a particular movie as exists
in IMDb's OMDb database, uses it to construct the url of that movie's page, and
scrapes the page for the full cast list.

@param  JSON     event    The json object containing id of movie
@param  Context  context  Object providing information on execution environment

@return JSON     Error or cast list
"""
def lambda_handler(event, context):

    data = json.loads(event['body'])
    movieID = data['id']

    search_url = "https://www.imdb.com/title/" + movieID

    page = requests.get(search_url)

    content = page.content
    soup = BeautifulSoup(content,"html.parser")
    actorsTable = soup.find("table", class_="cast_list")

    try:
        actors = actorsTable.findAll('td', class_=lambda x: x!="primary_photo" and x!="ellipsis" and x!="character")
        ls = []
        for i in range(len(actors)):
            name = actors[i].find('a')
            if name is not None:
                ls.append(name.text[1:-1])
        return {
            'statusCode': 200,
            'body': json.dumps(ls)
        }
    except:
        print("An exception occurred")
