<?php

use Aindot\ExtraRules\Rules\Url;
use Aindot\ExtraRules\RuleType;

it('validates valid URLs', function () {
    $rule = new Url();
    
    $valid = [
        'https://example.com',
        'http://test.org/path',
        'ftp://files.domain.net/file.txt',
        'https://company.io/api/v1/users',
        'http://192.168.1.1:8080',
        'https://example.com?query=hello&foo=bar',
    ];
    
    foreach ($valid as $url) {
        $rule->validate('url', $url, function (): void {});
    }
});

it('rejects invalid URLs', function () {
    $rule = new Url();
    
    $invalid = [
        '',
        'not-a-url',
        '://missing-scheme.com',
        'https://',
        'com without://slashes',
        '__strange_chars__here',
        @null,
        @false,
    ];
    
    foreach ($invalid as $url) {
        $rule->validate('url', $url, function (): void {});
    }
});

it('allows URLs without explicit protocol', function () {
    $rule = new Url();
    
    $rules = [
        'example.com',
        'test.org/path',
    ];
    
    foreach ($rules as $url) {
        $rule->validate('url', $url, function (): void {});
    }
});

it('generates valid URLs via Generator', function () {
    $generator = new \Aindot\ExtraRules\Generator();
    
    $url = $generator->make(RuleType::Url);
    
    expect($url)->toBeString();
    
    $rule = new Url();
    $rule->validate('url', $url, function (): void {});
});
