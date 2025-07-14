<?php

namespace Aindot\ExtraRules\Tests\Unit;

use Aindot\ExtraRules\Rules\Isbn10;
use Illuminate\Support\Facades\Validator;

it('Preform correct check', function ($id) {

    $data = [
        'Isbn' => $id,
    ];

    $validator = Validator::make($data, [
        'Isbn' => [new Isbn10],
    ]);

    expect($validator->fails())->toBeFalse();

})->with([
    8881837188,
    '0-7475-3269-9',
    '3899930169',
    6028328227,
    '0306406152',
    '0-306-40615-2',
    '0134093410',
    '0 47 0173 424',
    '043942089X',
    '043942089x'
]);


it('Preform false check', function ($id) {

    $data = [
        'Isbn' => $id,
    ];

    $validator = Validator::make($data, [
        'Isbn' => [new Isbn10],
    ]);

    expect($validator->fails())->toBeTrue();

})->with([
    '0201540821',
    '0240032193',
    '0521357511',
    '0439420896',
    '043942088X',
]);
