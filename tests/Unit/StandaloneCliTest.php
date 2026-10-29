<?php

namespace Aindot\ExtraRules\Tests\Unit;

/**
 * @param  list<string>  $args
 * @return array{code: int, stdout: string, stderr: string}
 */
function runExtraRulesCli(array $args, string $stdin = ''): array
{
    $root = dirname(__DIR__, 2);
    $process = proc_open(
        [PHP_BINARY, $root.'/bin/extra-rules', ...$args],
        [0 => ['pipe', 'r'], 1 => ['pipe', 'w'], 2 => ['pipe', 'w']],
        $pipes,
        $root
    );

    if (! is_resource($process)) {
        throw new RuntimeException('Unable to start the CLI process.');
    }

    fwrite($pipes[0], $stdin);
    fclose($pipes[0]);

    $stdout = stream_get_contents($pipes[1]);
    $stderr = stream_get_contents($pipes[2]);
    fclose($pipes[1]);
    fclose($pipes[2]);

    return [
        'code' => proc_close($process),
        'stdout' => $stdout === false ? '' : $stdout,
        'stderr' => $stderr === false ? '' : $stderr,
    ];
}

it('redacts text from the standalone cli argument', function () {
    $result = runExtraRulesCli(['redact', 'card 4111111111111111']);

    expect($result['code'])->toBe(0)
        ->and($result['stdout'])->toContain('card [REDACTED:credit_card]');
});

it('redacts text from a file in the standalone cli', function () {
    $file = tempnam(sys_get_temp_dir(), 'extra-rules-');

    try {
        file_put_contents($file, 'iban GB82WEST12345698765432');

        $result = runExtraRulesCli(['redact', '--file='.$file]);

        expect($result['code'])->toBe(0)
            ->and($result['stdout'])->toContain('iban [REDACTED:iban]');
    } finally {
        @unlink($file);
    }
});

it('redacts text from stdin in the standalone cli', function () {
    $result = runExtraRulesCli(['redact', '--stdin'], "Bearer abc123\n");

    expect($result['code'])->toBe(0)
        ->and($result['stdout'])->toContain('[REDACTED:bearer_token]');
});

it('accepts leading-dash input after the separator', function () {
    $key = "-----BEGIN PRIVATE KEY-----\nMIIBVAIBADANBgkqhkiG9w0BAQEFAASCAT4wggE6AgEAAkEA\n-----END PRIVATE KEY-----";

    $result = runExtraRulesCli(['redact', '--', $key]);

    expect($result['code'])->toBe(0)
        ->and($result['stdout'])->toContain('[REDACTED:private_key]');
});

it('lists detected categories without exposing values', function () {
    $result = runExtraRulesCli(['redact', '--list', 'card 4111111111111111']);

    expect($result['code'])->toBe(0)
        ->and($result['stdout'])->toContain('credit_card: 1')
        ->and($result['stdout'])->not->toContain('4111111111111111');
});

it('leaves clean text unchanged and exits successfully', function () {
    $result = runExtraRulesCli(['redact', 'nothing sensitive']);

    expect($result['code'])->toBe(0)
        ->and($result['stdout'])->toBe("nothing sensitive\n");
});

it('fails without an input source', function () {
    $result = runExtraRulesCli(['redact']);

    expect($result['code'])->toBe(1)
        ->and($result['stderr'])->toContain('exactly one input source');
});

it('fails on types that cannot be redacted', function () {
    $result = runExtraRulesCli(['redact', '--types=uuid', 'text']);

    expect($result['code'])->toBe(1)
        ->and($result['stderr'])->toContain('cannot be redacted');
});
