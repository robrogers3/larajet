# Larajet

[![Latest Version on Packagist][ico-version]](https://packagist.org/packages/robrogers/larajet)
[![Build Status][(https://travis-ci.org/robrogers3/larajet.svg)](https://travis-ci.org/robrogers3/larajet)
[![Software License][ico-license]](LICENSE.md)
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]

Larajet is a mail transport for Laravel. It is a MailJet integration package. Much like Mailgun, this allows for sending Mail via the mailjet api (based on Mailjet API v3.) 
It supports Mailables and Notifables.

[Sboo](https://github.com/sboo) had already done a lot of the heavy lifting.

## Install

Via Composer

``` bash
$ composer require robrogers3/larajet
```

You will need to update app.php

In Package Service Providers add

Larajet\MailjetServiceProvider::class

Also, add this line to the 'aliases' array
'MailJet' => Larajet\Facades\MailJet::class,

Then you need to configure services.php. Add this:

``` php
    'mailjet' => [
        'public_key' => env('MAILJET_PUBLIC_KEY'),
        'private_key' => env('MAILJET_PRIVATE_KEY'),
        'from' => env('MAIL_FROM'),
        'driver' => env('MAIL_DRIVER'),
        'guzzle' => [],
        'api_url' => 'https://api.mailjet.com/v3/send'
    ],
```

You will need to update your .env file accordingly.

## Usage

The best way is to create a Mailable. e.g. 

``` bash
php artisan make:mail TestMail
```

Then just mail it!

``` php
use App\Mail\TestMail;

Mail::to(fred@example.com)
        ->subject('Test Mail')
        ->send(new TestMail);
```

Learn about [Mailables](https://laravel.com/docs/5.3/mail)

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```
There is only one test. It tests the mail was sent.

Basically the steps are:
* Call a console command, or hit a route that sends an email.
* There are no more steps.

A route might look like:
``` php
use App\Mail\TestMail;

Route::get('/sendmail', function() {
    Mail::to(\App\User::first()->email)
        ->send(new TestMail);
    return 'sent?';
});
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CONDUCT](CONDUCT.md) for details.

## Security

If you discover any security related issues, please email robrogers@me.com instead of using the issue tracker.

## Credits

- [Rob Rogers][link-author]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/robrogers3/larajet.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/robrogers3/larajet/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/robrogers3/larajet.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/robrogers3/larajet.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/robrogers3/larajet.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/robrogers3/larajet
[link-travis]: https://travis-ci.org/robrogers3/larajet
[link-scrutinizer]: https://scrutinizer-ci.com/g/robrogers3/larajet/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/robrogers3/larajet
[link-downloads]: https://packagist.org/packages/robrogers3/larajet
[link-author]: https://github.com/robrogers3
[link-contributors]: ../../contributors
