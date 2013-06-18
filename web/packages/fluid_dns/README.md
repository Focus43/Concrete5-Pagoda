# FluidDNS Package for Concrete5.6.1 #

Manage multiple domains with a single Concrete5 instance. Route domains and/or subdomains to
specific pages in the sitemap. In effect, multiple sites can be hosted with a single instance.

## Overview ##

Point multiple domains at a single Concrete5 installation, and route domains to any page in the
sitemap. FluidDNS supports *wildcard subdomains*. With a root domain `mydomain.com` setup to
catch wildcard subdomains, you can dynamically route `other.mydomain.com` to a subpage in the
sitemap named 'Other', or `somethingelse.mydomain.com` to a page 'SomethingElse'. With wildcards, 
the subdomain can be *anything*, and will be dynamically routed to a subpage.

Consider the scenario: when someone signs up on your site, you want to automatically
create a subdomain for them, and give them edit access (with restricted permissions) to a few
automatically created pages. A user registers having first name: The, last name: Governator.
Your sitemap has a page called User Sites, where you place user pages under. So you create
the following pages:

	The Governator (path: home/user-sites/the-governator)
	About (path: home/user-sites/the-governator/about)
	About (path: home/user-sites/the-governator/contact)

In the FluidDNS configuration, you set your domain to Resolve Wildcard Subdomains, and set
the Wildcard Root to the page with path `home/user-sites/`.

Instantly, the new user The Governator can access his site `the-governator.mydomain.com`.

## Notes ##

There is one core override, `request.php`, and the file `site_post_autoload.php` must be in
the config directory (see files in the repository root). No modifications to the core are
necessary.

FluidDNS *works with Full Page Caching*. In order to keep Concrete5 snappy, parsing of DNS
route records is handled with Redis, early in the load process. If you have a host where you
can install and run Redis, this package should work just fine. However, this is intended to
be used (mostly) on PagodaBox - as it "just works".

## Dependencies ##

ConcreteRedis package (included in this repository).
