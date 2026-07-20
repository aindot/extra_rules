<?php

namespace Aindot\ExtraRules\Tests\Unit;

use Aindot\ExtraRules\Rules\CreditCard;
use Illuminate\Support\Facades\Validator;

it('Preform correct check', function ($id) {
    $data = [
        'Value' => $id,
    ];

    $validator = Validator::make($data, [
        'Value' => [new CreditCard],
    ]);

    expect($validator->fails())->toBeFalse();
})->with(array (
  0 => '4111111111111111',
  1 => '5500 0000 0000 0004',
  2 => '340000000000009',
  3 => '6011000000000004',
));

it('Preform false check', function ($id) {
    $data = [
        'Value' => $id,
    ];

    $validator = Validator::make($data, [
        'Value' => [new CreditCard],
    ]);

    expect($validator->fails())->toBeTrue();
})->with(array (
  0 => '4111111111111112',
  1 => '1234567890123',
  2 => 'abcd',
  3 => '411111111111',
));
