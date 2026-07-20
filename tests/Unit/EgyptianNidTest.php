<?php

namespace Aindot\ExtraRules\Tests\Unit;

use Aindot\ExtraRules\Rules\EgyptianNid;
use Illuminate\Support\Facades\Validator;

it('Preform correct check', function ($id) {
    $data = ['Value' => $id];
    $validator = Validator::make($data, ['Value' => [new EgyptianNid]]);
    expect($validator->fails())->toBeFalse();
})->with([
    '29501011234567',
    '30001011234567',
    '2-950101-12-3456-7',
    '30312318812345',
    '28506291501234',
]);

it('Preform false check', function ($id) {
    $data = ['Value' => $id];
    $validator = Validator::make($data, ['Value' => [new EgyptianNid]]);
    expect($validator->fails())->toBeTrue();
})->with([
    '19501011234567',
    '29513011234567',
    '29501321234567',
    '29501019934567',
    '2950101123456',
    'abcdefghijklmn',
]);
