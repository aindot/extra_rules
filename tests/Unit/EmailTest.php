<?php

namespace Aindot\ExtraRules\Tests\Unit;

use Aindot\ExtraRules\Rules\Email;
use Illuminate\Support\Facades\Validator;

it('validates correct email addresses', function ($email) {
    $data = [
        'Value' => $email,
    ];

    $validator = Validator::make($data, [
        'Value' => ['required', new Email],
    ]);

    expect($validator->fails())->toBeFalse();
})->with([
    'john@example.com',
    'jane.doe@example.com',
    'john_doe@example.com',
    'john123@example.com',
    'test.email@example.co.uk',
    'user+tag@example.org',
]);

it('rejects invalid email addresses', function ($email) {
    $data = [
        'Value' => $email,
    ];

    $validator = Validator::make($data, [
        'Value' => ['required', new Email],
    ]);

    expect($validator->fails())->toBeTrue();
})->with([
    'invalid',
    'no-at-sign.example.com',
    '@example.com',
    'user@',
    'user..name@example.com',
    '.user@example.com',
    'user.@example.com',
    'user@.example.com',
    'user@example..com',
    'user@example.c',
    str_repeat('a', 65).'@example.com',
    'user@'.str_repeat('a', 254),
]);
