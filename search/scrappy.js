const readline = require('readline')
const Nightmare = require('nightmare')
const electron = require('electron-prebuilt')
const nightmare = Nightmare({ show: false })

/**
Primary Function
----------------
Takes in user input (movie title) and builds a search
query to send to google. Then uses a headless (UI-less) Chromium
browser using the Nightmare library to perform the search.

Returns a list of objects with the name and links to
streaming services where the movie is available.
**/

var RL = readline.createInterface(process.stdin, process.stdout);
RL.question('What movie would you like results for? \n', (title)=>{

     title = title.toLowerCase()
     titleWords = title.split(" ")
     var searchURL = 'https://www.google.com/search?q='

     for (var i = 0; i < titleWords.length; i++) {
        searchURL = searchURL + titleWords[i]
        if (i != titleWords.length - 1) {
          searchURL = searchURL + "+"
        }
     }
     console.log(searchURL)

     nightmare
    .goto(searchURL)
    .wait("div.ellip.bclEt")
    .evaluate(() => {
          const container = document.querySelector("div[jsname='gI9xcc']")
          const links = container.querySelectorAll("a")
          var num = 0
          var platforms = []
          for (var i = 0; i < links.length; i++) {
              if (links[i].href != null) {
                  num = num + 1
                  var link = links[i].href
                  var name = links[i].querySelector("div.ellip.bclEt").textContent
                  var str = "{ name: " + name + ", link: " + link + " }"
                  platforms.push(str)
              }
          }
          return platforms
      })
    .end()
    .then(console.log)
    .catch(error => {
      console.error('Search failed:', error)
    })

 });

