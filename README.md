# Concrete5 :: Pagodabox Quickstart #

Concrete5 (latest stable: 5.6.2.1) for [Pagodabox](http://pagodabox.com), with a couple freebie packages and a whole slew
of tools for developers. Use the one-click Quickstart launcher at **[Concrete5 Quickstart](https://pagodabox.com/cafe/jonohartman/concrete5)**.

**Intended Audience/Users**
In short, this Quickstart/repo is targeted at developers/teams or git-savvy folks that want a super-streamlined workflow.
Using the bundled VM provisioning tools, you can be sure anyone who ever checks out and runs the project locally is using the exact same environment, with the exact same toolset. And deploys via `git push`.

**Versions**: Vagrant builds were tested with VirtualBox 4.2.18 and Vagrant 1.3.5 on OSX Mavericks. Small version incremements should remain stable,
however, not guaranteed. If you're using a newer version of either VirtualBox or Vagrant and run into issues, please file an issue report in the
[issues tracker](https://github.com/Focus43/Concrete5-Stable/issues)

[Overview](#overview)

[Usage / Getting Started](#usage--getting-started)
* [Building Locally](#build-locally)
* [Starting/Stopping VM](#startingstopping-the-vm-for-day-to-day-development)
* [Connecting To Database from GUI](#connecting-to-the-database-from-a-mysql-gui)
* [SSL](#ssl-stuff)

[Notes](#notes)

## Overview ##

This is a release of Concrete5 v5.6.2.1, with slight modifications to run on PagodaBox (see notes at the bottom of this page for details). Several tools come bundled with this repo for creating a super-streamlined workflow for development and deployment. From start to finish, here's what can be accomplished with **3 commands** (literally, that's it).

1. Launch a production instance of Concrete5 on Pagodabox,
2. Get a local copy of the repo on your own machine (with Git-deployment pre-configured),
3. Build and provision a VM for local development with: Ubuntu 12.04, Apache, PHP 5.3.10, MySQL 5.5+, Redis, NodeJS, GruntJS, Xdebug, PHPUnit

Thanks to kick-ass [Vagrant](http://www.vagrantup.com/), you **do not** need to have a LAMP stack installed on your machine
to get a development copy up and running. The files in the /vagrant directory will automatically build and provision
an entire VM automatically, and will bind (if avail) to port :8080 on your local machine. Concrete5 will be automatically
installed in the VM, matching the install process used for Pagodabox.

If you're new to Vagrant or the idea of working with virtual machines for local development, [read this](http://www.vagrantup.com/about.html).

#### Default Pagodabox Configuration ####

This quickstart will automatically provision the following resources for a new Concrete5 app on Pagodabox
(see the [Boxfile](https://github.com/Focus43/concrete5/blob/pagoda/Boxfile) for exact details).

* a 200mb web server (PHP 5.3.10, APC cache, CURL, zip, GD image library, 20MB max upload filesize setting)
* a 10mb MySQL server
* a 10mb Redis cache server (used as session store, and optionally the full page caching library)

Every resource can be scaled independently in the app dashboard, but the default configuration (200mb web server, 10mb mysql, 10mb redis)
can handle a very decent traffic load with full page caching enabled. (Even better if you install the included
Redis package, which enables Concrete5's full page cache library to use ultra-fast, in-memory Redis).

## Usage / Getting Started ##

**Prerequisites**: A [Pagodabox account](https://dashboard.pagodabox.com/account/register), configured for
pushing/pulling via Git (instructions for: [OSX](http://help.pagodabox.com/customer/portal/articles/200927), [Windows](http://help.pagodabox.com/customer/portal/articles/202068)),
and the following installed on your local computer: [VirtualBox](https://www.virtualbox.org/), and [Vagrant](http://docs.vagrantup.com/v2/installation/).

* After logging in, click this link to launch a new C5 instance: https://pagodabox.com/q/u8/go. (Be patient, takes a sec
to initialize).

* Once the app has launched, visit the app dashboard and look for 'Show Git Clone Url'. Copy the URL, then on
your local computer, `$ git clone {git-url-here}`.

* In the repo root on your machine, `$cd vagrant && vagrant up`. Watch Vagrant build your development environment (could take a while).

When you clone the repository from Pagodabox, the default branch in your repo will be called "pagoda" instead of the usual "master". From your project root, do `$ git status` to confirm. You'll want to make all changes to this branch (it is effectively master, but for upgrading purposes when new releases come out from the core team, we preserve master).

**Default Login Credentials**
For both Pagodabox and local installations:
* user: `admin`
* password: `c5@dmin`

CHANGE THE PASSWORD ON YOUR PAGODABOX (eg. "PRODUCTION") INSTANCE RIGHT AWAY

#### Build Locally ####

Once the VM is done provisioning, open a browser and go to `http://localhost:8080` (assuming port 8080 is not being used on your machine). If :8080 is in use by another program, the VM will automatically bind to the next available port. When you `vagrant up`, it'll tell you where.

Now open the project in your favorite IDE, build something awesome, and when you're ready to push the changes to your live Pagodabox instance, just...

* `$ git add . && git commit -m "Built something fly"`
* `$ git push origin pagoda`

Rinse and repeat.

**Note** If you're new to developing within a VM, understand this: you write all the code on your local machine, but when you visit `http://localhost:8080` in a browser, your code base is being executed completely within the VM. Its totally segregated from whatever operating system your using. Your code is actually being run on Ubuntu linux 12.04 w/ Apache, MySQL, PHP 5.3.10, and Redis (if your using the ConcreteRedis package).

#### Starting/Stopping the VM for day-to-day development ####

Whenever you work on the project, make sure the VM is running. From project root, `$ cd vagrant && vagrant up`. When you're done, do `vagrant halt`. If you need to work on multiple projects throughout the day, you can run a few VMs at a time without much problem (they're fairly light weight). Just beware that every VM you `vagrant up` will bind on a different port, so accessing each project/site in the browser happens on a different port (it tells you which when you start the VM via `vagrant up`).

To start the VM without provisioning, do `vagrant up` with the `--no-provision` flag. (`cd vagrant && vagrant up --no-provision`). After the VM is built the first time, you can start it with `--no-provision` to load it faster.

#### Connecting to the database from a MySQL GUI ####

If you want to inspect whats going on in the database, you can easily connect to the MySQL instance running inside the VM from your local machine. From your favorite MySQL GUI, use:
* host: `127.0.0.1` (or `localhost`)
* username: `root`
* password: `root`
* port: `3307`

If something else was running on port 3307 when you ran `vagrant up`, Vagrant will bind to the next available port, similar to how it handles :8080 (see above).

#### SSL Stuff ####

The VM comes with a self-signed certificate for testing SSL during development. As it's a self-signed certificate, your browser will (almost definitely) show security warnings. Just click proceed. To eliminate the warning, you should add the certificate to your system's trusted certificate chain. If you're on chrome, the only way to make the `https://` connection marker in the URL bar green is to setup an alias in your system's `hosts` file (the alias must include a `.`).

On OSX, from terminal:

* `$ sudo nano /etc/hosts`
* Add a line with `127.0.0.1 lo.cal` then save
* Test in your browser by first visiting the `http://lo.cal:8080` (or whatever port the VM is running on)
* Next, try to visit it via https: `https://lo.cal:4433` (again, when you start the VM, Vagrant will show which port 443 is forwarded to - usually it'll be 4433)

If you setup the browser correctly, you should be able to connect via HTTPS with a green "secure" notification in the browser bar.

**Deploying w/ SSL to Pagodabox**

This repo comes pre-configured with an [.htaccess](https://github.com/Focus43/concrete5/blob/pagoda/web/.htaccess) file, to enable Pretty URLs by default (as well as a whole bunch of static asset caching optimizations). It also includes settings for forcing HTTPS connections either sitewide, or when accessing the dashboard (commented out). When you want to deploy a site with SSL, simply uncomment the appropriate lines.

## To Do ##

Write-up on using updated build tools (GruntJS) in the VM, and the bundled ConcreteRedis and FluidDNS packages in the /packages directory.

## Notes ##

PagodaBox MySQL defaults to different auto-increment settings. Specifically, the auto-increment-increment settings 
used in a master-master MySQL configuration are not the same as a standard MySQL install.

	auto-increment-increment = *
	auto-increment-offset = *

This causes the installation of dashboard pages to fail. This has been fixed to work with PagodaBox.

### Marketplace Integration ###
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
