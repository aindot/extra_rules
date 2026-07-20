<?php

namespace Aindot\ExtraRules\Tests\Unit;

use Aindot\ExtraRules\Rules\HexColor;
use Illuminate\Support\Facades\Validator;

it('Preform correct check', function ($id) {
    $data = [
        'Value' => $id,
    ];

    $validator = Validator::make($data, [
        'Value' => [new HexColor],
    ]);

    expect($validator->fails())->toBeFalse();
})->with(array (
  0 => '#fff',
  1 => '#FFFFFF',
  2 => '#000000ff',
  3 => 'abc',
  4 => 'AABBCC',
  5 => '#a1b2c3d4',
));

it('Preform false check', function ($id) {
    $data = [
        'Value' => $id,
    ];

    $validator = Validator::make($data, [
        'Value' => [new HexColor],
    ]);

    expect($validator->fails())->toBeTrue();
})->with(array (
  0 => '#gg0000',
  1 => '#ffff',
  2 => 'red',
  3 => '#12345',
));
