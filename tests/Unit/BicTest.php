<?php

namespace Aindot\ExtraRules\Tests\Unit;

use Aindot\ExtraRules\Rules\Bic;
use Illuminate\Support\Facades\Validator;

it('Preform correct check', function ($id) {
    $data = [
        'Value' => $id,
    ];

    $validator = Validator::make($data, [
        'Value' => [new Bic],
    ]);

    expect($validator->fails())->toBeFalse();
})->with(array (
  0 => 'DEUTDEFF',
  1 => 'DEUTDEFF500',
  2 => 'NEDSZAJJ',
  3 => 'BOFAUS3N',
));

it('Preform false check', function ($id) {
    $data = [
        'Value' => $id,
    ];

    $validator = Validator::make($data, [
        'Value' => [new Bic],
    ]);

    expect($validator->fails())->toBeTrue();
})->with(array (
  0 => 'DEUTDEF',
  1 => 'DEUTDEFF5000',
  2 => 'DEUT12FF',
  3 => '1234DEFF',
));
