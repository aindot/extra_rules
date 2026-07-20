<?php

namespace Aindot\ExtraRules\Tests\Unit;

use Aindot\ExtraRules\Rules\PassportMrz;
use Illuminate\Support\Facades\Validator;

it('Preform correct check', function ($id) {
    $data = [
        'Value' => $id,
    ];

    $validator = Validator::make($data, [
        'Value' => [new PassportMrz],
    ]);

    expect($validator->fails())->toBeFalse();
})->with(array (
  0 => 'L898902C36UTO7408122F1204159ZE184226B<<<<<10',
));

it('Preform false check', function ($id) {
    $data = [
        'Value' => $id,
    ];

    $validator = Validator::make($data, [
        'Value' => [new PassportMrz],
    ]);

    expect($validator->fails())->toBeTrue();
})->with(array (
  0 => 'L898902C36UTO7408122F1204159ZE184226B<<<<<11',
  1 => 'L898902C3',
  2 => 'not-an-mrz-line',
));
