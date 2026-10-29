<?php

namespace Aindot\ExtraRules\Tests\Unit;

use Aindot\ExtraRules\Rules\ApiKey;
use Illuminate\Support\Facades\Validator;

it('accepts known API key prefixes', function (string $key) {
    $validator = Validator::make(
        ['Value' => $key],
        ['Value' => [new ApiKey]]
    );

    expect($validator->fails())->toBeFalse();
})->with([
    'AKIAIOSFODNN7EXAMPLE',
    'sk_live_51234567890abcdefghijklmn',
    'rk_test_51234567890abcdefghijklmn',
    'ghp_1234567890abcdefghijklmnopqrstuvwxyz',
    'github_pat_11ABCDEFG0123456789_abcdefghijklmnopqrstuvwxyz',
    'glpat-1234567890abcdefghij',
    'xoxb-123456789012-1234567890abcdefghijkl',
    'AIzaSyD-1234567890abcdefghijklmnopqrstu',
    'sk-proj-1234567890abcdefghijklmnop',
    'sk-ant-api03-1234567890abcdefghijklmnop',
    'SG.1234567890abcdefghijkl.abcd1234567890abcdefghijklmnopqrstuvwxyz123',
    'key-1234567890abcdefghijklmnopqrstuv',
    'SK0123456789abcdef0123456789abcdef',
    'shpat_0123456789abcdef0123456789abcdef',
    'sq0atp-1234567890abcdefghijklmn',
    'npm_1234567890abcdefghijklmnopqrstuvwxyz',
    'pypi-1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRST',
    'dop_v1_0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef',
    'hf_1234567890abcdefghijklmnopqrstuvwxyz',
    'nfp_1234567890abcdefghijklmnopqrstuvwxyzABCD',
    'NRAK-0123456789ABCDEFGHIJKLMNOPQ',
    'pat1234567890abcd.0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef',
    '123456789:1234567890abcdefghijklmnopqrstuvwxy',
    'EAACEdEose0cBA1234567890abcdef',
]);

it('rejects invalid or partial API keys', function (string $value) {
    $validator = Validator::make(
        ['Value' => $value],
        ['Value' => [new ApiKey]]
    );

    expect($validator->fails())->toBeTrue();
})->with([
    'plain text',
    'sk_live_',
    'AKIA123',
    'ghp_short',
    '1234567890abcdefghijklmnopqrstuvwxyz',
]);
