<?php

declare(strict_types=1);

namespace Bethany\Services\Seo;

/**
 * Immutable SEO meta for a single page/product. Plain data — safe to persist,
 * serialise to JSON for an admin endpoint, or render into <head>.
 */
final class SeoMeta
{
    /** @param list<string> $keywords */
    public function __construct(
        public readonly string $title,
        public readonly string $description,
        public readonly array $keywords,
    ) {
    }

    /** @return array{title:string,description:string,keywords:list<string>} */
    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'description' => $this->description,
            'keywords' => $this->keywords,
        ];
    }
}
