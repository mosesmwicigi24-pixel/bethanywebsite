<?php

declare(strict_types=1);

namespace Bethany\Services\Tests\Security;

use Bethany\Services\Security\PasswordHasher;
use PHPUnit\Framework\TestCase;

final class PasswordHasherTest extends TestCase
{
    public function test_hash_is_verifiable_and_not_md5(): void
    {
        $hasher = new PasswordHasher();
        $hash = $hasher->hash('correct horse');

        self::assertNotSame(md5('correct horse'), $hash);
        self::assertFalse($hasher->isLegacyMd5($hash));
        self::assertTrue($hasher->verify('correct horse', $hash));
    }

    public function test_verify_rejects_wrong_password(): void
    {
        $hasher = new PasswordHasher();
        $hash = $hasher->hash('s3cret');

        self::assertFalse($hasher->verify('nope', $hash));
    }

    public function test_verify_accepts_legacy_md5(): void
    {
        $hasher = new PasswordHasher();
        $legacy = md5('oldpassword');

        self::assertTrue($hasher->isLegacyMd5($legacy));
        self::assertTrue($hasher->verify('oldpassword', $legacy));
        self::assertFalse($hasher->verify('wrong', $legacy));
    }

    public function test_verify_accepts_uppercase_legacy_md5(): void
    {
        $hasher = new PasswordHasher();
        $legacy = strtoupper(md5('MixedCase'));

        self::assertTrue($hasher->verify('MixedCase', $legacy));
    }

    public function test_needs_rehash_true_for_md5_false_for_fresh_hash(): void
    {
        $hasher = new PasswordHasher();

        self::assertTrue($hasher->needsRehash(md5('x')));
        self::assertFalse($hasher->needsRehash($hasher->hash('x')));
    }

    public function test_empty_stored_never_verifies(): void
    {
        $hasher = new PasswordHasher();

        self::assertFalse($hasher->verify('', ''));
        self::assertFalse($hasher->verify('anything', ''));
    }
}
