# Multi tenancy

[![Latest Stable Version](https://poser.pugx.org/hyn/multitenant/v/stable?format=flat-square)](https://packagist.org/packages/hyn/multitenant)
[![License](https://poser.pugx.org/hyn/multitenant/license?format=flat-square)](https://packagist.org/packages/hyn/multitenant)
[![Build Status](https://img.shields.io/travis/hyn/multitenant/2.x.svg?maxAge=2592000&style=flat-square)](https://travis-ci.org/hyn/multitenant)
[![Code Coverage](https://img.shields.io/codecov/c/github/hyn/multitenant/2.x.svg?maxAge=2592000&style=flat-square)](https://codecov.io/github/hyn/multitenant)
[![StyleCI](https://styleci.io/repos/39585488/shield)](https://styleci.io/repos/39585488)
[![Reference Status](https://www.versioneye.com/php/hyn:multitenant/reference_badge.svg?style=flat)](https://www.versioneye.com/php/hyn:multitenant/references)
[![SensioLabsInsight](https://img.shields.io/sensiolabs/i/f8241f3b-ab7b-4a58-9123-488c13560887.svg?maxAge=2592000&style=flat-square)](https://insight.sensiolabs.com/projects/f8241f3b-ab7b-4a58-9123-488c13560887)
[![Awesome Laravel](https://cdn.rawgit.com/sindresorhus/awesome/d7305f38d29fed78fa85652e3a63e154dd8e8829/media/badge.svg)](https://github.com/chiraggude/awesome-laravel)

This package allows for multi tenancy websites on one installation of Laravel.

---

The goals for this and its related packages are:

- Unobtrusive multi tenancy for Laravel 5.1 LTS and 5.2.
- Provide proper insight into tenants and webserver.
- Powerful, but flexible for developers, use it the way you want.

### Reading material:

- [documentation][7]
- [changelog](changelog.md)
- [website][1]
- [contributor information](contributing.md)

## What is multi tenancy

Referring to [wikipedia](http://en.wikipedia.org/wiki/Multitenancy);

> Multitenancy refers to a software architecture in which a single instance of a software runs on a server and serves multiple tenants.

### Multi tenancy how?

In its most abstract sense you can use this package to manage multiple websites with only one application installation.
- Multiple websites running on one code base.
- Multiple hostnames configured per website.

Each website has its own folder on disk, allowing:
- seperation of routes, templates, translations etc
- custom files (media, themes and packages)

Also each website has its own database, this ensures that in no way one website can access data from another website.
The distinction also gives proper division of responsibilities to the system (global) and tenant (local) databases.

For more information visit the [documentation](https://hyn.readme.io/docs/hyn-approach-to-tenancy).

### Example tenant website & demo

One website running on the multi tenant installation of [hyn.me][1] is [dummy.hyn.me](http://dummy.hyn.me), you can review the code on [github.com/hyn/dummy-tenant-website](https://github.com/hyn/dummy-tenant-website).

## [Installation, configuration][7]

## Chat or critical bug

- For priority bug reports, please contact me directly on [gitter.im/luceos][6].
- The package chat can be found at [gitter.im/hyn/multitenant][8].
- A support forum will be made available in the future.

## Q&A

> Please visit the [FAQ](https://hyn.readme.io/docs/frequently-asked-questions) in the [documentation][7].


[1]: https://hyn.me
[2]: https://hyn.me/packages/multitenant
[3]: https://github.com/hyn/multitenant
[5]: https://github.com/hyn/multitenant/issues
[6]: https://gitter.im/luceos
[7]: https://hyn.readme.io
[8]: https://gitter.im/hyn/multitenant
