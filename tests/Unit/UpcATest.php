<?php

namespace Aindot\ExtraRules\Tests\Unit;

use Aindot\ExtraRules\Rules\UpcA;
use Illuminate\Support\Facades\Validator;

it('Preform correct check', function ($id) {
    $data = [
        'Value' => $id,
    ];

    $validator = Validator::make($data, [
        'Value' => [new UpcA],
    ]);

    expect($validator->fails())->toBeFalse();
})->with(array (
  0 => '036000291452',
  1 => '012345678905',
  2 => '03600-02914-52',
));

it('Preform false check', function ($id) {
    $data = [
        'Value' => $id,
    ];

    $validator = Validator::make($data, [
        'Value' => [new UpcA],
    ]);

    expect($validator->fails())->toBeTrue();
})->with(array (
  0 => '036000291453',
  1 => '01234567890',
  2 => 'abcdefghijkl',
));
