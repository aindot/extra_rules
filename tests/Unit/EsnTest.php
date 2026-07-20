<?php

namespace Aindot\ExtraRules\Tests\Unit;

use Aindot\ExtraRules\Rules\Esn;
use Illuminate\Support\Facades\Validator;

it('Preform correct check', function ($id) {
    $data = [
        'Value' => $id,
    ];

    $validator = Validator::make($data, [
        'Value' => [new Esn],
    ]);

    expect($validator->fails())->toBeFalse();
})->with(array (
  0 => '80ABCDEF',
  1 => '80-AB-CD-EF',
  2 => '12345678901',
  3 => 'A1B2C3D4',
));

it('Preform false check', function ($id) {
    $data = [
        'Value' => $id,
    ];

    $validator = Validator::make($data, [
        'Value' => [new Esn],
    ]);

    expect($validator->fails())->toBeTrue();
})->with(array (
  0 => '80ABCDE',
  1 => '80ABCDEF0',
  2 => 'GHIJKLMN',
  3 => '12345',
));
