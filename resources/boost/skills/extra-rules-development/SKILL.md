---
name: extra-rules-development
description: Use aindot/extra-rules Laravel validation rules (IBAN, IMEI, ISBN, national IDs, barcodes, etc.), magic guess, and sample generation. Use when adding form/request validation, guessing identifier types, or generating test fixtures for specialty formats.
---

# Extra Rules (aindot/extra-rules)

Laravel `ValidationRule` classes for real-world identifiers (national IDs, barcodes, telecom, finance, media). Prefer these over ad-hoc regex when a matching rule exists.

## Install & messages

```bash
composer require aindot/extra-rules
php artisan vendor:publish --tag=extra-rules-translations
```

English and Arabic messages ship under `extra-rules::validation.*`. Locale: `app()->setLocale('ar')`.

## Validation usage

Rules live in `Aindot\ExtraRules\Rules\*` and implement `Illuminate\Contracts\Validation\ValidationRule`.

```php
use Aindot\ExtraRules\Rules\Iban;
use Aindot\ExtraRules\Rules\KuwaitCivilId;
use Illuminate\Support\Facades\Validator;

Validator::make($data, [
    'civil_id' => ['required', new KuwaitCivilId],
    'iban' => ['required', new Iban],
]);
```

Same pattern in Form Requests: `new \Aindot\ExtraRules\Rules\Isbn13`.

## Rule catalog (RuleType value → class)

Use `Aindot\ExtraRules\RuleType` string values (snake_case) or enum cases. Classes: `Aindot\ExtraRules\Rules\{Name}`.

| Area | Keys |
|------|------|
| National IDs | `kuwait_civil_id`, `emirates_id`, `iqama`, `egyptian_nid`, `cpf`, `cnpj`, `ssn`, `passport_mrz` |
| Books/media | `isbn10`, `isbn13`, `issn`, `isni`, `iswc`, `istc`, `isan`, `ismn`, `isrc`, `doi`, `nbn`, `urn` |
| Barcodes/trade | `ean8`, `ean13`, `upc_a`, `upc_e`, `gtin8`, `gtin13`, `gtin14`, `itf14`, `sscc` |
| Telecom/device | `imei`, `imeisv`, `imsi`, `iccid`, `meid`, `esn`, `mac_address`, `e164` |
| Finance | `iban`, `bic`, `credit_card`, `cvv` |
| Other | `email`, `uuid`, `ulid`, `jwt`, `hex_color`, `slug`, `vin` |

Aliases accepted by `RuleType::fromMixed()` include: `swift`→`bic`, `phone`→`e164`, `mrz`→`passport_mrz`, `kuwait_id`→`kuwait_civil_id`, `uae_id`→`emirates_id`.

## Magic guess

```php
use Aindot\ExtraRules\ExtraRules;
use Aindot\ExtraRules\Facades\ExtraRules as ExtraRulesFacade;
use Aindot\ExtraRules\RuleType;

$matches = (new ExtraRules)->magic($value); // list<RuleType>
$matches = ExtraRulesFacade::magic($value, ['credit_card', RuleType::Imei]);
```

```bash
php artisan extra-rules:magic "4111111111111111"
php artisan extra-rules:magic "4111111111111111" --rules=credit_card,imei
```

## Generate valid samples

```php
$imei = (new ExtraRules)->generate('imei');
$ibans = (new ExtraRules)->generateMany(RuleType::Iban, 5);
```

```bash
php artisan extra-rules:generate imei
php artisan extra-rules:generate iban --count=5
```

Use generators for tests/fixtures so values pass the same checksum rules as production validation.

## Package conventions (contributing)

- New rule: `src/Rules/FooBar.php` implementing `ValidationRule`, use `RejectsInvalidValue` + `extra-rules::validation.*` keys.
- Register in `RuleType` enum (`case`, `ruleClass()`, aliases if needed) and `Generator` for sample output.
- Add Pest unit tests with real-world valid/invalid examples (checksum-aware, not only format).
- Translations: `resources/lang/{en,ar}/validation.php` (and commands if CLI text changes).
