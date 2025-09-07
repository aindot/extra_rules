<?php

namespace Aindot\ExtraRules\Tests\Unit;

use Aindot\ExtraRules\Rules\Ean13;
use Illuminate\Support\Facades\Validator;

it('Preform correct check', function ($id) {

    $data = [
        'Isbn' => $id,
    ];

    $validator = Validator::make($data, [
        'Isbn' => [new Ean13],
    ]);

    expect($validator->fails())->toBeFalse();

})->with([
    '5012345678900',
    '0799439112766',
    '5901234123457',
    '6650-010026-275',
    '6281085037 271',
    '8711253001202',
    '4012345123456',
]);
