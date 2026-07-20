<?php

namespace Aindot\ExtraRules\Tests\Unit;

use Aindot\ExtraRules\Rules\Ulid;
use Illuminate\Support\Facades\Validator;

it('Preform correct check', function ($id) {
    $data = [
        'Value' => $id,
    ];

    $validator = Validator::make($data, [
        'Value' => [new Ulid],
    ]);

    expect($validator->fails())->toBeFalse();
})->with(array (
  0 => '01ARZ3NDEKTSV4RRFFQ69G5FAV',
  1 => '01H5Z8K2Y3N4P5Q6R7S8T9V0WX',
  2 => '00000000000000000000000000',
));

it('Preform false check', function ($id) {
    $data = [
        'Value' => $id,
    ];

    $validator = Validator::make($data, [
        'Value' => [new Ulid],
    ]);

    expect($validator->fails())->toBeTrue();
})->with(array (
  0 => '01ARZ3NDEKTSV4RRFFQ69G5FA',
  1 => '01ARZ3NDEKTSV4RRFFQ69G5FAVU',
  2 => '01ARZ3NDEKTSV4RRFFQ69G5FAI',
  3 => 'not-a-valid-ulid-value!!',
));
