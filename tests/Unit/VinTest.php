<?php

namespace Aindot\ExtraRules\Tests\Unit;

use Aindot\ExtraRules\Rules\Vin;
use Illuminate\Support\Facades\Validator;

it('Preform correct check', function ($id) {
    $data = [
        'Value' => $id,
    ];

    $validator = Validator::make($data, [
        'Value' => [new Vin],
    ]);

    expect($validator->fails())->toBeFalse();
})->with(array (
  0 => '1HGCM82633A004352',
  1 => 'JH4KA8268MC000000',
  2 => '11111111111111111',
  3 => 'WAUZZZ8K19AN12345',
));

it('Preform false check', function ($id) {
    $data = [
        'Value' => $id,
    ];

    $validator = Validator::make($data, [
        'Value' => [new Vin],
    ]);

    expect($validator->fails())->toBeTrue();
})->with(array (
  0 => '1HGCM82633A004353',
  1 => '1HGCM82633A00435',
  2 => '1HGCM82633A00435I',
  3 => 'not-a-valid-vin!!',
));
