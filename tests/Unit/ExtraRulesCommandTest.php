<?php

namespace Aindot\ExtraRules\Tests\Unit;

it('guesses categories from the command line', function () {
    $this->artisan('extra-rules:magic', ['input' => '4111111111111111'])
        ->expectsOutputToContain('CreditCard')
        ->assertSuccessful();
});

it('limits command guessing with --rules', function () {
    $this->artisan('extra-rules:magic', [
        'input' => '4111111111111111',
        '--rules' => 'credit_card,imei',
    ])
        ->expectsOutputToContain('CreditCard')
        ->doesntExpectOutputToContain('Imei')
        ->assertSuccessful();
});

it('reports when nothing matches', function () {
    $this->artisan('extra-rules:magic', [
        'input' => '!!!',
        '--rules' => 'imei,uuid',
    ])
        ->expectsOutputToContain('No matching validation categories found.')
        ->assertSuccessful();
});

it('fails on unknown rule names', function () {
    $this->artisan('extra-rules:magic', [
        'input' => '123',
        '--rules' => 'not-a-rule',
    ])->assertFailed();
});
