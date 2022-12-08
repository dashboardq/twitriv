# TwitRiv

An open source, MIT licensed Twitter tool built with a custom PHP MVC backend framework and [TACE](https://www.agraddy.com/introducing-tace) for front end development. It focuses on giving you the tools you need to filter what you see and quickly find, interact, and communicate with friends and followers. If you would like to use the hosted version, it is available at [TwitRiv - Take Control of Twitter](https://twitriv.com).

![TwitRiv Screenshot](https://raw.githubusercontent.com/dashboardq/twitriv/main/public/assets/images/share_1200x630.png)

## Who
TwitRiv is created by [Anthony Graddy](https://www.agraddy.com) as a part of the [12 Startups in 12 Months (Open Source Edition) challenge](https://www.agraddy.com/12-startups-in-12-months-open-source-edition). This is the 2nd startup of the challenge. You can find all the details on how to install this tool on your own server below in the [How](#how) section.

If you have some questions and want to skip all this, there is an [FAQ](#faq) at the bottom.


## What

TwitRiv **is** a [TACE based](https://www.agraddy.com/introducing-tace) web app built using a custom PHP MVC framework that provides a simple interface to view tweets and users your follow. It is inspired by [Nitter](https://github.com/zedeus/nitter) but uses the official Twitter API and allows you to login.

TwitRiv **is not** a tweet scheduling tool, tweet inspiration tool, or a full replacement for Twitter (tweet scheduling may come sometime in the future). There are limitations to the official Twitter API that limits what functionality is available to build. The idea for TwitRiv is that it provides a clean, easy to user interface to quickly catch up and interact with other users.

## When

TwitRiv is available now but it has been recently released and should be considered an early beta version. If you would like to request additional feature, please login and submit a  new ticket in the [DashboardQ Support](https://www.dashboardq.com/support) system. Note that paying customers will receive higher priority.

## Where

TwitRiv is open source and can be installed on any server that supports modern PHP frameworks. The PHP used is very basic and should work with web hosts that support PHP 7.3 and above. If you are not using encryption to store the Twitter credentials in the database, it will probably work with older versions of PHP, but I'm not testing or guaranteeing anything for older versions of PHP.

## Why

I find the Twitter interface very frustrating to use. It is slow, is always trying to show other trending information that I'm not interested in, does not work great on mobile devices when just using the website. I always prefer to navigate Twitter using Nitter but I wanted to have a way to login to my account to have more control. I couldn't find a service that did what I wanted so I decided to build it myself.

## How

I prefer documentation that has at least one clear step-by-step guide of how to get things working. I come across so much documentation that leave out valuable/important steps. My goal is that the documentation is so simple, a 10 year old can install it following the steps. If you feel anything is lacking, please let me know (you can contact me anywhere I have an account - if for some reason I don't respond, try a different platform where I have an account).

I've provided two installation versions below: Experienced Developer and No Experience. Please pick the one that is right for you (or combine the insights of the two).

### Experienced Developer

These instructions will install TwitRiv on your local computer. The instructions are based on a Unix command line installation but should be easy to apply to other systems.

Required items:
* PHP
* MySQL
* Git
* [Twitter OAuth2 Application Keys](https://developer.twitter.com/en/portal/dashboard) - you'll need to set the App Info Callback URI / Redirect URL in your Twitter App settings to be https://EXAMPLE.COM/twitter/redirect (you need to change "EXAMPLE.COM")

Not required:
* There are no dependencies so composer is not needed for this install.

```
# Get started (put it anywhere on your system, I'm putting it in the home directory)
cd ~

# Clone the repo
git clone https://github.com/dashboardq/twitriv.git twitriv
cd twitriv

# Set up MySQL database
mysql -u root -p --vertical
mysql> CREATE DATABASE twitriv;
mysql> exit;

# Set up the environment config values (enter the appropriate MySQL data)
# If you are developing, use "dev" for the "APP_ENV" environment variable, 
# if you are putting this on a publicly available website, use the "prod" value.
cp .example.env.php .env.php

# Edit the .env.php file.
# Make sure you update: APP_HOST, APP_SITE, DB_NAME, DB_USER, and DB_PASS along with the Twitter application values.
vim .env.php

# Generate the encryption keys
php ao gen keys

# Run the migrations
php ao mig init
php ao mig up

# Serve the site locally
cd public
php -S localhost:8080

# Visit the site in your browser:
# http://localhost:8080

# You should be seeing the TwitRiv home page when you visit it in your browser. 
# On the login page, you should be able to register an account.
```

### No Experience

I'm going to be listing out steps on how to install TwitRiv using [Cloudways](https://www.cloudways.com/en/?id=1100140) so I guess that 10 year old is going to need to find a credit card (the Cloudways link is an affiliate link). The reason I am using Cloudways as an example is because they have a simple, easy to use platform that makes setting up an affordable VPS very simple. Unfortunately, they only support PHP so for any other hosting needs, you will need to use a different host.

I'm going to start off with some introduction text written for intermediate users. Don't worry if the introduction doesn't make sense to you. The most important part to understand is step 1. If the steps themselves don't make sense, please let me know.

#### Let's Get Started

TwitRiv code is written using a custom PHP framework. The framework is in an early alpha status (there are currently no tests and this is basically the initial draft you are viewing - eventually the goal is to separate out the framework as a separate system). The framework has a similar structure to [Laravel](https://github.com/laravel/laravel), meaning it assumes the majority of the files are not in the public directory and accessible from the web. If your web host supports Laravel, then it should be able to host TwitRiv.

Note that the setup instructions use SFTP. If you are not familiar with SFTP, it stands for "Secure File Transfer Protocol." It is an agreed upon standard on how to move files between computers. There are numerous free and paid SFTP apps available; I'll be mentioning some free ones below. 

We are going to be uploading files from our computer to our server using SFTP. Note that this is an old school way of setting up a site. If you are a 10 year old, you are probably going to get bullied by the cool kids who have moved on to more "sophisticated" methods of "app deployment."

The main points are in bold. The other text is additional context if the bold text doesn't make sense. If you are skimming, just look at the bold points.

1\. First we are going to get our tools installed. **Install a text editor.**
  * This step is going to depend on what operating system you are using. 
  * If you are using **Linux**, you probably already have a text editor and know which one you prefer. 
    * If you don't have a text editor preference, maybe try **Gedit** (it may already be installed).
  * If you are using a **Mac**, I would recommend installing **[CotEditor](https://coteditor.com/)** - I haven't used it myself so don't put a lot of weight into this this recommendation.
  * If you are using **Windows**, I would recommend installing **[Notepad++](https://notepad-plus-plus.org/downloads/)** - choose the latest version (don't worry about the names of each one).
  * If you want to really dive in deep, **learn [Vim](https://www.vim.org/)**. It is a text editor available on every operating system for free. It has a very steep learning curve, but once you understand it, you will never want to use anything else.
  * If anybody tells you to learn **Emacs, don't do it**, they are trying to play a prank on you. (yes, this line is a joke, but it was written with Vim) (Vim is not responsible for this lame joke and will not affect your humor) (now I'm going to have to explain the joke because if you are 10 years old, you won't understand - Vim and Emacs have been around for decades. Both editors have built up a loyal following, so much so that when one or the other is brought up online, it often results in people spending hours of their life arguing about which one is better, it also causes them to spend way too much time writing about this in README files, but thankfully it can be written quickly if you use Vim).
    * Vim is Charityware (meaning it is free but the creator asks that you donate to a charity they are associated with so if you can - **go donate money to the [International Child Care Fund charity](https://iccf-holland.org/)**).

2\. **Install an SFTP program.**
  * For years I used to enthusiastically recommended that people should **install [FileZilla Client](https://filezilla-project.org/download.php?type=client)** if they needed an SFTP program but I am no longer enthusiastic about the recommendation. It tries to install additional software if you are not paying attention during installation. **Do not install additional software, only install the basic client.**
    * I highly recommend that you **read this article first** to make sure you don't end up with additional junk on your system: [How to Safely Download and Cleanly Install FileZilla FTP Software (with no additional junk)](https://medium.com/web-design-web-developer-magazine/how-to-safely-download-and-cleanly-install-filezilla-ftp-software-with-no-additional-junk-10b27a2d270d).
    * Follow the steps in the article above to install FileZilla. Once you have it installed, there shouldn't be any other issues with the "additional junk."
  * If my warnings above make you wary of using FileZilla, here are some possible alternatives you can check out:
    * [Cyberduck](https://cyberduck.io/) - Available on Linux, Mac, and Windows. Can connect to more services but may not be as easy to use.
    * [WinSCP](https://winscp.net/eng/index.php) - Available on Windows. I have not used it, but it seems to be very similar to FileZilla.
  * I'm not aware of a solid, cross platform open source SFTP progam that I feel comfortable recommending. If you know of one I'm missing, let me know.

3\. **Create a [Cloudways account](https://www.cloudways.com/en/?id=1100140).** 
  * Go to the pricing page. 
  * Make sure "DigitalOcean is selected (DigitalOcean is the default).
  * Change the Slider from "Premium" to "Standard" (Premium is the default)
  * Pick the cheapest plan, it should say "$10" (at least it does at the time of writing this document). It has the option to "Start Free."
  * It will take you to a sign up page. If you need help figuring out the sign up page, let me know.

4\. **Set up the server.**
  * When setting up the server, Cloudways is going to ask what application you want to install. **Choose "Laravel" for the app** (at the time of this writing, the option was Laravel 8.26.1 which will change in the future). We are not going to use Laravel, but TwitRiv has a similar application structure.
  * It is going to ask you for some additional information for your application. Feel free to use whatever you want, but I would recommend that you **use the info below to set up the app**:
    * Name Your Managed App: TwitRiv
    * Name Your Managed Server: TwitRiv
    * Name Your Project: TwitRiv
  * It will ask you for the location of the server. I would recommend that you **pick a location near you for the server location**.
  * Once you've entered everything, **press "Launch Server."**
  * It may take a little time to set up, so go outside and run around or something.

5\. **Understand the server details.**
  * Once the server and application are set up, you should be able to **click on the "www" icon** to open up the details about the Laravel application.
  * You should now be on a page where you can **look at the "Application Management" section** and be on the "Access Details" page.
  * You will see a default URL that Cloudways provides you. Setting up a custom domain is outside the scope of the installation instructions, but it is very easy - please refer to Cloudways documentation if you want to use a custom domain: [How Do I Take My Website Live from Cloudways?](https://support.cloudways.com/en/articles/4805075-how-do-i-take-my-website-live-from-cloudways)
  * When you **click on the Application URL**, it should be a web page that says welcome to Laravel.

6\. **Gather server information for access and your app settings**
  * For each of the items below, save this information for later use. You can save it to a text file or just write it down on a piece of paper. Some of the information should be private, so make sure you don't share it with others.
  * **Find the URL** for your app. This will be located at the following location in your Cloudways admin panel: Applications > [App Name You Created] > Access Details: APPLICATION URL
  * On that same page, you want to save the following items (clicking on them will copy them to your clipboard):
    * **DB Name**
    * **Username**
    * **Password**
  * Get the Master credentials for your server. You can find them at the following location in your Cloudways admin panel: Server > [Server Name You Created] > Master Credentials
  * On the Master Credentials page, save the following items (clicking on them will copy them to your clipboard):
    * **Public IP**
    * **Username**
    * **Password**

7\. **Create a [Twitter App](https://developer.twitter.com/en/portal/dashboard)**
  * You will need to create a Twitter App that has "App Permissions" set to "Read and write and Direct message".
  * You don't need "Request email from users" to be enabled. 
  * For the "Type of App", you will want to choose "Web App, Automated App or Bot" as the setting.
  * For the "App Info Callback URI / Redirect URL" you will want to enter something like this URL (where EXAMPLE.COM is changed to match your default URL from Cloudways: https://EXAMPLE.COM/twitter/redirect
    * Note that if you don't have an SSL certificate, you will need the URL to start with "http://" instead of "https://".
  * Keep a note of the "OAuth 2.0 Client ID and Client Secret" - these are important.

7\. **Download the source code for TwitRiv**
  * Go to this link, it should automatically start the download process: [TwitRiv Zip](https://github.com/dashboardq/twitriv/archive/refs/heads/main.zip).

8\. **Unzip the twitriv-main.zip file you just downloaded**
  * If you are not sure how to unzip a file, try right clicking the file and look for an option to unzip. If that is not available, try searching for instructions to unzip a file online. Different computer operating systems have different ways to unzip a file.

9\. **Create and open the setting file**
  * Usually you would edit this file on the server itself, but I want to make this guide as simple as possible.
  * Copy the `.example.env.php` file to `.env.php` 
  * Open the `.env.php` file using your text editor.
  * Note that the file is considered a "hidden file" because it starts with a period. If you cannot see the example files or the new `.env.php` file, you may need to update your directory viewer settings to show hidden files.

10\. **Modify the values in the .env.php file**
  * Using the information you saved in step 6, modify the file as needed:
    * `APP_ENV` - this should be set to `prod` if strangers will have access. If you are just testing on your own, you can leave this set to `dev` to see any errors.
    * `APP_HOST` - this should be the domain name without the "http" prefix. If you are going to use the Cloudways default URL, it may be something like: `phplaravel-123456-7654321.cloudwaysapps.com`
      * If you are using a custom domain, then it may be something like `example.com`.
    * `APP_SITE` - this is like the host but it includes the "http" prefix. It does not end with a slash. So it might look like: `http://phplaravel-123456-7654321.cloudwaysapps.com` or `https://example.com`
    * `APP_AUTHOR` - the author values are used on the Terms and Privacy pages.
    * `DB_USE`, `DB_INSTALL` - This ensures that the database connection is used and install ensures that the initial database migrations are ran. Leave these both set to `true`. If you are mixing steps from the "Experienced Developer" instructions and using the command line, you will want install set to `false`.
    * `DB_NAME`, `DB_USER`, `DB_PASS` - Each of the db values should match the DB values you saved in step 6.
    * `EMAIL_ADMIN`, `EMAIL_FROM` - If you want to receive email notification (`EMAIL_ADMIN`) or send emails (`EMAIL_FROM`), then these values should be set to your email for admin notifications and the email address that should be used to send emails.
      * In order to send emails using your Cloudways server, you need to set up a 3rd party email sender. You can learn more at the [Cloudways Custom SMTP Instructions](https://support.cloudways.com/en/articles/5130857-how-to-activate-the-custom-smtp-add-on) documentation.
    * `TWITTER_URL_REDIRECT` - This should be the redirect URL you entered in your Twitter App settings mentioned above: https://EXAMPLE.COM/twitter/redirect
    * `TWITTER_CLIENT_ID` - This should be the "OAuth 2.0 Client ID" saved above when creating your Twitter App.
    * `TWITTER_CLIENT_SECRET` - This should be the "OAuth 2.0 Client Secret" saved above when creating your Twitter App.

11\. **Download the keys file.**
  * The Twitter connection settings in the database are encrypted. In order to encrypt the data, unique encryption keys are needed. TwitRiv has a built in key file encryption generation system.
  * Go to the following  URL and download the file by following the instructions listed there: [https://twitriv.com/generate-keys-file](https://twitriv.com/generate-keys-file)

12\. **Connect to Cloudways using SFTP.**
  * Using your SFTP program and the Cloudways master credentials you saved in step 6, you want to connect to your Cloudways server.
  * If you are using FileZilla, Cloudways has some instructions on how to connect to the server located here: [Guide to Connecting to Your Application Using SSH/SFTP](https://support.cloudways.com/en/articles/5119485-guide-to-connecting-to-your-application-using-ssh-sftp#h_274b4a0b69)
    * The link should take you directly to the FileZilla section. You can ignore any of the details about SSH and Putty.
  * Once you are logged in with your master credentials, you will need to navigate to the application directory. If you are using FileZilla, this means on the right side, you want to go to: applications > [Your Application Code Name - this usually matches your DB Name] > public_html
  * Once you are in the public_html directory, you should see the default Laravel code and directories.

13\. **Using SFTP, delete any of the default Laravel code that was generated by Cloudways.**
  * You should be in the public_html directory, and there should be files and directories with names like: `README.md`, `app`, `artisan`, `bootstrap`, `composer.json`, `composer.lock`, `config`, `database`, `package.json`, `phpunit.xml`, `public`, `resources`, `routes`, `server.php`, `storage`, `tests`, `vendor`, `webpack.mix.js`
  * **Except for `public`, select each of the items and delete them** (if you can - some may not delete due to permissions). You delete by right clicking a file or directory and selecting "Delete" from the menu.

14\. **Using SFTP, upload TwitRiv to your Cloudways server.**
  * You should be in the public_html directory.
  * **Make sure you can see hidden files**, if you are using FileZilla turn on this option: Server > Force showing hidden files
  * If your SFTP program has a left side and right side pane (using other types of SFTP programs will be slightly different):
    * On the left side, navigate to the directory where you unzipped the twitriv-main.zip file containing the TwitRiv code including the `.env.php` file you edited in step 10.
    * On the right side, you should be in the Cloudways public_html directory.
  * Upload all the files from the left side to the right side by selecting them all and dragging them to the right side.

15\. **Open the URL with your browser** that Cloudways gave you in the access details section of the Cloudways portal or if you are using a custom domain, use that.
  * When you first open the page, TwitRiv needs to set up the database tables. If you have `DB_INSTALL` set to `true` from step 10, then the first time you load the page, the database tables will be set up.
  * After setting up the database tables, it should then show you the home page of TwitRiv.

16\. **Create an account on the login page** 
  * Visit the URL again in your browser for the new TwitRiv site.
  * Go to the login page and register a new user. 

17\. **Connect to Twitter**
  * You should be notified that you need to connect to Twitter. Go through the process of connecting your Twitter account.
  * Congratulations! You should now have TwitRiv fully installed on your server. [TwitRiv.com](https://twitriv.com) has a free plan so you can compare the functionality of the free plan with the functionailty on the installation on your Cloudways server.


## FAQ

### Why aren't you using composer or other libraries?

My experience over time has been that the more 3rd party libraries you pull in, the more difficult it becomes to keep the project updated and maintained. Often security updates and major refactoring of libraries are tied together which means to get the security updates you often have to rewrite the way your app interacts with the library because all of the API endpoints have changed.

By keeping the depencies minimal, I spend a lot less time managing interactions with 3rd party libraries and can often run code that was written a decade ago with minimal changes. Trying to run code that was written a decade ago that relies heavily on other libraries often becomes a very painful process.

I have code with minimal dependencies that I wrote over 10 years ago that still works without any problems. I could easily move it to another server with minimal issues. If I ever need to update a security issue, it is usually a simple process. 

I also have code that was written 5 years ago that heavily uses 3rd party libraries. It takes a lot of time trying to wrangle the depencies and get them all working cleanly with the latest security updates.

I don't have any problems using composer or other 3rd party libraries but my experience has taught me that minimizing dependencies allows the code to be more resilient and portable.


### How do I contribute?

TwitRiv is open source software but it is not currently open contribution software. I'm following the [SQLite model](https://www.sqlite.org/copyright.html) where basically I'm the only one that will be making updates to the code. Feel free to fork, make any changes you want, and use it however you want. If you create a hard fork (meaning you are wanting to take the project in a different direction), I would ask that you rename your project to something else and keep the accreditation in the license file like this:

Right now it says:
```
Copyright (c) 2022 LocationQ
```

If you create a hard fork, please update the license file to something like this:
```
Original Copyright (c) 2022 LocationQ
Copyright (c) 2023 YOUR NAME HERE
```

For an example reference, see how WinterCMS forked OctoberCMS: [WinterCMS License](https://github.com/wintercms/winter/blob/cfa763b714367026f4deef5645c9e64d2f5385bc/LICENSE)

Please don't be offended if I close any pull requests. I would prefer to not receive any pull requests.

### How often is TwitRiv open source code updated?

Updates to the production hosted code are not immediately pushed to the open source version. I have not figured out the specific update schedule that I'm planning to use. I believe [Plausible Analytics](https://github.com/plausible/analytics), which has been a major source of inspiration for this project, uses a six month release schedule. That is one release schedule I have considered, but I haven't definitively decided on anything. I plan to push updates for any significant bugs as soon as possible.  


### Where is the central login and billing code that you are using on TwitRiv and DashboardQ?

If you use the hosted version of TwitRiv, it uses a centralized login and billing system. At this time, I do not have plans to release that code as open source. There are a few other minor differences between the hosted version and the open source version.

All of these additions are added using the web framework's plugin system. I have a DashboardQ plugin that adds all the additional functionality. The plugin system works with the framework's hook system. There are numerous hooks that allow you to intercept the code and perform additional actions. This hook system has taken a lot of inspiration from WordPress hooks. If you look through the code, any place you see `hook('example_action')` is a place that you can intercept and add or modify the functionality.

### What if I run into any bugs, how do I report them?

For now, if you run into any bugs please either add them to the [Github Issue Tracker](https://github.com/dashboardq/twitriv/issues) or to the [DashboardQ Support System](https://www.dashboardq.com/support).

At some point, I may move to a different issue tracker, but for now, either of those should work.

### Can you tell me about the design? Is it really open source?

Yes, feel free to use the layout and design however you want. The icons are from the open source and MIT licensed [Tabler Icons](https://github.com/tabler/tabler-icons). Everything else is original code and design.

If you are looking for email design resources, I definitely recommend checking out the tabler [Responsive Newsletters and Email templates](https://tabler.io/emails).


### Where is the Docker image?

I don't personally use Docker right now. It may be something I add in the future, but right now I'm not looking to add additional code to the project that I don't use. Feel free to create your own Docker image.


### You know someone could rip you off and start competing with you right? You should have used a different open source license.

I'm a strong supporter of the MIT license and am completely aware that it gives someone the right to fully host and compete with my TwitRiv service. My belief is that the majority of the people will want to use the original creator's service and not a copycat service. Please feel free to use, compete, copy, any part of my service. I believe the marketplace is large enough to accomodate multiple companies.

I also think that as a solo developer, I have an ability to react quicker and make changes (change course quicker) as needed than a larger company.

If you do launch a service, the one thing I would ask is that you do not confuse the marketplace by claiming to be "TwitRiv", use the TwitRiv logo, or use any trademarked material - please use a different name and logo if you are offering the service publically for others. Feel free to copy any and all other marketing materials in this repository for any startup you may be working on.

There are countries where I can't reach or easily market to and there are definitely languages I don't speak where I would have no options to provide support. You have my complete and total permission to rip off any part of this project and use it as you see fit. (sidenote, internationalization is not setup just yet - I'm hoping to add it in steps as the core web framework is developed)

I know what it is like to be a freelancer and struggle to pay the bills. If this project allows you to charge more to clients by giving them a white-label Twitter tool, by all means, load this up and start charging more!

The one final note I would make is that launching a profitable software service is very difficult (I've been trying for 17+ years). If you are looking to copy my TwitRiv service, I think it may be harder to achieve success than you realize. If you are wanting to get into building software, I would recommend building a unique tool that solves a problem that you have and/or a problem that you are very familiar with. As you can probably tell, I'm very stubborn and love to compete. If you would like to compete directly, welcome to the competition!

### OK, lets be honest, the answers to that last question is really just a big marketing gimmick, right?

I definitely believe that being free and open can have a significant positive impact on your marketing. In conversations with others, I've brought up the names of software companies I probably wouldn't have simply because they are open about their numbers. Being open helps provide reference points for others and when you become a reference point, that means more people are going to reference you. I'm a big believer in looking for win-win situations.

### Can I steal all your marketing material and create my own SaaS?

Yep. All I ask is that if you are marketing a competing service that you not use the TwitRiv name or logo or make any claims of copyright to material I've written (feel free to use it however you want, just don't claim that you wrote it - you don't need to explicitly reference TwitRiv anywhere publicly or privately).


### You are going to go out of business because you make terrible business decisions.

That is not a question, but to answer, I hope not. If I do fail, I have 10 more startups to launch within the next year. Follow me on my blog ([Anthony Graddy Blog](https://www.agraddy.com/)) or on [Twitter](https://twitter.com/agraddy) and watch me make more terrible business decisions. If nothing else, it should be fun and entertaining!

## Other DashboardQ Projects
1\. [NumbersQ](https://www.numbersq.com/)  
Know your important numbers: [Launch Post](https://www.agraddy.com/startup-1-numbersq-know-the-state-of-your-business-in-30-seconds) | [Open Source Code](https://github.com/dashboardq/numbersq)  

2\. [TwitRiv](https://twitriv.com/)  
Take control of your Twitter experience: [Launch Post](https://www.agraddy.com/startup-2-twitriv-control-your-twitter-experience) | [Open Source Code](https://github.com/dashboardq/twitriv)

