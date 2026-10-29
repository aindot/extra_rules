<?php

namespace Aindot\ExtraRules\Tests\Unit;

use Aindot\ExtraRules\Rules\PrivateKey;
use Illuminate\Support\Facades\Validator;

function privateKeyBlock(string $label, string $body = "MIIBVAIBADANBgkqhkiG9w0BAQEFAASCAT4wggE6AgEAAkEA\n"): string
{
    return "-----BEGIN {$label}-----\n{$body}-----END {$label}-----";
}

it('accepts valid private key blocks', function (string $label) {
    $validator = Validator::make(
        ['Value' => privateKeyBlock($label)],
        ['Value' => [new PrivateKey]]
    );

    expect($validator->fails())->toBeFalse();
})->with([
    'PRIVATE KEY',
    'RSA PRIVATE KEY',
    'EC PRIVATE KEY',
    'DSA PRIVATE KEY',
    'OPENSSH PRIVATE KEY',
    'ENCRYPTED PRIVATE KEY',
    'PGP PRIVATE KEY BLOCK',
]);

it('rejects invalid private key blocks', function (string $value) {
    $validator = Validator::make(
        ['Value' => $value],
        ['Value' => [new PrivateKey]]
    );

    expect($validator->fails())->toBeTrue();
})->with([
    'plain text',
    '-----BEGIN PRIVATE KEY-----',
    "-----BEGIN PRIVATE KEY-----\nMIIBVAIBADANBgkqhkiG9w0BAQEFAASCAT4wggE6AgEAAkEA\n-----END RSA PRIVATE KEY-----",
    "-----BEGIN PUBLIC KEY-----\nMIIBVAIBADANBgkqhkiG9w0BAQEFAASCAT4wggE6AgEAAkEA\n-----END PUBLIC KEY-----",
    "-----BEGIN PRIVATE KEY-----\nnot base64 @@@\n-----END PRIVATE KEY-----",
]);
