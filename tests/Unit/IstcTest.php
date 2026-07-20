<?php

namespace Aindot\ExtraRules\Tests\Unit;

use Aindot\ExtraRules\Rules\Istc;
use Illuminate\Support\Facades\Validator;

it('Preform correct check', function ($id) {
    $data = [
        'Istc' => $id,
    ];

    $validator = Validator::make($data, [
        'Istc' => [new Istc],
    ]);

    expect($validator->fails())->toBeFalse();
})->with([
    '0A9200212B4A1053',
    '0A9-2002-12B4A105-3',
    'A02B20020000000E',
    '123456789ABCDEF8',
    '0000000000000001',
]);

it('Preform false check', function ($id) {
    $data = [
        'Istc' => $id,
    ];

    $validator = Validator::make($data, [
        'Istc' => [new Istc],
    ]);

    expect($validator->fails())->toBeTrue();
})->with([
    '0A9200212B4A1057',
    '0A9200212B4A105',
    'GHIJKLMNOPQRSTUV',
    '123',
]);
