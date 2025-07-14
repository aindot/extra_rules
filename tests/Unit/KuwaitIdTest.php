<?php

namespace Aindot\ExtraRules\Tests\Unit;

use Aindot\ExtraRules\Rules\KuwaitCivilId;
use Illuminate\Support\Facades\Validator;

it('Preform correct check', function ($id) {
    $data = [
        'KuwaitiId' => $id,
    ];
    $validator = Validator::make($data, [
        'KuwaitiId' => [new KuwaitCivilId],
    ]);
    expect($validator->fails())->toBeFalse();

})->with([282021504193, 279040907388, 283091804241]);
