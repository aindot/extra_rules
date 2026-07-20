<?php

namespace Aindot\ExtraRules\Tests\Unit;

use Aindot\ExtraRules\Rules\Gtin8;
use Illuminate\Support\Facades\Validator;

it('Preform correct check', function ($id) {
    $data = ['Value' => $id];
    $validator = Validator::make($data, ['Value' => [new Gtin8]]);
    expect($validator->fails())->toBeFalse();
})->with([
    '96385074',
    '55123457',
    '12345670',
    '9638-5074',
]);

it('Preform false check', function ($id) {
    $data = ['Value' => $id];
    $validator = Validator::make($data, ['Value' => [new Gtin8]]);
    expect($validator->fails())->toBeTrue();
})->with([
    '96385075',
    '1234567',
    '123456789',
    'abcdefgh',
]);
