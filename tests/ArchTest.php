<?php

declare(strict_types=1);

/**
 * tests/ArchTest.php
 *
 * Architecture rules: no debug calls and strict types everywhere.
 */
it('will not use debugging functions')
    ->expect(['dd', 'dump', 'ray', 'var_dump'])
    ->each->not->toBeUsed();

it('uses strict types in every source file')
    ->expect('Schmeits\FilamentUmami')
    ->toUseStrictTypes();

it('uses strict types in every test helper class')
    ->expect('Schmeits\FilamentUmami\Tests')
    ->toUseStrictTypes();
