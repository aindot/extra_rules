<?php

namespace Aindot\ExtraRules\Tests\Unit;

use Aindot\ExtraRules\Rules\Isan;
use Illuminate\Support\Facades\Validator;

it('Preform correct check', function ($id) {
    $data = [
        'Isan' => $id,
    ];

    $validator = Validator::make($data, [
        'Isan' => [new Isan],
    ]);

    expect($validator->fails())->toBeFalse();
})->with([
    '0000-0000-D07A-0090-Q',
    '00000000D07A0090Q',
    '0000-0000-D07A-0090-Q-0000-0000-X',
    '00000000D07A0090Q00000000X',
    '0000-0001-8CFA-0000-I-0000-0000-K',
    'ISAN 0000-0000-D07A-0090-Q',
]);

it('Preform false check', function ($id) {
    $data = [
        'Isan' => $id,
    ];

    $validator = Validator::make($data, [
        'Isan' => [new Isan],
    ]);

    expect($validator->fails())->toBeTrue();
})->with([
    '0000-0001-8CFA-0000-A-0000-0000-K',
    '00000000D07A0090A',
    '00000000D07A009',
    'not-an-isan',
]);
