<?php

namespace Aindot\ExtraRules\Tests\Unit;

use Aindot\ExtraRules\Rules\Iqama;
use Illuminate\Support\Facades\Validator;

it('Preform correct check', function ($id) {
    $data = ['Value' => $id];
    $validator = Validator::make($data, ['Value' => [new Iqama]]);
    expect($validator->fails())->toBeFalse();
})->with([
    '1023456781',
    '2123456788',
    '1000000008',
    '2000000006',
    '1-02345678-1',
]);

it('Preform false check', function ($id) {
    $data = ['Value' => $id];
    $validator = Validator::make($data, ['Value' => [new Iqama]]);
    expect($validator->fails())->toBeTrue();
})->with([
    '1023456782',
    '3123456781',
    '102345678',
    '0123456781',
    'abcdefghij',
]);
