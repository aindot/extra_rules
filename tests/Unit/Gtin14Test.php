<?php

namespace Aindot\ExtraRules\Tests\Unit;

use Aindot\ExtraRules\Rules\Gtin14;
use Illuminate\Support\Facades\Validator;

it('Preform correct check', function ($id) {
    $data = [
        'Value' => $id,
    ];

    $validator = Validator::make($data, [
        'Value' => [new Gtin14],
    ]);

    expect($validator->fails())->toBeFalse();
})->with(array (
  0 => '00012345678905',
  1 => '10012345678902',
  2 => '0001-2345-6789-05',
));

it('Preform false check', function ($id) {
    $data = [
        'Value' => $id,
    ];

    $validator = Validator::make($data, [
        'Value' => [new Gtin14],
    ]);

    expect($validator->fails())->toBeTrue();
})->with(array (
  0 => '00012345678906',
  1 => '0001234567890',
  2 => 'abcdefghijklmn',
));
