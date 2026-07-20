<?php

namespace Aindot\ExtraRules\Tests\Unit;

use Aindot\ExtraRules\Rules\Itf14;
use Illuminate\Support\Facades\Validator;

it('Preform correct check', function ($id) {
    $data = ['Value' => $id];
    $validator = Validator::make($data, ['Value' => [new Itf14]]);
    expect($validator->fails())->toBeFalse();
})->with([
    '00012345678905',
    '10012345678902',
    '0001-2345-6789-05',
]);

it('Preform false check', function ($id) {
    $data = ['Value' => $id];
    $validator = Validator::make($data, ['Value' => [new Itf14]]);
    expect($validator->fails())->toBeTrue();
})->with([
    '00012345678906',
    '0001234567890',
    'abcdefghijklmn',
]);
