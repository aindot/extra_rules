# What it dose
A package that include extra validation rules for Laravel, this package approach focuses on actual usage, for example the ISBN test must validate against different real-world examples.

The following are the validation rules that are in development or have been developed.
- [x] Kuwaiti Civil ID.
- [x] ISBN10.
- [x] ISBN13.
- [x] ISNI.
- [x] ISWC.
- [x] ISTC.
- [x] ISAN.
- [x] ISMN.
- [x] ISRC.
- [x] URN.
- [x] NBN.
- [x] DOI.
- [x] ISSN.
- [x] EAN8.
- [x] EAN13.
- [x] GTIN13.
- [x] IMEI.
- [x] IMEISV.
- [x] IMSI - A unique identifier for a mobile network subscriber.
- [x] ICCID - A unique serial number identifying a sim card.

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
