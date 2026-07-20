<?php

namespace Aindot\ExtraRules\Tests\Unit;

use Aindot\ExtraRules\Rules\Jwt;
use Illuminate\Support\Facades\Validator;

it('Preform correct check', function ($id) {
    $data = [
        'Value' => $id,
    ];

    $validator = Validator::make($data, [
        'Value' => [new Jwt],
    ]);

    expect($validator->fails())->toBeFalse();
})->with(array (
  0 => 'eyJhbGciOiJIUzI1NiJ9.eyJzdWIiOiIxMjM0NTY3ODkwIn0.signature',
  1 => 'aaa.bbb.ccc',
  2 => 'header.payload.',
));

it('Preform false check', function ($id) {
    $data = [
        'Value' => $id,
    ];

    $validator = Validator::make($data, [
        'Value' => [new Jwt],
    ]);

    expect($validator->fails())->toBeTrue();
})->with(array (
  0 => 'aaa.bbb',
  1 => 'aaa',
  2 => 'aaa.bbb.ccc.ddd',
));
