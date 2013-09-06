# ConcreteRedis Package for Concrete5.6.1 #

Easily use Redis to speed up Concrete5 sites... a lot. Using this package requires that your environment has Redis installed (either locally or accessible via network). In your `config/site.php`, you'll need to define the following constants
* `REDIS_CONNECTION_HANDLE` (ex: `define('REDIS_CONNECTION_HANDLE', 'tunnel.pagodabox.com:6379');`)
* `PAGE_CACHE_LIBRARY` (ex: `define('PAGE_CACHE_LIBRARY', 'Redis');`)

Checkout the [config/site.php file](https://github.com/Focus43/concrete5/blob/pagoda/web/config/site.php) in this repository for an example.

Done.

## Overview ##

This package simply bundles the [Predis](https://github.com/nrk/predis) class, into an easily accessible interface, as well as provides a Redis Full Page Cache class. Instead of caching your full pages on the file system, you can use Redis as the Full Page Cache store to speed things up. In a distributed environment (eg. running multiple webservers behind a load balancer), caching pages in Redis (network accessible and in-memory) yields huge performance gains.

#### Other Uses ####

With the package installed, a system-wide ConcreteRedis class becomes available. Its a singleton and functions just like Concrete5's `Loader::db()->...` calls. You can simply use `ConcreteRedis::db()->{redis_command_here}` to store serialized objects in smokin' fast Redis.