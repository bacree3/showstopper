const readline = require('readline')
const Nightmare = require('nightmare')
const electron = require('electron')
// const electron_prebuilt = require('electron-prebuilt')
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


title = "the avengers 2012"

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
.wait("div.fl.ellip.oBrLN.S1gFKb.rOVRL")
.evaluate(() => {
    const containers = document.querySelectorAll("div.fl.ellip.oBrLN.S1gFKb.rOVRL")
    // const links = container.querySelectorAll("a")
    var num = 0
    var movies = []
    for (var i = 0; i < containers.length; i++) {
        // const aContainer = containers[i].querySelector("a")
        // name = aContainer.querySelector("div.fl.ellip.oBrLN.S1gFKb.rOVRL").textContent
        movies.push(containers[i].textContent)
    }
    return movies
})
.end()
.then(console.log)
.catch(error => {
console.error('Search failed:', error)
})

//do a wait for div.fl.ellip.oBrLN.SlgFKb.rOVRL
//idk how to do an if, maybe catch error timeout and that means the search
//doesnt have that section
