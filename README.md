# What it dose
A package that include extra validation rules for Laravel, this package approach focuses on actual usage, for example the ISBN test must validate against different real-world examples.

The following are the validation rules that are in development or have been developed.
- [x] Kuwaiti Civil ID - Official personal identification number for residents of Kuwait.
- [x] ISBN10 - Legacy 10-character identifier for books.
- [x] ISBN13 - Modern 13-digit identifier for books and related media.
- [x] ISNI - Unique identifier for authors, artists, and other contributors.
- [x] ISWC - Unique identifier for compositions.
- [x] ISTC - Unique identifier for textual works across editions and formats.
- [x] ISAN - Unique identifier for films, TV programmes, and audiovisual works.
- [x] ISMN - Unique identifier for printed composition publications.
- [x] ISRC - Unique identifier for sound recordings and sound videos.
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
- [x] MEID - Unique identifier for CDMA mobile devices.
- [x] ESN - Legacy electronic serial number for older CDMA devices.
- [x] MAC address - Unique hardware address for a network interface.
- [x] E.164 - International telephone number including country code.
- [x] IBAN - Cross-border bank account identifier.
- [x] BIC/SWIFT - Bank identifier code used in financial transfers.
- [x] Credit card - Payment card number validated with Luhn.
- [x] CVV - 3- or 4-digit card security code.
- [x] UPC-A - 12-digit North American retail product barcode.
- [x] GTIN14 - 14-digit trade item number for cases and pallets.
- [x] SSCC - Serial shipping container code for logistics units.
- [x] VIN - 17-character vehicle identification number.
- [x] CPF - Brazilian individual taxpayer registry number.
- [x] CNPJ - Brazilian company taxpayer registry number.
- [x] SSN - US Social Security Number format check.
- [x] Passport MRZ - TD3 passport machine-readable zone line check.
- [x] UUID - Universally unique identifier in 8-4-4-4-12 form.
- [x] ULID - 26-character lexicographically sortable unique ID.
- [x] JWT - JSON Web Token header.payload.signature structure.
- [x] Hex color - CSS hexadecimal color value.
- [x] Slug - URL-friendly lowercase hyphenated string.
- [x] Emirates ID - Official 15-digit UAE identity number.
- [x] Iqama - Saudi 10-digit national ID or resident Iqama number.
- [x] Egyptian NID - Egyptian 14-digit national identity number.
- [x] UPC-E - Compressed UPC barcode for small retail packages.
- [x] GTIN8 - 8-digit global trade item number for small products.
- [x] ITF-14 - 14-digit carton barcode using GTIN-14 structure.

## Installation

You can install the package via composer:

```bash
composer require aindot/extra-rules
```

### Translations

English and Arabic validation messages are included. Publish them if you want to customize the wording:

```bash
php artisan vendor:publish --tag=extra-rules-translations
```

Set the app locale to use Arabic messages:

```php
app()->setLocale('ar');
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

### Magic guess

```php
    use Aindot\ExtraRules\ExtraRules;
    use Aindot\ExtraRules\RuleType;

    $matches = (new ExtraRules)->magic('4111111111111111');
    // all matching RuleType cases

    $matches = (new ExtraRules)->magic('4111111111111111', ['credit_card', RuleType::Imei]);
    // only test the provided candidates
```

```bash
php artisan extra-rules:magic "4111111111111111"
php artisan extra-rules:magic "4111111111111111" --rules=credit_card,imei
```

### Generate samples

```php
    use Aindot\ExtraRules\ExtraRules;
    use Aindot\ExtraRules\RuleType;

    $imei = (new ExtraRules)->generate('imei');
    $ibans = (new ExtraRules)->generateMany(RuleType::Iban, 5);
```

```bash
php artisan extra-rules:generate imei
php artisan extra-rules:generate iban --count=5
```

## Credits

- [Abdullah](https://github.com/aindot)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
