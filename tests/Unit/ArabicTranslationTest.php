<?php

namespace Aindot\ExtraRules\Tests\Unit;

use Aindot\ExtraRules\Rules\Imei;
use Aindot\ExtraRules\Rules\Iqama;
use Aindot\ExtraRules\Rules\KuwaitCivilId;
use Illuminate\Support\Facades\Validator;

it('returns English validation messages by default', function () {
    app()->setLocale('en');

    $validator = Validator::make(
        ['device' => '123'],
        ['device' => [new Imei]]
    );

    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->first('device'))->toBe('The device must be a valid IMEI.');
});

it('returns Arabic validation messages when locale is ar', function () {
    app()->setLocale('ar');

    $validator = Validator::make(
        ['device' => '123'],
        ['device' => [new Imei]]
    );

    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->first('device'))->toBe('يجب أن يكون device رقم IMEI صالحًا.');
});

it('translates Kuwait civil ID errors to Arabic', function () {
    app()->setLocale('ar');

    $validator = Validator::make(
        ['civil_id' => '123'],
        ['civil_id' => [new KuwaitCivilId]]
    );

    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->first('civil_id'))->toBe('يجب أن يكون civil id رقم مدني كويتي صالحًا.');
});

it('translates Iqama errors to Arabic', function () {
    app()->setLocale('ar');

    $validator = Validator::make(
        ['id' => '000'],
        ['id' => [new Iqama]]
    );

    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->first('id'))->toBe('يجب أن يكون id رقم إقامة أو هوية وطنية سعودية صالحًا.');
});

it('shows Arabic command output when locale is ar', function () {
    app()->setLocale('ar');

    $this->artisan('extra-rules:magic', [
        'input' => '!!!',
        '--rules' => 'imei,uuid',
    ])
        ->expectsOutputToContain('لم يتم العثور على فئات تحقق مطابقة.')
        ->assertSuccessful();
});
