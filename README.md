# Laravel Shop

This is a shop build on top of [Laravel](https://laravel.com/) using [Aimeos Laravel Package](https://aimeos.org/integrations/laravel-ecommerce-package/).

## Getting started

All stories have a beginning and so does this project. You can read the [Laravel Documentation] and [Aimeos Documentation] to get started quickly, but we've done all the grunt work for you.

Just follow these steps and you should be all-right.

### 1. Install this repo

If you didn't received an invitation from us for our classroom, please contact @dragonbe or @bartmcleod to provide you the invitation link.

```
cd /path/to/workspace
git clone git@git@github.com:in2it-training-<username>/phpbootcamp-shop.git phpbootcamp-shop
cd phpbootcamp-shop
```

### 2. Install the PHP packages

You need [Composer] to continue as we're needing several packages to continue.

```
composer install
```

### 3. Set environment settings

```
cp .env.example .env
```

Depending on your database settings, if you followed from the beginning these default settings are going to be enough.

Next thing you need to do is set an application key

```
php artisan key:generate
```

### 4. Launch your application

```
php artisan serve
```

Now point your browser to [localhost:8000/list](http://localhost:8000/list) to see the webshop application.


[Laravel Documentation]: https://laravel.com/docs/5.3/installation
[Aimeos Documentation]: https://github.com/aimeos/aimeos-laravel#aimeos-laravel-package
[Composer]: https://getcomposer.org
