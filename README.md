# Welcome to ShowStopper
This is your one stop-shop for your streaming services. Users can search across a variety of platforms to help decide what they can watch and where they can watch it.

## Release Notes

<blockquote>
  <h2>v1.2.2 (4/23/2021)</h2>
  <h4>Enchancements:</h4>
  <ul>
    <li>Added a view for user's to view their activity history.</li>
    <li>Added ability to clear search history.</li>
    <li>Improved overall responsiveness on main user pages.</li>
  </ul>
  <h4>Bug Fixes:</h4>
  <ul>
    <li>Fixed issues with typos in search for mispelled titles.</li>
    <li>Fixed escape string issues on search input for certain titles.</li>
  </ul>
</blockquote>

### Known Bugs and Defects
<ul>
  <li>API requests are limited and tend to throttle at high usage.</li>
  <li>Ability to delete a user's account within the app is still not active.</li>
</ul>

## Install Guide
### Prerequisites
<ul>
  <li>Install and configure a MySQL server.</li>
  <li>Install and configure an Apache web server with PHP 7.4 installed (either locally or hosted within the cloud).</li>
  <li>Clone this git repository within the root directory of the web-server.</li>
  <li>Obtain an API key for the public facing OMDB API. <a href = "http://www.omdbapi.com/apikey.aspx">OMDB Key's</a></li>
</ul>
<h3>Download Instructions</h3>
<ul>
   <li>Download<a href = "https://www.apachefriends.org/download.html"> XAMPP </a>for a local web and MySQL server if you do not have a web server or database instance readily available.</li>
   <li>Clone this repository by using the following commands the terminal of your web server:</li>
</ul>
```bash
cd /path/to/web/server/root
git clone https://github.com/bacree3/showstopper.git
cd showstopper
```
### Dependencies
All dependencies for the application are included within the repository.

Some features of the application require the use of a proprietary API that is not public facing. If you wish to obtain the credentials please contact us, otherwise, you can deploy your own version of this API with the source code included within the 'scrape' directory.
### Build Instructions
This is a web application so no build is required. Simply run the PHP files with the proper database connection in any Apache Web Server with PHP 7.4 installed.
### Installation Instructions
### Run Instructions
### Troubleshooting
