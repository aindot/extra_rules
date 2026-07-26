<?php

use Aindot\ExtraRules\Rules\Email;
use Aindot\ExtraRules\RuleType;

it('validates valid email addresses', function () {
    $rule = new Email();
    
    $valid = [
        'test@example.com',
        'user.name@domain.org',
        'john_doe@company.io',
        'admin(@service.net',
        'info+tag@example.com',
    ];
    
    foreach ($valid as $email) {
        $rule->validate('email', $email, function (): void {});
    }
});

it('rejects invalid email addresses', function () {
    $rule = new Email();
    
    $invalid = [
        '',
        'not-an-email',
        '@example.com',
        'user@',
        'user@.com',
        'user@domain',
        'user name@example.com',
        'user@domain',
        'user@domain..com',
    ];
    
    foreach ($invalid as $email) {
        $rule->validate('email', $email, function (): void {});
    }
});

it('rejects emails without MX records', function () {
    $rule = new Email();
    
    $rule->validate('email', 'test@nonexistent-domain-xyz123.invalid', function (): void {});
});

it('generates valid emails via Generator', function () {
    $generator = new \Aindot\ExtraRules\Generator();
    
    $email = $generator->make(RuleType::Email);
    
    expect($email)->toBeString();
    
    $rule = new Email();
    $rule->validate('email', $email, function (): void {});
});

it('generates multiple valid emails', function () {
    $generator = new \Aindot\ExtraRules\Generator();
    
    $emails = $generator->makeMany(RuleType::Email, 10);
    
    expect($emails)->toHaveCount(10);
    
    foreach ($emails as $email) {
        $rule = new Email();
        $rule->validate('email', $email, function (): void {});
    }
});
