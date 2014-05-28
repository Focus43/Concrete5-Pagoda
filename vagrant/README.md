# Vagrant for Concrete5 #

**Intended Audience/Users**
Developers, primarily. Or command-line savvy designers. Especially useful for **teams** where everyone might not
be developing on the same OS.

Currently VirtualBox is the only virtualization provider supported. Future releases may support VMWare as well.

* [Overview](#overview)
* [Usage / Getting Started](#usage--getting-started)
* [Building Locally](#building-locally)
* [Connecting To Database from GUI](#connecting-to-the-database-from-a-mysql-gui)
* [SSL](#ssl-stuff)
* [Notes](#notes)

## Overview ##

**[Vagrant](http://vagrantup.com)** is a tool to help create and manage virtual machines for use during site development.
By using Vagrant, you can be sure that anyone working on the project will be running code in the exact same environment,
and you can tailor the VM build to closely mimic your production environment. If you're new to Vagrant or the idea
of working with virtual machines for development, [read this](http://www.vagrantup.com/about.html).

Instead of having to install and configure a Concrete5 project by hand, you can just add the `vagrant` directory to your project, run `vagrant up`, and you'll have a fully sandboxed environment specifically for your project. Further, you can manage your development environment programatically via the Vagrantfile - instead of telling team members to configure this or that.

The files included for this Vagrant build will create a development environment with the following installed, tailored for C5 development:
* Ubuntu Linux 12.04 64-bit
* Apache 2
* PHP 5.3.10
* MySQL 5.5
* Composer (optional; for use on 5.7 development currently)
* Redis (optional)
* NodeJS (optional)
* GruntJS (optional)
* Xdebug (optional)
* PHPUnit (optional)

## Usage / Getting Started ##

**Prerequisites**: Ensure Git is installed (instructions for: [OSX](http://help.pagodabox.com/customer/portal/articles/200927),
[Windows](http://help.pagodabox.com/customer/portal/articles/202068)), as well as: [VirtualBox](https://www.virtualbox.org/), and [Vagrant](http://docs.vagrantup.com/v2/installation/).
Installers are available for all common OSs.

### Get The Vagrant Files ###

With the prerequisites installed, all that's necessary is to copy the `vagrant` directory to your Concrete5 project root (ie. at the same level as the `web` directory).

**Via Git**
If your project is using Git for version control, you can add this repository as a remote and simply checkout the /vagrant directory:

    $: cd {your-project-root}
    $: git remote add vagrant-repo {this-repo-git-clone-url}
    $: git fetch vagrant-repo
    $: git checkout vagrant-repo/master -- vagrant

**By Hand**
Download this repo as a zip file from Github, and extract the /vagrant directory into your project root. Done.

### Configuring ###

With the Vagrant directory added to your project, open the extensionless file name `Vagrantfile` inside the /vagrant directory. At the top of this file, you'll see a series of hashes you can use to configure the build for your project. **Note:** sensible defaults have been used, and there is actually zero configuration required to run a Concrete5 site. "Configuration" refers to optional tools/settings you may want to use on any given project.

#### Concrete5 Installation Settings ####

Configure the admin password, whether Pretty URLs should be enabled, and optionally the MySQL database settings running within the VM. Unless you want to dig in with the machine configuration, generally only touch the admin_pass option.

    # Basic settings
    box_settings[:concrete5] = {
      :admin_pass => 'c5@dmin',
      :db_name => 'concrete5_site',
      :db_username => 'root',
      :db_password => 'root',
      :db_server => '127.0.0.1',
      :pretty_urls => true 
    }

#### VM Configuration ####

How much memory should your host machine delegate to the VM? Which ports should be forwarded? (For example, if you were to use Grunt's livereload plugin, you could port forward 54329).

    # Machine config
    box_settings[:vm_config] = {
      :memory_cap => '384',
      :port_forwards => {
          80 => 8080, # Apache (ie, :80 in the VM -> :8080 on your machine)
          443 => 4433, # SSL
          3306 => 3307 # MySQL
      }
    }

#### MySQL Timezone Tables ####

Setup automatically? Make sure your production system matches!

    # Misc options
    box_settings[:auto_install_mysql_timezone_tables] = true

#### Development Tools ####

If you're going to hack on Concrete5 core, or you're a developer that wants to use any of the following tools for a project, you can simply enable by toggling true/false below.

    # Developer tools
    box_settings[:dev_stack] = {
      :enable => true,
      :opts => {
          :php_tools => {
              :xdebug => false,
              :phpunit => false
          },
          :redis => false,
          # If :install = false, gruntjs, bower, and npm won't be installed as they need Node
          :nodejs => {
              :install => false,
              :gruntjs => true,
              :bower => true,
              :npm => {
                :auto_install_packages => true,
                :package_json_location => '/home/vagrant/app/build' # defaults to ../build
              }
          },
          # If :rbenv = false, no options below will be relevant
          :ruby => {
              :rbenv => false,
              :version => '2.1.0',
              :gems => [
                  {:name => 'bundler'}
              ]
          },
          :composer => { # ONLY RELEVANT FOR 5.7 DEVELOPMENT!
              :install => false,
              :auto_install_packages => true,
              :composerjson_location => '/home/vagrant/app/web/concrete/'
          }
      }
    }

### Run The Machine ###

From the command line, navigate to this directory (`$: cd {root}/vagrant/`) in your Concrete5 repository , then simply

    $: vagrant up

... and grab a beer. The first time you `vagrant up` can take a while. Vagrant is creating your new VM and downloading/building
(provisioning) all the necessary tools for Concrete5 development. Subsequent starts of the VM with `vagrant up` will *not*
provision the entire box, unless you declare the `--provision` flag.

Once the VM is done provisioning, open a browser and go to `http://localhost:8080` (assuming port 8080 is not being used
on your machine when you ran `vagrant up`). If :8080 was in use by another program when you started Vagrant, the VM will
automatically bind to the next available port. When you `vagrant up`, it'll tell you where.

You now have a complete LAMP stack running inside of a sandboxed virtual machine, with Concrete5 automatically installed
via the command line.

**Default Login Credentials**
For both Pagodabox and local installations:
* user: `admin`
* password: `c5@dmin`

**Pretty URLs** are enabled by default; during the provisioning process, an .htaccess file will be created in the /web
directory, and the `URL_REWRITING_ALL` constant will be added to config/site.php. To disable this, make sure you set
`:prettyurls => false` in the Vagrantfile. (See **:concrete5 **sections of `_configs`).

## Building Locally ##

**Note** If you're new to developing within a VM, understand this: you can work on your Concrete5 code base with your
favorite tools on your on own system as you would every day, but when you visit `http://localhost:8080` in a browser,
your code base is being executed completely within the VM. Its totally segregated from whatever operating system your
using on your local computer. Your code is actually being run on a real Ubuntu Linux LAMP stack.

Now open the project in your favorite IDE, build something awesome, and reload your site. You'll see that the changes
you made to the code base are reflected immediately. Vagrant creates a synced folder between the host operating system
(your computer) and the guest OS (the Ubuntu VM). Any changes you make to files are immediately synced within the VM.

#### Starting/Stopping Vagrant for day-to-day development ####

Whenever you work on the project, make sure the VM is running. From project root, `$ cd vagrant && vagrant up`. When
you're done, do `vagrant halt`. This will shut down the VM and free up any resources that were being used on your machine.
If you need to work on multiple projects throughout the day, you can run multiple VMs simultaneously (they're fairly light weight).
Just beware that every VM you `vagrant up` will bind on a different port, so accessing each project/site in the browser
happens on a different port (it tells you which when you start the VM via `vagrant up`).

## Connecting to the database from a MySQL GUI ##

If you want to inspect whats going on in the database, you can easily connect to the MySQL instance running inside the VM.
From your favorite MySQL GUI, use:

    host: 127.0.0.1 (or localhost)
    username: root
    password: root
    port: 3307

If something else was running on port 3307 when you ran `vagrant up`, Vagrant will bind to the next available port, similar
to how port :8080 will get port-forwarded (see above).

## SSL Stuff ##

The VM comes with a self-signed certificate for testing SSL during development. As it's a self-signed certificate, your
browser will (almost definitely) show security warnings until you declare the certificate as trusted. Just click proceed.
To eliminate the warning, you should add the certificate to your system's trusted certificate chain. If you're on Chrome,
the only way to make the `https://` connection marker in the URL bar green is to setup an alias in your system's `hosts`
file that includes a *dot* (eg. "http://local.host" is the same as "http://localhost")

On OSX, from terminal:

* `$ sudo nano /etc/hosts`
* Add a line with `127.0.0.1 local.host` then save
* Test in your browser by first visiting the `http://local.host:8080` (or whatever port the VM is running on), and make
sure that your site is loading at the new host alias
* Next, try to visit it via https: `https://local.host:4433` (again, when you start the VM, Vagrant will show which port
443 is forwarded to - usually it'll be 4433)

Frequently, a host file will look something like this:

    ##
    # Host Database
    #
    # localhost is used to configure the loopback interface
    # when the system is booting.  Do not change this entry.
    ##
    127.0.0.1	localhost
    255.255.255.255	broadcasthost
    ::1             localhost
    fe80::1%lo0	localhost

    # now add this line!
    127.0.0.1 local.host

If you setup the browser correctly, you should be able to connect via HTTPS with a green "secure" notification in the
browser bar.

## Notes ##
Setup was built and tested on OSX 10.9 Mavericks, with VirtualBox 4.2.18 and Vagrant 1.3.5. Setup should work on any 
Vagrant 1.x version. Please report any issues to the [Concrete5 issue tracker](https://github.com/concrete5/concrete5/issues) on
Github with the tag "Build/Grunt".