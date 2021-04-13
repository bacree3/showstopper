/**
 * **NOTE**
 * This file is hosted on AWS Lambda. You will not find any references to
 * "getPlatforms.js" anywhere else, instead its function is referenced when
 * needed using the AWS API.
 * ********
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

/**
* Function recieves a request with the name of a movie to search for,
* runs a headless browser to reach the desired page, and retrieves the desired
* information.
*
* @param  JSON     event    The json object containing title of movie
* @param  Context  context  Object providing information on execution environment
* @param  function callback The response containing either an error message or
*                           list of platforms
* @return   JSON            Error or platform list
*/
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
    var json = JSON.parse(event.body);
    var title = json.title;

    title = title.replace(/[^\w\s]/g, " ");

    var titleWords = title.split(" ");
    var searchURL = 'https://www.google.com/search?q=';

    for (var i = 0; i < titleWords.length; i++) {
      if (titleWords[i].length > 0) {
        searchURL = searchURL + titleWords[i];
        if (i != titleWords.length - 1) {
          searchURL = searchURL + "+";
        }
      }

    }

    const page = await browser.newPage();

    await page.goto(searchURL);

    await page.waitForSelector('div.ellip.bclEt');

    const platformsList = await page.evaluate(function () {
        let container = document.querySelector("div[jsname='gI9xcc']")
        const links = container.querySelectorAll("a")
        var num = 0
        var platforms = []
        for (var i = 0; i < links.length; i++) {
            if (links[i].href != null) {
                num = num + 1
                var link = links[i].href
                var name = links[i].querySelector("div.ellip.bclEt").textContent
                var str = {id: i, name: name, link: link};
                platforms.push(str)
            }
        }
        return platforms;
    })

    result = JSON.stringify(platformsList)

  } catch (error) {
    return callback(error);
  } finally {
    if (browser !== null) {
      await browser.close();
    }
  }

  return callback(null, result);
};
