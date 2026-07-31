<?php

declare(strict_types=1);

namespace Bethany\Services\Ai;

/**
 * A minimal single-turn text-completion port. Higher-level services (SEO, exec
 * insights) depend on THIS interface, never on a concrete provider — so they are
 * trivially testable with a fake and provider-swappable.
 */
interface AiClient
{
    /**
     * Send one instruction and return the model's plain-text reply.
     *
     * @param array{system?:string,max_tokens?:int,temperature?:float,model?:string} $options
     *
     * @throws AiException on transport / provider failure.
     */
    public function complete(string $prompt, array $options = []): string;
}
