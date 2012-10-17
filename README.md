Take The Register
=================

[![Build Status](https://secure.travis-ci.org/mdarse/take-the-register.png)](http://travis-ci.org/mdarse/take-the-register)

Take the register is an application to manage absences in university.

Features
--------
* Synchronization with Google calendar
* Automatic matching with professors
* Event blacklisting to get only lessons

Installation
------------
Symfony uses [Composer][2] to manage its dependencies.

If you don't have Composer yet, download it following instructions on http://getcomposer.org/ or just run the following command:

	curl -s http://getcomposer.org/installer | php
	
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








	

[2]:  http://getcomposer.org/
[3]:  http://symfony.com/doc/current/book/installation.html#configuration-and-setup