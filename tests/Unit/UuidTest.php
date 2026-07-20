<?php

namespace Aindot\ExtraRules\Tests\Unit;

use Aindot\ExtraRules\Rules\Uuid;
use Illuminate\Support\Facades\Validator;

it('Preform correct check', function ($id) {
    $data = [
        'Value' => $id,
    ];

    $validator = Validator::make($data, [
        'Value' => [new Uuid],
    ]);

    expect($validator->fails())->toBeFalse();
})->with(array (
  0 => '550e8400-e29b-41d4-a716-446655440000',
  1 => '123e4567-e89b-12d3-a456-426614174000',
  2 => '018f3c2a-7b6d-7c3e-9f1a-2b3c4d5e6f70',
));

it('Preform false check', function ($id) {
    $data = [
        'Value' => $id,
    ];

    $validator = Validator::make($data, [
        'Value' => [new Uuid],
    ]);

    expect($validator->fails())->toBeTrue();
})->with(array (
  0 => '550e8400-e29b-01d4-a716-446655440000',
  1 => '550e8400e29b41d4a716446655440000',
  2 => 'not-a-uuid',
  3 => '550e8400-e29b-41d4-c716-446655440000',
));
