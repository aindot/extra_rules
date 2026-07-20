<?php

namespace Aindot\ExtraRules\Tests\Unit;

use Aindot\ExtraRules\Rules\Gtin13;
use Illuminate\Support\Facades\Validator;

it('Preform correct check', function ($id) {
    $data = [
        'Gtin' => $id,
    ];

    $validator = Validator::make($data, [
        'Gtin' => [new Gtin13],
    ]);

    expect($validator->fails())->toBeFalse();
})->with([
    '5012345678900',
    '0799439112766',
    '5901234123457',
    '6650-010026-275',
    '6281085037 271',
    '8711253001202',
    '4012345123456',
]);

it('Preform false check', function ($id) {
    $data = [
        'Gtin' => $id,
    ];

    $validator = Validator::make($data, [
        'Gtin' => [new Gtin13],
    ]);

    expect($validator->fails())->toBeTrue();
})->with([
    '5012345678901',
    '123',
    'abcdefghijklm',
]);
