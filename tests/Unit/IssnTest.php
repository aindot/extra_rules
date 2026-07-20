<?php

namespace Aindot\ExtraRules\Tests\Unit;

use Aindot\ExtraRules\Rules\Issn;
use Illuminate\Support\Facades\Validator;

it('Preform correct check', function ($id) {
    $data = [
        'Issn' => $id,
    ];

    $validator = Validator::make($data, [
        'Issn' => [new Issn],
    ]);

    expect($validator->fails())->toBeFalse();
})->with([
    '0378-5955',
    '0317-8471',
    '2049-3630',
    '0028-0836',
    '2434-561X',
    '2434561x',
    '03785955',
]);

it('Preform false check', function ($id) {
    $data = [
        'Issn' => $id,
    ];

    $validator = Validator::make($data, [
        'Issn' => [new Issn],
    ]);

    expect($validator->fails())->toBeTrue();
})->with([
    '0378-5956',
    '0317-8472',
    '1234',
    'abcdefgh',
]);
