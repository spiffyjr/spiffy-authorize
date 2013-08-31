# SpiffyAuthorize

SpiffyAuthorize is an authorization module for SpiffyCms, however, this module functions entirely as a standalone
module as well.

## Project Status
[![Master Branch Build Status](https://secure.travis-ci.org/spiffyjr/spiffy-authorize.png?branch=master)](http://travis-ci.org/spiffyjr/spiffy-authorize)
[![Dependency Status](https://www.versioneye.com/user/projects/51a345b9d2eb58000200ac03/badge.png)](https://www.versioneye.com/user/projects/51a345b9d2eb58000200ac03)

## Documentation

Please check the [`doc` dir](https://github.com/spiffyjr/spiffy-authorize/tree/master/doc)
for more detailed documentation on the features provided by this module.

## Requirements

* PHP 5.4
* Composer

## Installation

Installation of SpiffyAuthorize uses composer. For composer documentation, please refer to
[getcomposer.org](http://getcomposer.org/).

```sh
php composer.phar require spiffy/spiffy-authorize:dev-master
```

Then add `SpiffyAuthorize` to your `config/application.config.php`

Installation without composer is not officially supported, and requires you to install and autoload
the dependencies specified in the `composer.json`.
