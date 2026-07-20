<?php

namespace Aindot\ExtraRules\Tests\Unit;

use Aindot\ExtraRules\Rules\Cpf;
use Illuminate\Support\Facades\Validator;

it('Preform correct check', function ($id) {
    $data = [
        'Value' => $id,
    ];

    $validator = Validator::make($data, [
        'Value' => [new Cpf],
    ]);

    expect($validator->fails())->toBeFalse();
})->with(array (
  0 => '529.982.247-25',
  1 => '52998224725',
  2 => '11144477735',
));

it('Preform false check', function ($id) {
    $data = [
        'Value' => $id,
    ];

    $validator = Validator::make($data, [
        'Value' => [new Cpf],
    ]);

    expect($validator->fails())->toBeTrue();
})->with(array (
  0 => '529.982.247-26',
  1 => '11111111111',
  2 => '123',
  3 => 'abcdefghijk',
));
