/**
 * **NOTE**
 * This file is hosted on AWS Lambda. You will not find any references to
 * "getRelatedTitles.js" anywhere else, instead its function is referenced when
 * needed using the AWS API.
 * ********
 *
 * This file requires the Lambda layer 'puppeteerLayer'
 *
 * This file contains the function that constructs, executes, and scrapes the
 * results of a Google search to find the platforms on which a particular movie
 * is currently available.
 *
 ** Requires 'chrome-aws-lambda' a NodeJS dependency that includes the binary
 ** for a headless browser (chromium) designed to run in AWS Lambda
 *
 * @author Team 0306
 *
 * @since 1.0
 */

var chromium = require('chrome-aws-lambda');

exports.handler = async function(event, context, callback){
  let result = 'result';
  let browser;

  try {
    browser = await chromium.puppeteer.launch({
      args: chromium.args,
      defaultViewport: chromium.defaultViewport,
      executablePath: await chromium.executablePath,
      headless: chromium.headless,
      ignoreHTTPSErrors: true,
    });
    if (!event) {
        return Promise.resolve();
    }
    // var json = JSON.parse(event.body);
    // console.log(json);
    // var title = json.title;
    var title = event.title;

    var titleWords = title.split(" ");
    var searchURL = 'https://www.google.com/search?q=';

    for (var i = 0; i < titleWords.length; i++) {
      searchURL = searchURL + titleWords[i];
      if (i != titleWords.length - 1) {
        searchURL = searchURL + "+";
      }
    }
    console.log(searchURL);

    const page = await browser.newPage();

    // console.log(`Navigating to ${this.url}...`);
    await page.goto(searchURL);

    await page.waitForSelector('div.fl.ellip.oBrLN.S1gFKb.rOVRL');

    const movieList = await page.evaluate(function () {
        const containers = document.querySelectorAll("div.fl.ellip.oBrLN.S1gFKb.rOVRL")
        var num = 0
        var movies = []
        for (var i = 0; i < containers.length; i++) {
            var title = containers[i].textContent
            // var movie = {id: i, title: title};
            // movies.push(movie)
            movies.push(title)
        }
        return movies
    })

    console.log(movieList)
    result = JSON.stringify(movieList)

  } catch (error) {
    return callback(error);
  } finally {
    if (browser !== null) {
      await browser.close();
    }
  }

  return callback(null, result);
};
