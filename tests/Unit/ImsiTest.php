<?php

namespace Aindot\ExtraRules\Tests\Unit;

use Aindot\ExtraRules\Rules\Imsi;
use Illuminate\Support\Facades\Validator;

it('Preform correct check', function ($value) {
    $data = [
        'Imsi' => $value,
    ];

    $validator = Validator::make($data, [
        'Imsi' => [new Imsi],
    ]);

    expect($validator->fails())->toBeFalse();
})->with([
    '310150123456789',
    '429011234567890',
    '460001234567890',
    '234151234567890',
    '310-150-123456789',
    310150123456789,
]);

it('Preform false check', function ($value) {
    $data = [
        'Imsi' => $value,
    ];

    $validator = Validator::make($data, [
        'Imsi' => [new Imsi],
    ]);

    expect($validator->fails())->toBeTrue();
})->with([
    '1234567890123',
    '3101501234567890',
    '099150123456789',
    'abcdefghijklmno',
]);
