<?php

namespace Aindot\ExtraRules\Tests\Unit;

use Aindot\ExtraRules\Rules\UpcE;
use Illuminate\Support\Facades\Validator;

it('Preform correct check', function ($id) {
    $data = ['Value' => $id];
    $validator = Validator::make($data, ['Value' => [new UpcE]]);
    expect($validator->fails())->toBeFalse();
})->with([
    '04252614',
    '01234558',
    '00012344',
    '425261',
    '0-425261-4',
]);

it('Preform false check', function ($id) {
    $data = ['Value' => $id];
    $validator = Validator::make($data, ['Value' => [new UpcE]]);
    expect($validator->fails())->toBeTrue();
})->with([
    '04252615',
    '21234558',
    '12345',
    'abcdefgh',
]);
