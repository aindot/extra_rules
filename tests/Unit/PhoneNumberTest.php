<?php

use Aindot\ExtraRules\Rules\PhoneNumber;
use Aindot\ExtraRules\RuleType;

it('validates E.164 format phone numbers', function () {
    $rule = new PhoneNumber();
    
    $valid = [
        '+1234567890',
        '+14155552671',
        '+442071234567',
        '+4915123456789',
        '+971501234567',
        '+966501234567',
        '+9731234567',
    ];
    
    foreach ($valid as $phone) {
        $rule->validate('phone', $phone, function (): void {});
    }
});

it('validates phone numbers with country code constraint', function () {
    $rule = new PhoneNumber();
    
    $valid = [
        '14155552671',
        '4155552671',
    ];
    
    foreach ($valid as $phone) {
        $rule->validate('phone', $phone, function (): void {}, 'US');
    }
});

it('rejects invalid phone numbers', function () {
    $rule = new PhoneNumber();
    
    $invalid = [
        '',
        'not-a-phone',
        '+12345', // Too short
        '+12345678901234567890', // Too long
        '+1ABC7890', // Not all digits
        '+1 234 567 890', // Contains spaces (after cleaning)
    ];
    
    foreach ($invalid as $phone) {
        $rule->validate('phone', $phone, function (): void {});
    }
});

it('remases common phone separators', function () {
    $rule = new PhoneNumber();
    
    $valid = [
        '+1 (415) 555-2671',
        '1 415-555-2671',
        '+44 20 7123 4567',
        '(1415) 555-2671',
    ];
    
    foreach ($valid as $phone) {
        $rule->validate('phone', $phone, function (): void {});
    }
});

it('generates valid phone numbers via Generator', function () {
    $generator = new \Aindot\ExtraRules\Generator();
    
    $phone = $generator->make(RuleType::PhoneNumber);
    
    expect($phone)->toBeString();
    expect($phone)->toContain('+');
    expect($phone)->toMatch('/^\+\d{10,15}$/');
    
    $rule = new PhoneNumber();
    $rule->validate('phone', $phone, function (): void {});
});
