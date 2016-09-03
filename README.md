Image Downloader
====

Allows you to download images from remote images in a cost effective way. Before downloading an image it makes a `HEAD` 
request and checks the `Content-Type`, and `Content-Length` headers in HTTP Response.

If server reports an image is at that URL, Image Downloader will issue a `GET` request and download the image.

Requirements
====
* PHP 7 or greater
* `php-gd` extension
* `php-curl` extension
* PHP [Multi-Byte](http://php.net/manual/en/book.mbstring.php) support enabled

Versions
====
This project follows [semver](http://semver.org/)

Installation
====
TODO

Usage
====
TODO

Tests
====
You can run tests using [PHPUnit](https://phpunit.de/):
```bash
$ vendor/bin/phpunit -c phpunit.xml.dist tests
```

TODO:
====
* Make this a RESTful service
