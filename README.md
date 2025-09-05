# What It Dose
A package that include extra validation rules for Laravel, the following are the validation rules that are in development or have been developed.
- [x] Kuwaiti Civil ID.
- [x] ISBN10.
- [x] ISBN13.
- [ ] IMEI.
- [ ] ICCID.

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
