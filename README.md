# Concrete5 CMS Pagodabox Quickstart #

Release of Concrete5 (latest stable: 5.6.1.2) for Pagodabox, with a couple extra free add-ons.

## Overview ##

This is a release of the Concrete5 CMS, with slight modifications to run on the PagodaBox infrastructure. This repository
will remain updated as new stable releases roll out.

This quickstart will automatically provision the a 200mb web server, a 10mb MySQL server, and a 10mb
Redis cache server. Every resource can be scaled independently using the Pagodabox dashboard.

Check out the Boxfile to see the basic install settings. Features:

1. APC cache installed as opcode cache
2. Redis ~~is~~ can be used as the session cache, and as the Full Page Caching library
3. Server file upload size set to 20mb

## Usage ##

Visit https://pagodabox.com/cafe/jonohartman/concrete5. The one-click install will clone
this repository and perform all setup.

## Installing Locally ##

The intended workflow with this PagodaBox Quickstart is: run QuickStart install on PagodaBox, then clone the
QuickStart repo to your local machine. Install on your local machine, then develop there. When changes are working,
simply push back to the origin (pagodabox). To install on your local machine, you *should* be able to simply clone
the repo, setup a mysql database, and create a file named `site.local.php` at `web/config/site.local.php`.

In the `site.local.php` file, place the following code (credentials for connecting to your *local* database):

	<?php
	$_SERVER['DB1_HOST'] = 'HOST_HERE'; // probably localhost
	$_SERVER['DB1_USER'] = 'USER_HERE'; // maybe root
	$_SERVER['DB1_PASS'] = 'PASSWORD_HERE'; // maybe empty on your local machine
	$_SERVER['DB1_NAME'] = 'DB_NAME_HERE'; // local database to use, must be empty

Update appropriately, and save. Then from the command line:

	cd /path/to/web/root/ (repository root)
	php cli_installer.php

## Notes ##

PagodaBox MySQL defaults to different auto-increment settings. Specifically, the auto-increment-increment settings 
used in a master-master MySQL configuration are not the same as a standard MySQL install.

	auto-increment-increment = *
	auto-increment-offset = *

This causes the installation of dashboard pages to fail. This has been fixed to work with PagodaBox.


In order to download marketplace items (packages, themes, etc.), the /packages directory would need to be writable.
With PagodaBox's shared writable directories, this IS possible, but the code in the packages directory would no longer
be trackable. By using this QuickStart, understand that *downloading marketplace items directly to the server is not
possible.* Instead, you should download the package to your local installation, test the install, commit locally, then
push the changes to Pagoda. This is the intended workflow with Pagoda - so although there is some flexibility lost with
not being able to install add-ons directly from the marketplace, this workflow will enforce best-practices.

If we get enough requests to enable downloading marketplace add-ons, we'll come up with a solution. Please submit to the
QuickStart reviews at PagodaBox.

* To search for modifications to the core code, search for "@pagoda".

## Resources ##

MySQL issue: http://www.concrete5.org/developers/bugs/5-6-0-2/install-fails-with-mysql-auto-increment-offset-set/
Memcache usage: http://www.concrete5.org/community/forums/customizing_c5/memcached-is-not-working/#239289
