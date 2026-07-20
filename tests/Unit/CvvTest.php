<?php

namespace Aindot\ExtraRules\Tests\Unit;

use Aindot\ExtraRules\Rules\Cvv;
use Illuminate\Support\Facades\Validator;

it('Preform correct check', function ($id) {
    $data = [
        'Value' => $id,
    ];

    $validator = Validator::make($data, [
        'Value' => [new Cvv],
    ]);

    expect($validator->fails())->toBeFalse();
})->with(array (
  0 => '123',
  1 => '1234',
  2 => '000',
  3 => '9999',
));

it('Preform false check', function ($id) {
    $data = [
        'Value' => $id,
    ];

    $validator = Validator::make($data, [
        'Value' => [new Cvv],
    ]);

    expect($validator->fails())->toBeTrue();
})->with(array (
  0 => '12',
  1 => '12345',
  2 => '12a',
  3 => 'ab',
));
