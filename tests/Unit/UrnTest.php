<?php

namespace Aindot\ExtraRules\Tests\Unit;

use Aindot\ExtraRules\Rules\Urn;
use Illuminate\Support\Facades\Validator;

it('Preform correct check', function ($id) {
    $data = [
        'Urn' => $id,
    ];

    $validator = Validator::make($data, [
        'Urn' => [new Urn],
    ]);

    expect($validator->fails())->toBeFalse();
})->with([
    'urn:isbn:0451450523',
    'urn:issn:0167-6423',
    'urn:nbn:de:bvb:19-146642',
    'urn:uuid:6e8bc430-9c3a-11d9-9669-0800200c9a66',
    'URN:ISBN:0451450523',
    'urn:ietf:rfc:2648',
]);

it('Preform false check', function ($id) {
    $data = [
        'Urn' => $id,
    ];

    $validator = Validator::make($data, [
        'Urn' => [new Urn],
    ]);

    expect($validator->fails())->toBeTrue();
})->with([
    'isbn:0451450523',
    'urn:',
    'urn:isbn',
    'not-a-urn',
]);
