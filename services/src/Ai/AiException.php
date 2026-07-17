<?php

declare(strict_types=1);

namespace Bethany\Services\Ai;

/** Raised for any AI transport / provider error. Callers should treat AI as optional. */
final class AiException extends \RuntimeException
{
}
