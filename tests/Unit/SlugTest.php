<?php

namespace Aindot\ExtraRules\Tests\Unit;

use Aindot\ExtraRules\Rules\Slug;
use Illuminate\Support\Facades\Validator;

it('Preform correct check', function ($id) {
    $data = [
        'Value' => $id,
    ];

    $validator = Validator::make($data, [
        'Value' => [new Slug],
    ]);

    expect($validator->fails())->toBeFalse();
})->with(array (
  0 => 'hello-world',
  1 => 'hello',
  2 => 'a1b2-c3',
  3 => 'my-long-blog-post-title',
));

it('Preform false check', function ($id) {
    $data = [
        'Value' => $id,
    ];

    $validator = Validator::make($data, [
        'Value' => [new Slug],
    ]);

    expect($validator->fails())->toBeTrue();
})->with(array (
  0 => 'Hello-World',
  1 => '-hello',
  2 => 'hello-',
  3 => 'hello--world',
  4 => 'hello world',
));
