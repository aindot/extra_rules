<?php

namespace Aindot\ExtraRules\Tests\Unit;

use Aindot\ExtraRules\Rules\Isbn13;
use Illuminate\Support\Facades\Validator;

it('Preform correct check', function ($id) {

    $data = [
        'Isbn' => $id,
    ];

    $validator = Validator::make($data, [
        'Isbn' => [new Isbn13],
    ]);

    expect($validator->fails())->toBeFalse();

})->with([
    '978-3-16-148410-0',
    9780733426094,
    9781782808084,
    9791090636071,
    '979-10906-36-07-1',
    '9788 889 5271 91',
    '9780201621112',
    '9788889527191',
    '978-0-306-40615-7',
    9783899930160,
    '9780439420891',
]);
