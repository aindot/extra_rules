<?php

namespace Aindot\ExtraRules\Tests\Unit;

use Aindot\ExtraRules\Rules\E164;
use Illuminate\Support\Facades\Validator;

it('Preform correct check', function ($id) {
    $data = [
        'Value' => $id,
    ];

    $validator = Validator::make($data, [
        'Value' => [new E164],
    ]);

    expect($validator->fails())->toBeFalse();
})->with(array (
  0 => '+14155552671',
  1 => '14155552671',
  2 => '+442071838750',
  3 => '+966501234567',
  4 => '123',
));

it('Preform false check', function ($id) {
    $data = [
        'Value' => $id,
    ];

    $validator = Validator::make($data, [
        'Value' => [new E164],
    ]);

    expect($validator->fails())->toBeTrue();
})->with(array (
  0 => '+0123456789',
  1 => '++14155552671',
  2 => 'abcdefghij',
  3 => '+1234567890123456',
));
