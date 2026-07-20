<?php

namespace Aindot\ExtraRules\Tests\Unit;

use Aindot\ExtraRules\Rules\Cnpj;
use Illuminate\Support\Facades\Validator;

it('Preform correct check', function ($id) {
    $data = [
        'Value' => $id,
    ];

    $validator = Validator::make($data, [
        'Value' => [new Cnpj],
    ]);

    expect($validator->fails())->toBeFalse();
})->with(array (
  0 => '11.222.333/0001-81',
  1 => '11222333000181',
  2 => '34.028.316/0001-03',
));

it('Preform false check', function ($id) {
    $data = [
        'Value' => $id,
    ];

    $validator = Validator::make($data, [
        'Value' => [new Cnpj],
    ]);

    expect($validator->fails())->toBeTrue();
})->with(array (
  0 => '11.222.333/0001-82',
  1 => '11111111111111',
  2 => '123',
  3 => 'abcdefghijklmn',
));
