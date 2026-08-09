<?php

declare(strict_types=1);

namespace Bethany\Services\Ai;

/**
 * Anthropic Messages API implementation of {@see AiClient}.
 *
 * Provider details (endpoint, headers, response shape) live ONLY here; swapping
 * providers means writing one more AiClient, not touching any business service.
 */
final class AnthropicClient implements AiClient
{
    private const ENDPOINT = 'https://api.anthropic.com/v1/messages';
    private const API_VERSION = '2023-06-01';

    /** Cost-efficient default; good enough for SEO copy and bulk jobs. */
    public const DEFAULT_MODEL = 'claude-haiku-4-5-20251001';

    public function __construct(
        private HttpTransport $transport,
        private string $apiKey,
        private string $defaultModel = self::DEFAULT_MODEL,
    ) {
    }

    public function complete(string $prompt, array $options = []): string
    {
        if ($this->apiKey === '') {
            throw new AiException('Anthropic API key is not configured.');
        }

        $body = [
            'model' => $options['model'] ?? $this->defaultModel,
            'max_tokens' => $options['max_tokens'] ?? 1024,
            'messages' => [
                ['role' => 'user', 'content' => $prompt],
            ],
        ];
        if (isset($options['system'])) {
            $body['system'] = $options['system'];
        }
        if (isset($options['temperature'])) {
            $body['temperature'] = $options['temperature'];
        }

        $response = $this->transport->postJson(self::ENDPOINT, [
            'x-api-key' => $this->apiKey,
            'anthropic-version' => self::API_VERSION,
            'content-type' => 'application/json',
        ], $body);

        return $this->extractText($response);
    }

    /** Pull concatenated text out of the Messages API `content` blocks. */
    private function extractText(array $response): string
    {
        if (isset($response['error'])) {
            $message = is_array($response['error']) ? ($response['error']['message'] ?? 'unknown error') : (string) $response['error'];
            throw new AiException('Anthropic API error: ' . $message);
        }

        $blocks = $response['content'] ?? null;
        if (! is_array($blocks)) {
            throw new AiException('Anthropic response missing content blocks.');
        }

        $text = '';
        foreach ($blocks as $block) {
            if (is_array($block) && ($block['type'] ?? null) === 'text') {
                $text .= (string) ($block['text'] ?? '');
            }
        }

        $text = trim($text);
        if ($text === '') {
            throw new AiException('Anthropic response contained no text.');
        }

        return $text;
    }
}
