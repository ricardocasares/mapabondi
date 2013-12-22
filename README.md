mapabondi
=========

Bus route search and browse app on top of Slim framework REST API.

## Requirements

1. MySQL database
2. PHP >= 5.3.0 (use PHP >= 5.4.0 for built-in web server)
3. Composer for downloading dependencies

## Installation

1. Clone the repo `git clone https://github.com/ricardocasares/mapabondi.git`
2. [Install composer](http://getcomposer.org/doc/00-intro.md#installation-nix), then `composer install`
3. Create a database and import `mapabondi.sql`
4. Configure your database settings on [index.php line 184](https://github.com/ricardocasares/mapabondi/blob/master/index.php#L184)
5. Install to your document root, or run `php -S localhost:3000` if using PHP >= 5.4.0

## API docs
Checkout the API documentation [here](https://github.com/ricardocasares/mapabondi/wiki/API-Documentation)