<?php

namespace Aindot\ExtraRules\Tests\Unit;

use Aindot\ExtraRules\Rules\Iccid;
use Illuminate\Support\Facades\Validator;

it('Preform correct check', function ($value) {
    $data = [
        'Iccid' => $value,
    ];

    $validator = Validator::make($data, [
        'Iccid' => [new Iccid],
    ]);

    expect($validator->fails())->toBeFalse();
})->with([
    '894410001010145678',
    '890141032111185101',
    '8991101200003204514',
    '89 4410 0010 1014 5678',
    '894410001010145678',
]);

it('Preform false check', function ($value) {
    $data = [
        'Iccid' => $value,
    ];

    $validator = Validator::make($data, [
        'Iccid' => [new Iccid],
    ]);

    expect($validator->fails())->toBeTrue();
})->with([
    '894410001010145679',
    '794410001010145678',
    '12345',
    'abcdefghijklmnopqrs',
]);
