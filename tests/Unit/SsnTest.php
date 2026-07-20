<?php

namespace Aindot\ExtraRules\Tests\Unit;

use Aindot\ExtraRules\Rules\Ssn;
use Illuminate\Support\Facades\Validator;

it('Preform correct check', function ($id) {
    $data = [
        'Value' => $id,
    ];

    $validator = Validator::make($data, [
        'Value' => [new Ssn],
    ]);

    expect($validator->fails())->toBeFalse();
})->with(array (
  0 => '123-45-6789',
  1 => '123456789',
  2 => '287-65-4321',
));

it('Preform false check', function ($id) {
    $data = [
        'Value' => $id,
    ];

    $validator = Validator::make($data, [
        'Value' => [new Ssn],
    ]);

    expect($validator->fails())->toBeTrue();
})->with(array (
  0 => '000-12-3456',
  1 => '666-12-3456',
  2 => '900-12-3456',
  3 => '123-00-6789',
  4 => '123-45-0000',
  5 => '12-345-6789',
  6 => 'abcdefghi',
));
