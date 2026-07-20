<?php

namespace Aindot\ExtraRules\Tests\Unit;

use Aindot\ExtraRules\Rules\Meid;
use Illuminate\Support\Facades\Validator;

it('Preform correct check', function ($id) {
    $data = [
        'Value' => $id,
    ];

    $validator = Validator::make($data, [
        'Value' => [new Meid],
    ]);

    expect($validator->fails())->toBeFalse();
})->with(array (
  0 => 'A10000009296FD',
  1 => 'FF000001234561',
  2 => 'A1-000000-9296FD',
  3 => '293608736500703711',
));

it('Preform false check', function ($id) {
    $data = [
        'Value' => $id,
    ];

    $validator = Validator::make($data, [
        'Value' => [new Meid],
    ]);

    expect($validator->fails())->toBeTrue();
})->with(array (
  0 => 'A10000009296FE',
  1 => 'FF00000123456',
  2 => 'GHIJKLMNOPQRST',
  3 => '123',
));
