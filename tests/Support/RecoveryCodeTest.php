<?php

use MityDigital\StatamicTwoFactor\Support\RecoveryCode;

test('it generates a code made up of 10 characters, a dash, and another 10 characters', function () {
    $code = RecoveryCode::generate();

    expect(preg_match('/[a-zA-Z0-9]{10}-[a-zA-Z0-9]{10}/', $code))
        ->toBeTruthy();
});

it('generates a different code on each call', function () {
    $code1 = RecoveryCode::generate();

    expect($code1)
        ->not()->toBeNull();

    $code2 = RecoveryCode::generate();

    expect($code2)
        ->not()->toBeNull()
        ->not()->toBe($code1);

    $code3 = RecoveryCode::generate();

    expect($code3)
        ->not()->toBeNull()
        ->not()->toBe($code2)
        ->not()->toBe($code1);
});
