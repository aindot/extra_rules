<?php

namespace Aindot\ExtraRules\Tests\Unit;

use Aindot\ExtraRules\ExtraRules;
use Aindot\ExtraRules\RuleType;
use Illuminate\Contracts\Validation\ValidationRule;

it('generates values that pass their own validators', function (RuleType $type) {
    $value = (new ExtraRules)->generate($type);

    /** @var ValidationRule $rule */
    $rule = new ($type->ruleClass());
    $failed = false;
    $rule->validate('value', $value, function () use (&$failed): void {
        $failed = true;
    });

    expect($failed)->toBeFalse()
        ->and($value)->toBeString()
        ->and($value)->not->toBeEmpty();
})->with(RuleType::cases());

it('generates multiple values', function () {
    $values = (new ExtraRules)->generateMany('uuid', 3);

    expect($values)->toHaveCount(3)
        ->and($values[0])->not->toBe($values[1]);
});

it('accepts string rule names', function () {
    $value = (new ExtraRules)->generate('credit_card');

    expect($value)->toStartWith('4');
});

it('generates from the command line', function () {
    $this->artisan('extra-rules:generate', ['rule' => 'imei'])
        ->expectsOutputToContain('Generated')
        ->assertSuccessful();
});

it('generates multiple values from the command line', function () {
    $this->artisan('extra-rules:generate', [
        'rule' => 'cvv',
        '--count' => 3,
    ])->assertSuccessful();
});

it('fails the command for unknown rules', function () {
    $this->artisan('extra-rules:generate', ['rule' => 'not-a-rule'])
        ->assertFailed();
});
