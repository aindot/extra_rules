<?php

namespace Aindot\ExtraRules\Tests\Unit;

it('redacts text from the command argument', function () {
    $this->artisan('extra-rules:redact', [
        'input' => 'card 4111111111111111',
    ])
        ->expectsOutputToContain('card [REDACTED:credit_card]')
        ->assertSuccessful();
});

it('redacts text from a file', function () {
    $file = tempnam(sys_get_temp_dir(), 'extra-rules-');

    try {
        file_put_contents($file, 'iban GB82WEST12345698765432');

        $this->artisan('extra-rules:redact', ['--file' => $file])
            ->expectsOutputToContain('iban [REDACTED:iban]')
            ->assertSuccessful();
    } finally {
        @unlink($file);
    }
});

it('lists detected categories without outputting secrets', function () {
    $this->artisan('extra-rules:redact', [
        'input' => 'card 4111111111111111',
        '--list' => true,
    ])
        ->expectsOutputToContain('credit_card: 1')
        ->doesntExpectOutputToContain('4111111111111111')
        ->assertSuccessful();
});

it('limits detection with --types', function () {
    $this->artisan('extra-rules:redact', [
        'input' => '4111111111111111 GB82WEST12345698765432',
        '--types' => 'iban',
    ])
        ->expectsOutputToContain('4111111111111111 [REDACTED:iban]')
        ->assertSuccessful();
});

it('fails when no input source is provided', function () {
    $this->artisan('extra-rules:redact')->assertFailed();
});

it('fails when more than one input source is provided', function () {
    $this->artisan('extra-rules:redact', [
        'input' => 'text',
        '--stdin' => true,
    ])->assertFailed();
});

it('fails when the file cannot be read', function () {
    $this->artisan('extra-rules:redact', [
        '--file' => '/path/to/missing/file',
    ])->assertFailed();
});

it('fails on unknown redaction types', function () {
    $this->artisan('extra-rules:redact', [
        'input' => 'anything',
        '--types' => 'uuid',
    ])->assertFailed();
});
