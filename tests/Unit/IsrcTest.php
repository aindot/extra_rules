<?php

namespace Aindot\ExtraRules\Tests\Unit;

use Aindot\ExtraRules\Rules\Isrc;
use Illuminate\Support\Facades\Validator;

it('Preform correct check', function ($id) {
    $data = [
        'Isrc' => $id,
    ];

    $validator = Validator::make($data, [
        'Isrc' => [new Isrc],
    ]);

    expect($validator->fails())->toBeFalse();
})->with([
    'USRC17607839',
    'US-RC1-76-07839',
    'GBAYE0600730',
    'USSM19900123',
    'QZES81912345',
    'uk-a1b-20-12345',
]);

it('Preform false check', function ($id) {
    $data = [
        'Isrc' => $id,
    ];

    $validator = Validator::make($data, [
        'Isrc' => [new Isrc],
    ]);

    expect($validator->fails())->toBeTrue();
})->with([
    'USRC1760783',
    'USRC176078390',
    '12RC17607839',
    'USRC1AB07839',
]);
