<?php

declare(strict_types=1);

namespace Bethany\Services\Seo;

use Bethany\Services\Ai\AiClient;
use Bethany\Services\Ai\AiException;

/**
 * Generates SEO meta (title / description / keywords) for a product using an AiClient.
 *
 * Framework-agnostic: give it a plain associative array of product fields, get back a
 * {@see SeoMeta}. The prompt forces strict JSON; we parse defensively and clamp lengths
 * to what search engines actually display, so the output is always render-safe even if
 * the model rambles.
 */
final class SeoMetaService
{
    private const MAX_TITLE = 60;
    private const MAX_DESCRIPTION = 155;
    private const MAX_KEYWORDS = 10;

    public function __construct(
        private AiClient $ai,
        private string $storeName = 'Bethany House',
    ) {
    }

    /**
     * @param array{name?:string,description?:string,category?:string,brand?:string,price?:string|float|int} $product
     */
    public function generate(array $product): SeoMeta
    {
        $name = trim((string) ($product['name'] ?? ''));
        if ($name === '') {
            throw new AiException('Cannot generate SEO meta: product name is required.');
        }

        $facts = $this->buildFacts($product);
        $prompt = $this->buildPrompt($facts);

        $raw = $this->ai->complete($prompt, [
            'system' => 'You are an e-commerce SEO copywriter for a Kenyan Christian retail store '
                . '(Holy Communion elements, clergy apparel, Christian gifts). Reply with JSON only.',
            'max_tokens' => 400,
            'temperature' => 0.4,
        ]);

        return $this->parse($raw, $name);
    }

    /** @param array{name?:string,description?:string,category?:string,brand?:string,price?:string|float|int} $product */
    private function buildFacts(array $product): string
    {
        $lines = [];
        $lines[] = 'Store: ' . $this->storeName;
        foreach (['name' => 'Product', 'brand' => 'Brand', 'category' => 'Category', 'price' => 'Price'] as $key => $label) {
            if (isset($product[$key]) && (string) $product[$key] !== '') {
                $lines[] = $label . ': ' . trim((string) $product[$key]);
            }
        }
        if (isset($product['description']) && (string) $product['description'] !== '') {
            // Strip HTML and cap length so a huge description can't blow the token budget.
            $desc = trim(preg_replace('/\s+/', ' ', strip_tags((string) $product['description'])) ?? '');
            $lines[] = 'Description: ' . mb_substr($desc, 0, 600);
        }

        return implode("\n", $lines);
    }

    private function buildPrompt(string $facts): string
    {
        return <<<PROMPT
        Write SEO metadata for this product.

        {$facts}

        Requirements:
        - "title": compelling, <= 60 characters, include the product name; may append " | {$this->storeName}" only if it fits.
        - "description": persuasive meta description, 140-155 characters, active voice, no quotes.
        - "keywords": 5-10 lowercase search terms a Kenyan shopper would use.

        Respond with ONLY a JSON object, no markdown fences, exactly:
        {"title": "...", "description": "...", "keywords": ["...", "..."]}
        PROMPT;
    }

    private function parse(string $raw, string $fallbackName): SeoMeta
    {
        $json = $this->extractJson($raw);
        $data = json_decode($json, true);

        if (! is_array($data)) {
            throw new AiException('SEO generation returned unparseable JSON.');
        }

        $title = $this->clean((string) ($data['title'] ?? $fallbackName), self::MAX_TITLE);
        $description = $this->clean((string) ($data['description'] ?? ''), self::MAX_DESCRIPTION);

        $keywords = [];
        if (isset($data['keywords']) && is_array($data['keywords'])) {
            foreach ($data['keywords'] as $kw) {
                $kw = strtolower(trim((string) $kw));
                if ($kw !== '') {
                    $keywords[] = $kw;
                }
            }
        }
        $keywords = array_slice(array_values(array_unique($keywords)), 0, self::MAX_KEYWORDS);

        if ($title === '') {
            $title = $this->clean($fallbackName, self::MAX_TITLE);
        }

        return new SeoMeta($title, $description, $keywords);
    }

    /** Tolerate models that wrap JSON in prose or ```json fences. */
    private function extractJson(string $raw): string
    {
        $raw = trim($raw);
        $raw = preg_replace('/^```(?:json)?|```$/m', '', $raw) ?? $raw;
        $start = strpos($raw, '{');
        $end = strrpos($raw, '}');
        if ($start !== false && $end !== false && $end > $start) {
            return substr($raw, $start, $end - $start + 1);
        }
        return $raw;
    }

    private function clean(string $value, int $max): string
    {
        $value = trim(preg_replace('/\s+/', ' ', strip_tags($value)) ?? '');
        if (mb_strlen($value) > $max) {
            $value = rtrim(mb_substr($value, 0, $max));
        }
        return $value;
    }
}
