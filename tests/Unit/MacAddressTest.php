<?php

namespace Aindot\ExtraRules\Tests\Unit;

use Aindot\ExtraRules\Rules\MacAddress;
use Illuminate\Support\Facades\Validator;

it('Preform correct check', function ($id) {
    $data = [
        'Value' => $id,
    ];

    $validator = Validator::make($data, [
        'Value' => [new MacAddress],
    ]);

    expect($validator->fails())->toBeFalse();
})->with(array (
  0 => '00:1A:2B:3C:4D:5E',
  1 => '00-1A-2B-3C-4D-5E',
  2 => '001A2B3C4D5E',
  3 => 'aa:bb:cc:dd:ee:ff',
));

it('Preform false check', function ($id) {
    $data = [
        'Value' => $id,
    ];

    $validator = Validator::make($data, [
        'Value' => [new MacAddress],
    ]);

    expect($validator->fails())->toBeTrue();
})->with(array (
  0 => '00:1A:2B:3C:4D',
  1 => '00:1A:2B:3C:4D:5E:6F',
  2 => '001A2B3C4D5G',
  3 => 'not-a-mac',
));
