<?php

namespace Aindot\ExtraRules\Tests\Unit;

use Aindot\ExtraRules\Rules\Ismn;
use Illuminate\Support\Facades\Validator;

it('Preform correct check', function ($id) {
    $data = [
        'Ismn' => $id,
    ];

    $validator = Validator::make($data, [
        'Ismn' => [new Ismn],
    ]);

    expect($validator->fails())->toBeFalse();
})->with([
    '979-0-3452-4680-5',
    '9790060115615',
    '979-0-2306-7118-7',
    'M230671187',
    'M-2306-7118-7',
    '9790345246805',
]);

it('Preform false check', function ($id) {
    $data = [
        'Ismn' => $id,
    ];

    $validator = Validator::make($data, [
        'Ismn' => [new Ismn],
    ]);

    expect($validator->fails())->toBeTrue();
})->with([
    '9790060115614',
    '9780306406157',
    '9790345246806',
    'M230671188',
]);
