<?php

namespace Aindot\ExtraRules\Tests\Unit;

use Aindot\ExtraRules\Rules\Nbn;
use Illuminate\Support\Facades\Validator;

it('Preform correct check', function ($id) {
    $data = [
        'Nbn' => $id,
    ];

    $validator = Validator::make($data, [
        'Nbn' => [new Nbn],
    ]);

    expect($validator->fails())->toBeFalse();
})->with([
    'urn:nbn:de:bvb:19-146642',
    'urn:nbn:fi:fe2019102374211',
    'urn:nbn:se:uu:diva-12345',
    'fi-fe2019102374211',
    'de:bvb:19-146642',
]);

it('Preform false check', function ($id) {
    $data = [
        'Nbn' => $id,
    ];

    $validator = Validator::make($data, [
        'Nbn' => [new Nbn],
    ]);

    expect($validator->fails())->toBeTrue();
})->with([
    'ab',
    'urn:isbn:0451450523',
    '12345',
    'not a valid nbn!',
]);
