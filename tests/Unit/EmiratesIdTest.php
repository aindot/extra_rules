<?php

namespace Aindot\ExtraRules\Tests\Unit;

use Aindot\ExtraRules\Rules\EmiratesId;
use Illuminate\Support\Facades\Validator;

it('Preform correct check', function ($id) {
    $data = ['Value' => $id];
    $validator = Validator::make($data, ['Value' => [new EmiratesId]]);
    expect($validator->fails())->toBeFalse();
})->with([
    '784198012345678',
    '784-1980-1234567-8',
    '784199012345676',
    '784201012345670',
]);

it('Preform false check', function ($id) {
    $data = ['Value' => $id];
    $validator = Validator::make($data, ['Value' => [new EmiratesId]]);
    expect($validator->fails())->toBeTrue();
})->with([
    '784198012345679',
    '123198012345678',
    '78419801234567',
    'abcdefghijklmno',
]);
