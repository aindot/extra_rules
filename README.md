# A package that include extra rules for Laravel, Kuwait Civil ID validation, ISBN10, ISBN13 and more in development.

I focus on calculated methods, IMEI and Simcard validations are on the way.

## Installation

You can install the package via composer:

```bash
composer require aindot/extra-rules
```

## Usage

```php
    $data = [
        'KuwaitiId' => $id,
    ];

    $validator = Validator::make($data, [
        'KuwaitiId' => [new KuwaitCivilId],
    ]);
```

## Credits

- [Abdullah](https://github.com/aindot)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
