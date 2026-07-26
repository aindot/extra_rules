<?php

use Aindot\ExtraRules\Rules\Ipv4;
use Aindot\ExtraRules\RuleType;

it('validates valid Ipv4 addresses', function () {
    $rule = new Ipv4();
    
    $valid = [
        '192.168.1.1',
        '10.0.0.1',
        '255.255.255.255',
        '0.0.0.0',
    ];
    
    foreach ($valid as $ip) {
        $rule->validate('ip', $ip, function (): void {});
    }
});

it('rejects invalid Ipv4 addresses', function () {
    $rule = new Ipv4();
    
    $invalid = [
        '',
        'not-an-ip',
        '999.168.1.1',
        '192.168.1.256',
        '192.168.1',
        '192.168.1.1.1',
        'abc.def.ghi.jkl',
    ];
    
    foreach ($invalid as $ip) {
        $rule->validate('ip', $ip, function (): void {});
    }
});

it('generates valid Ipv4 addresses via Generator', function () {
    $generator = new \Aindot\ExtraRules\Generator();
    
    $ip = $generator->make(RuleType::Ipv4);
    
    expect($ip)->toBeString();
    
    $rule = new Ipv4();
    $rule->validate('ip', $ip, function (): void {});
});
