<?php

namespace Aindot\ExtraRules\Tests\Unit;

use Aindot\ExtraRules\Rules\Ean8;
use Illuminate\Support\Facades\Validator;

it('Preform correct check', function ($id) {
    $data = [
        'Ean' => $id,
    ];

    $validator = Validator::make($data, [
        'Ean' => [new Ean8],
    ]);

    expect($validator->fails())->toBeFalse();
})->with([
    '96385074',
    '55123457',
    '12345670',
    '9638-5074',
    '40123455',
]);

it('Preform false check', function ($id) {
    $data = [
        'Ean' => $id,
    ];

    $validator = Validator::make($data, [
        'Ean' => [new Ean8],
    ]);

    expect($validator->fails())->toBeTrue();
})->with([
    '96385075',
    '1234567',
    '123456789',
    'abcdefgh',
]);
