<?php

namespace Aindot\ExtraRules\Tests\Unit;

use Aindot\ExtraRules\ExtraRules;
use Aindot\ExtraRules\RuleType;

it('guesses matching categories against all rules when the list is empty', function () {
    $matches = (new ExtraRules)->magic('4111111111111111');

    expect($matches)->toContain(RuleType::CreditCard)
        ->and(array_map(fn (RuleType $type) => $type->value, $matches))->not->toBeEmpty();
});

it('limits guessing to the provided string candidates', function () {
    $matches = (new ExtraRules)->magic('4111111111111111', ['credit_card', 'imei', 'uuid']);

    expect($matches)->toBe([RuleType::CreditCard]);
});

it('accepts RuleType enums in the candidate list', function () {
    $matches = (new ExtraRules)->magic(
        '978-0-306-40615-7',
        [RuleType::Isbn13, RuleType::Ean13, RuleType::Imei]
    );

    expect($matches)->toContain(RuleType::Isbn13)
        ->and($matches)->toContain(RuleType::Ean13)
        ->and($matches)->not->toContain(RuleType::Imei);
});

it('accepts mixed strings and enums as candidates', function () {
    $matches = (new ExtraRules)->magic(
        '550e8400-e29b-41d4-a716-446655440000',
        ['imei', RuleType::Uuid, 'slug']
    );

    expect($matches)->toContain(RuleType::Uuid)
        ->and($matches)->toContain(RuleType::Slug)
        ->and($matches)->not->toContain(RuleType::Imei);
});

it('returns an empty list when nothing matches', function () {
    $matches = (new ExtraRules)->magic('!!!', [RuleType::Imei, RuleType::Uuid]);

    expect($matches)->toBe([]);
});

it('resolves common aliases for candidate names', function () {
    $matches = (new ExtraRules)->magic('+14155552671', ['phone', 'creditcard']);

    expect($matches)->toBe([RuleType::E164]);
});
