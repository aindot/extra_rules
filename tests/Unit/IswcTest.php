<?php

namespace Aindot\ExtraRules\Tests\Unit;

use Aindot\ExtraRules\Rules\Iswc;
use Illuminate\Support\Facades\Validator;

it('Preform correct check', function ($id) {
    $data = [
        'Iswc' => $id,
    ];

    $validator = Validator::make($data, [
        'Iswc' => [new Iswc],
    ]);

    expect($validator->fails())->toBeFalse();
})->with([
    'T-034.524.680-8',
    'T0345246808',
    'T-000.000.001-9',
    'T1234567895',
    't-034.524.680-8',
]);

it('Preform false check', function ($id) {
    $data = [
        'Iswc' => $id,
    ];

    $validator = Validator::make($data, [
        'Iswc' => [new Iswc],
    ]);

    expect($validator->fails())->toBeTrue();
})->with([
    'T-034.524.680-1',
    'T0345246800',
    '0345246808',
    'T123',
]);
