<?php

namespace Aindot\ExtraRules\Tests\Unit;

use Aindot\ExtraRules\ExtraRules;
use Aindot\ExtraRules\RuleType;
use InvalidArgumentException;

it('redacts each supported secret type', function (RuleType $type) {
    $secret = (new ExtraRules)->generate($type);
    $text = "before {$secret} after";

    expect((new ExtraRules)->redact($text))
        ->toBe("before [REDACTED:{$type->value}] after");
})->with([
    RuleType::Iban,
    RuleType::ApiKey,
    RuleType::BearerToken,
    RuleType::Jwt,
    RuleType::KuwaitCivilId,
    RuleType::CreditCard,
    RuleType::PrivateKey,
]);

it('leaves text without secrets unchanged', function () {
    $text = 'Nothing sensitive here, just plain words.';

    expect((new ExtraRules)->redact($text))->toBe($text);
});

it('redacts multiple secrets in one string', function () {
    $text = 'card 4111111111111111 and iban GB82WEST12345698765432';

    expect((new ExtraRules)->redact($text))
        ->toBe('card [REDACTED:credit_card] and iban [REDACTED:iban]');
});

it('redacts a bearer jwt once as a bearer token', function () {
    $jwt = 'eyJhbGciOiJIUzI1NiJ9.eyJzdWIiOiIxMjM0NTY3ODkwIn0.signature';
    $matches = (new ExtraRules)->detect("Authorization: Bearer {$jwt}");

    expect($matches)->toHaveCount(1)
        ->and($matches[0]['type'])->toBe(RuleType::BearerToken);
});

it('limits redaction to the provided types', function () {
    $text = '4111111111111111 GB82WEST12345698765432';

    expect((new ExtraRules)->redact($text, ['iban']))
        ->toBe('4111111111111111 [REDACTED:iban]');
});

it('detects secret metadata without exposing values', function () {
    $secret = '4111111111111111';
    $text = "card {$secret}";
    $matches = (new ExtraRules)->detect($text);

    expect($matches)->toHaveCount(1)
        ->and($matches[0]['type'])->toBe(RuleType::CreditCard)
        ->and(substr($text, $matches[0]['offset'], $matches[0]['length']))->toBe($secret);
});

it('rejects rule types that cannot be redacted', function () {
    (new ExtraRules)->redact('anything', ['uuid']);
})->throws(InvalidArgumentException::class);

it('accepts RuleType cases as redaction types', function () {
    expect((new ExtraRules)->redact('4111111111111111', [RuleType::CreditCard]))
        ->toBe('[REDACTED:credit_card]');
});
