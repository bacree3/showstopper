# Welcome to ShowStopper
This is your one stop-shop for your streaming services. Users can search across a variety of platforms to help decide what they can watch and where they can watch it.

## Release Notes
>
>  ## v1.2.2 (4/23/2021)
>  #### Enchancements:
>  - Added a view for user's to view their activity history.
>  - Added ability to clear search history.
>  - Improved overall responsiveness on main user pages.
>  - Improved Filtering options on search.
>  ##### Bug Fixes:
>  - Fixed issues with typos in search for misspelled titles.
>  - Fixed escape string issues on search input for certain titles.
>

### Known Bugs and Defects
- API requests are limited and tend to throttle at high usage.
- Ability to delete a user's account within the app is still not active.
- Inconsistent notification experience.

## Install Guide
### Prerequisites
- Access to an AWS account. Create a free account: <a href = "https://aws.amazon.com/free/?trk=ps_a131L0000085DvcQAE&trkCampaign=acq_paid_search_brand&sc_channel=ps&sc_campaign=acquisition_US&sc_publisher=google&sc_category=core&sc_country=US&sc_geo=NAMER&sc_outcome=acq&sc_detail=aws%20free%20tier&sc_content=Account_e&sc_segment=432339156165&sc_medium=ACQ-P|PS-GO|Brand|Desktop|SU|AWS|Core|US|EN|Text&s_kwcid=AL!4422!3!432339156165!e!!g!!aws%20free%20tier&ef_id=Cj0KCQjw4ImEBhDFARIsAGOTMj8N81JmKn0X-FE5Axfk9u2xhRTMiYp-89ERUkrinHyb6Joyp7eE5JwaAgX0EALw_wcB:G:s&s_kwcid=AL!4422!3!432339156165!e!!g!!aws%20free%20tier&all-free-tier.sort-by=item.additionalFields.SortRank&all-free-tier.sort-order=asc&awsf.Free%20Tier%20Types=*all&awsf.Free%20Tier%20Categories=*all">AWS Free Tier</a>
- Install and configure a MySQL server.
- Install and configure an Apache web server with PHP 7.4 installed (either locally or hosted within the cloud).
- Clone this git repository within the root directory of the web-server.
- Obtain an API key for the public facing OMDB API. <a href = "http://www.omdbapi.com/apikey.aspx">OMDB Key's</a>
### Download Instructions
- Download<a href = "https://www.apachefriends.org/download.html"> XAMPP </a>for a local web and MySQL server if you do not have a web server or database instance readily available.
- Clone this repository by using the following commands the terminal of your web server:
```bash
cd /path/to/web/server/root
git clone https://github.com/bacree3/showstopper.git
cd showstopper
```
1. Edit the document root of the web server to point to the cloned repository by editing the httpd.conf file for your webserver.
2. Running the following command will allow you to edit the file within the terminal of Linux based web servers.
```bash
sudo nano /etc/httpd/conf/httpd.conf
```
3. Find the 'DocumentRoot' variable and added '/showstopper' to the end of the path.
4. If you are using XAMPP, edit the file by going to the settings of the Apache web server and opening the httpd.conf file.
### Dependencies
All dependencies for the application are included within the repository.

Some features of the application require the use of a proprietary API that is not public facing. If you wish to obtain the credentials please contact us, otherwise, you can deploy your own version of this API with the source code included within the 'scrape' directory.
### Build Instructions
This is a web application so no build is required. Simply run the PHP files with the proper database connection in any Apache Web Server with PHP 7.4 installed by going to the address of the web server in your preferred browser.
### Installation Instructions
1. Run the 'database/data_structure.sql' file inside your database to set up the proper tables for the database.
2. If you acquired our API keys, simply move on to the next step and replace the proper values.
3. Once you have cloned the repository within the root of the web server, run the following command to configure the application based on your MySQL server configuration and your AWS SES credentials by replacing the variables prexied with 'YOUR' with the proper values (you can also just duplicate and edit the file with the proper name in your favorite text editor):
```bash
cp php/parameters-example.php php/parameters.php
nano php/parameters.php
```
3. If you are deploying your own API rather than acquiring the keys for our private API, create 4 Lambda functions inside your AWS account, with names corresponding to the names of the file EXCLUDING the file extensions, and attach the necessary layers provided in the 'scrape' directory.
4. Create and attach an API gateway to point to your functions, and use this URL within the new parameters file.
5. Repeat step 2 with the new credentials you created.
### Run Instructions
Navigate to the web address of the web server you configured. For example, there is a live version of the application on <a href = "https://showstopper.app">ShowStopper.app</a>, or if you configured a local web server, try <a href = "http://localhost">localhost</a>, or <a href = "http://127.0.0.1">127.0.0.1</a>.
### Troubleshooting
1. Ensure the PHP version is correct.
2. Ensure your web server is running properly (be sure to hit start if you are using XAMPP, you may need to restart the server if you edited the httpd.conf file while it was running.)
3. Ensure file structure is correct by confirming the repository has been specified in the document root of the web server.
4. Ensure the connection to the database is correct (be sure to make sure the port is open to reach the database if you launched the database in the cloud).
5. Ensure the account you created to access the database instance has the proper credentials to view and edit the schema you created from data_structure.sql.
6. Ensure sure you created the proper parameters file from the example one provided within the php directory and that the values you replaced are correct.
