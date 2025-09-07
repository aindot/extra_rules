<?php

namespace Aindot\ExtraRules\Tests\Unit;

use Aindot\ExtraRules\Rules\Imei;
use Illuminate\Support\Facades\Validator;

it('Preform correct check', function ($value) {
    $data = [
        'Imei' => $value,
    ];
    $validator = Validator::make($data, [
        'Imei' => [new Imei],
    ]);
    expect($validator->fails())->toBeFalse();
})->with([
    350077523237513,
    354809104295874,
    490154203237518,
    351451208401216,
    352857871997190,
    359100581997199,
]);
