# What it dose
A package that include extra validation rules for Laravel, this package approach focuses on actual usage, for example the ISBN test must validate against different real-world examples.

The following are the validation rules that are in development or have been developed.
- [x] Kuwaiti Civil ID - Official personal identification number for residents of Kuwait.
- [x] ISBN10 - Legacy 10-character identifier for books.
- [x] ISBN13 - Modern 13-digit identifier for books and related media.
- [x] ISNI - Unique identifier for authors, artists, and other contributors.
- [x] ISWC - Unique identifier for musical works (compositions).
- [x] ISTC - Unique identifier for textual works across editions and formats.
- [x] ISAN - Unique identifier for films, TV programmes, and audiovisual works.
- [x] ISMN - Unique identifier for printed music publications.
- [x] ISRC - Unique identifier for sound recordings and music videos.
- [x] URN - Persistent, location-independent uniform resource name.
- [x] NBN - National bibliography number used by national libraries.
- [x] DOI - Persistent identifier for digital objects such as papers and datasets.
- [x] ISSN - Unique identifier for serial publications such as journals.
- [x] EAN8 - 8-digit retail barcode for small product packaging.
- [x] EAN13 - 13-digit retail barcode used worldwide at point of sale.
- [x] GTIN13 - 13-digit global trade item number for products and packaging.
- [x] IMEI - Unique identifier for a mobile handset or cellular device.
- [x] IMEISV - Device identity plus software version number (SVN).
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
