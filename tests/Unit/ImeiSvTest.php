<?php

namespace Aindot\ExtraRules\Tests\Unit;

use Aindot\ExtraRules\Rules\ImeiSv;
use Illuminate\Support\Facades\Validator;

it('Preform correct check', function ($value) {
    $data = [
        'ImeiSv' => $value,
    ];

    $validator = Validator::make($data, [
        'ImeiSv' => [new ImeiSv],
    ]);

    expect($validator->fails())->toBeFalse();
})->with([
    '3568680000414120',
    '4901542032375100',
    '3500775232375101',
    '35-686800-004141-20',
    3568680000414120,
]);

it('Preform false check', function ($value) {
    $data = [
        'ImeiSv' => $value,
    ];

    $validator = Validator::make($data, [
        'ImeiSv' => [new ImeiSv],
    ]);

    expect($validator->fails())->toBeTrue();
})->with([
    '356868000041412',
    '35686800004141201',
    'abcdefghijklmnop',
    350077523237513,
]);
