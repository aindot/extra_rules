<?php

namespace Aindot\ExtraRules\Tests\Unit;

use Aindot\ExtraRules\Rules\Doi;
use Illuminate\Support\Facades\Validator;

it('Preform correct check', function ($id) {
    $data = [
        'Doi' => $id,
    ];

    $validator = Validator::make($data, [
        'Doi' => [new Doi],
    ]);

    expect($validator->fails())->toBeFalse();
})->with([
    '10.1000/182',
    '10.1038/nphys1170',
    '10.1016/j.cell.2019.01.001',
    'doi:10.1000/182',
    'https://doi.org/10.1000/182',
    'http://dx.doi.org/10.1038/nphys1170',
    '10.1109/5.771073',
]);

it('Preform false check', function ($id) {
    $data = [
        'Doi' => $id,
    ];

    $validator = Validator::make($data, [
        'Doi' => [new Doi],
    ]);

    expect($validator->fails())->toBeTrue();
})->with([
    '10.1000',
    '11.1000/182',
    '10.abc/182',
    'not-a-doi',
]);
