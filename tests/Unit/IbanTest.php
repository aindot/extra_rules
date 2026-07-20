<?php

namespace Aindot\ExtraRules\Tests\Unit;

use Aindot\ExtraRules\Rules\Iban;
use Illuminate\Support\Facades\Validator;

it('Preform correct check', function ($id) {
    $data = [
        'Value' => $id,
    ];

    $validator = Validator::make($data, [
        'Value' => [new Iban],
    ]);

    expect($validator->fails())->toBeFalse();
})->with(array (
  0 => 'GB82WEST12345698765432',
  1 => 'DE89 3704 0044 0532 0130 00',
  2 => 'FR1420041010050500013M02606',
  3 => 'NL91ABNA0417164300',
));

it('Preform false check', function ($id) {
    $data = [
        'Value' => $id,
    ];

    $validator = Validator::make($data, [
        'Value' => [new Iban],
    ]);

    expect($validator->fails())->toBeTrue();
})->with(array (
  0 => 'GB82WEST12345698765433',
  1 => 'DE89370400440532013001',
  2 => 'XX00AAAA00000000000000',
  3 => 'not-an-iban',
));
