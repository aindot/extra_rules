<?php

namespace Aindot\ExtraRules\Tests\Unit;

use Aindot\ExtraRules\Rules\Isni;
use Illuminate\Support\Facades\Validator;

it('Preform correct check', function ($id) {
    $data = [
        'Isni' => $id,
    ];

    $validator = Validator::make($data, [
        'Isni' => [new Isni],
    ]);

    expect($validator->fails())->toBeFalse();
})->with([
    '0000 0001 2281 955X',
    '000000012281955X',
    '0000-0001-2281-955X',
    '0000000121463571',
    '0000000121032683',
    '0000000110918374',
]);

it('Preform false check', function ($id) {
    $data = [
        'Isni' => $id,
    ];

    $validator = Validator::make($data, [
        'Isni' => [new Isni],
    ]);

    expect($validator->fails())->toBeTrue();
})->with([
    '0000 0001 1111 955X',
    '0000000122819551',
    '00000001228195',
    'abcdefghijklmnop',
]);
