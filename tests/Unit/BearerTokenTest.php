<?php

namespace Aindot\ExtraRules\Tests\Unit;

use Aindot\ExtraRules\Rules\BearerToken;
use Illuminate\Support\Facades\Validator;

it('accepts valid bearer tokens', function (string $token) {
    $validator = Validator::make(
        ['Value' => $token],
        ['Value' => [new BearerToken]]
    );

    expect($validator->fails())->toBeFalse();
})->with([
    'Bearer abc123',
    'bearer abc123',
    'Bearer eyJhbGciOiJIUzI1NiJ9.eyJzdWIiOiIxMjM0NTY3ODkwIn0.signature',
    'Bearer abc-123_456.789~+/=',
]);

it('rejects invalid bearer tokens', function (string $value) {
    $validator = Validator::make(
        ['Value' => $value],
        ['Value' => [new BearerToken]]
    );

    expect($validator->fails())->toBeTrue();
})->with([
    'Bearer',
    'Bearer ',
    'Basic abc123',
    'abc123',
    'Bearer abc 123',
]);
