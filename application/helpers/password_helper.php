<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Password helper — thin CI3 bridge to the portable Bethany\Services\Security\PasswordHasher.
 *
 * All password hashing across the app (admin, POS, customer, affiliate) goes through
 * these three functions so there is ONE place that knows the algorithm. The underlying
 * class transparently accepts legacy 32-char md5 hashes and reports needsRehash() for
 * them, so existing accounts keep logging in and are upgraded to bcrypt on next login.
 *
 * Load with: $this->load->helper('password');   (or autoload in application/config/autoload.php)
 */

use Bethany\Services\Security\PasswordHasher;

if ( ! function_exists('bethany_hasher'))
{
	function bethany_hasher()
	{
		static $hasher = null;
		if ($hasher === null)
		{
			$hasher = new PasswordHasher();
		}
		return $hasher;
	}
}

if ( ! function_exists('bethany_hash'))
{
	/** Hash a plaintext password with the current modern algorithm (bcrypt). */
	function bethany_hash($plain)
	{
		return bethany_hasher()->hash((string) $plain);
	}
}

if ( ! function_exists('bethany_verify'))
{
	/** True if $plain matches $stored (accepts modern hashes AND legacy md5). */
	function bethany_verify($plain, $stored)
	{
		return bethany_hasher()->verify((string) $plain, (string) $stored);
	}
}

if ( ! function_exists('bethany_needs_rehash'))
{
	/** True if $stored should be replaced with bethany_hash($plain) (e.g. it is md5). */
	function bethany_needs_rehash($stored)
	{
		return bethany_hasher()->needsRehash((string) $stored);
	}
}
