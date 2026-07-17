<?php

declare(strict_types=1);

namespace Bethany\Services\Security;

/**
 * Framework-agnostic password hashing with transparent migration off legacy md5.
 *
 * This class has ZERO CodeIgniter dependency by design — it is part of the portable
 * `Bethany\Services\*` layer that survives a future CI4 / Laravel migration untouched.
 *
 * The Bethany storefront historically stored `md5($password)` (32 hex chars). We move
 * to bcrypt/argon2 via PHP's password_* API without forcing any password resets:
 *   - verify() accepts BOTH a modern hash and a legacy 32-char md5 hash;
 *   - needsRehash() is true for md5 (and for an out-of-date modern cost), so the caller
 *     can re-hash the plaintext at login time and persist the upgraded hash.
 *
 * Usage in a login flow:
 *   $hasher = new PasswordHasher();
 *   if (! $hasher->verify($plain, $stored)) { return 'invalid credentials'; }
 *   if ($hasher->needsRehash($stored)) { $db->update(['user_password' => $hasher->hash($plain)]); }
 */
final class PasswordHasher
{
    /** @var string|int PHP password_hash algorithm identifier (e.g. PASSWORD_DEFAULT). */
    private string|int $algo;

    /** @var array<string,mixed> Options passed to password_hash (e.g. ['cost' => 12]). */
    private array $options;

    /**
     * @param string|int|null      $algo    Defaults to PASSWORD_DEFAULT (bcrypt today, may
     *                                       become argon2 in a future PHP — needsRehash() handles it).
     * @param array<string,mixed>  $options password_hash options, e.g. ['cost' => 12].
     */
    public function __construct(string|int|null $algo = null, array $options = [])
    {
        $this->algo = $algo ?? PASSWORD_DEFAULT;
        $this->options = $options;
    }

    /** Produce a modern one-way hash of the plaintext password. */
    public function hash(string $plain): string
    {
        return password_hash($plain, $this->algo, $this->options);
    }

    /**
     * True when $plain matches $stored. Accepts legacy md5 hashes so existing
     * accounts keep working during the migration window. Constant-time on the md5 path.
     */
    public function verify(string $plain, string $stored): bool
    {
        if ($stored === '') {
            return false;
        }
        if ($this->isLegacyMd5($stored)) {
            return hash_equals(strtolower($stored), md5($plain));
        }
        return password_verify($plain, $stored);
    }

    /**
     * True when $stored should be replaced with hash($plain): either it is a legacy
     * md5 hash, or it is a modern hash produced with weaker/older parameters.
     */
    public function needsRehash(string $stored): bool
    {
        if ($this->isLegacyMd5($stored)) {
            return true;
        }
        return password_needs_rehash($stored, $this->algo, $this->options);
    }

    /** A stored value is a legacy md5 digest iff it is exactly 32 hexadecimal chars. */
    public function isLegacyMd5(string $stored): bool
    {
        return (bool) preg_match('/^[a-f0-9]{32}$/i', $stored);
    }
}
