Take The Register
=================

[![Build Status](https://secure.travis-ci.org/mdarse/take-the-register.png)](http://travis-ci.org/mdarse/take-the-register)

Take the register is an application to manage student presence in Cergy-Pontoise university.

Features
--------
* Synchronization with Google Agenda
* Automatic matching between event names and professors
* Event blacklisting to filter out lessons

Installation
------------
Take The Regiser uses [Composer][2] to manage dependencies.

If you don't have Composer yet, download it following instructions on http://getcomposer.org/ or just run the following command:

	curl -s http://getcomposer.org/installer | php

Before running next command, we will need to copy `app/config/parameters.yml.dist` to `app/config/parameters.yml` and adjust setting according to your configuration.

Then use the `install` command to download dependencies:

	php composer.phar install

Checking your System Coonfiguration
-----------------------------------
Execture the `check.php`script from the command line:

	php app/check.php
	
Access the `config.php` script from a browser:

    http://localhost/path/to/symfony/app/web/config.php

If you get any warnings or recommendations, fix them before moving on.

Make sure to ensure that the `app/cache` and `app/logs` directories are write-able for the web server (and also for the CLI):

[Symfony configuration and setup][3]

If everthing is OK, you can continue by running this:

	php app/console cache:warmup
	
It will setup cache files an all that stuff automatically.

Installing the database
-----------------------
This application is using MySQL as storage backend.

Create the database:

	php app/console doctrine:database:create
	
This will create an empty database according to your `parameters.yml` file.

Create the schema with all tables:

	php app/console doctrine:schema:create
	
This is magic :-)

Creating the first user
-----------------------

In order to interact with the application, your need an initial user account to sign in. There is a command for that.

	php app/console fos:user:create <username> <email> --super-admin
	
You can now log in to your application.
	
Later, to add admin rights on an existing account you will just have to do that:

	php app/console fos:user:promote <username> ROLE_ADMIN

Google Agenda syncronization
----------------------------
Take The Register is capable of extracting the university planning on a Google calendar, and turn it into lessons directly in the app.

To setup this, you need a Google account which has at least read access to the targeted calendar.
Go to `http://<your-application>/sync`, then click on `Link an account`, you will be prompted to allow access to application. After that the app let you choose the calendar, and it's done.

For sync to occur automatically you need to setup a `crontab` entry or something similar, this is the command who need to be run:

	php app/console ucp:lesson:sync
	
Professor matching
------------------
When syncing, Take The Register can automatically associate the event with the right professor. Your just need to append the professor initials to the event name like this:

`OOP Programming (PI)`

The initials can be 2 or 3 letters. Of course, you need to set initials in the professor's profile for matching to work correctly. The automatic matching will occur only if a professor hasn't be defined manually.

Also, the application ignore event ending with `?`or `(???)`

_In a future version, you will be able to set custom expressions to filter or associate events._




[2]:  http://getcomposer.org/
[3]:  http://symfony.com/doc/current/book/installation.html#configuration-and-setup
