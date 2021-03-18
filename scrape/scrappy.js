const readline = require('readline')
const Nightmare = require('nightmare')
const electron = require('electron')
// const electron_prebuilt = require('electron-prebuilt')
const nightmare = Nightmare({ show: true })

/**
Primary Function
----------------
Takes in user input (movie title) and builds a search
query to send to google. Then uses a headless (UI-less) Chromium
browser using the Nightmare library to perform the search.

Returns a list of objects with the name and links to
streaming services where the movie is available.
**/


// title = "the avengers"
title = "ant-man"
str = title.replace(/[^\w\s]/g, " ");

console.log(str);

var titleWords = str.split(" ");
// for (var i = 0; i < titties.length; i++) {
//   if (titties[i].length != 0) {
//     console.log(titties[i]);
//   }

// }

// titleWords = title.split(" ")
var searchURL = 'https://www.google.com/search?q='

for (var i = 0; i < titleWords.length; i++) {
  if (titleWords[i].length > 0) {
    // titleWord = titleWords[i][0].toUpperCase() + titleWords[i].substr(1);
    searchURL = searchURL + titleWords[i];
    if (i != titleWords.length - 1) {
      searchURL = searchURL + "+"
    }
  }

}

searchURL = searchURL + "+movie"

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

//do a wait for div.fl.ellip.oBrLN.SlgFKb.rOVRL
//idk how to do an if, maybe catch error timeout and that means the search
//doesnt have that section



