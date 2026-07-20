<?php

namespace Aindot\ExtraRules\Tests\Unit;

use Aindot\ExtraRules\Rules\Sscc;
use Illuminate\Support\Facades\Validator;

it('Preform correct check', function ($id) {
    $data = [
        'Value' => $id,
    ];

    $validator = Validator::make($data, [
        'Value' => [new Sscc],
    ]);

    expect($validator->fails())->toBeFalse();
})->with(array (
  0 => '006141411234567890',
  1 => '00 614141 123456789 0',
));

it('Preform false check', function ($id) {
    $data = [
        'Value' => $id,
    ];

    $validator = Validator::make($data, [
        'Value' => [new Sscc],
    ]);

    expect($validator->fails())->toBeTrue();
})->with(array (
  0 => '006141411234567891',
  1 => '00614141123456789',
  2 => 'abcdefghijklmnopqr',
));
