<?php

use Aindot\ExtraRules\Rules\Ipv6;
use Aindot\ExtraRules\RuleType;

it('validates valid Ipv6 addresses', function () {
    $rule = new Ipv6();
    
    $valid = [
        '2001:0db8:85a3:0000:0000:8a2e:0370:7334',
        '2001:0DB8:85A3:0000:0000:8A2E:0370:7334',
        'fe80::1',
        '::1',
        '::ffff:192.0.2.1',
        '2001:4860:4860::8888',
        '0:0:0:0:0:0:0:1',
    ];
    
    foreach ($valid as $ip) {
        $rule->validate('ip', $ip, function (): void {});
    }
});

it('rejects invalid Ipv6 addresses', function () {
    $rule = new Ipv6();
    
    $invalid = [
        '',
        'not-an-ip',
        '0001:02:03:04:05:06:07:08',
        '256:168.1.1',
        '256.1.1.1',
        '0:0:0:0:0:0:0:0:0',
    ];
    
    foreach ($invalid as $ip) {
        $rule->validate('ip', $ip, function (): void {});
    }
});

it('generates valid Ipv6 addresses via Generator', function () {
    $generator = new \Aindot\ExtraRules\Generator();
    
    $ip = $generator->make(RuleType::Ipv6);
    
    expect($ip)->toBeString();
    
    $rule = new Ipv6();
    $rule->validate('ip', $ip, function (): void {});
});
