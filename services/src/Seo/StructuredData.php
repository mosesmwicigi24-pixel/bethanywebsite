<?php

declare(strict_types=1);

namespace Bethany\Services\Seo;

/**
 * Builds schema.org JSON-LD. Pure, dependency-free, no network — this is the
 * highest-ROI SEO win in the app (rich snippets in Google / Google Shopping) and
 * costs nothing to run. Render the output inside a
 * <script type="application/ld+json"> tag in the page <head>.
 */
final class StructuredData
{
    public function __construct(
        private string $storeName = 'Bethany House',
        private string $baseUrl = 'https://bethanyhouse.co.ke',
        private string $currency = 'KES',
    ) {
    }

    /**
     * schema.org/Product with an Offer. Only emits fields that are present, so it is
     * safe to call with partial data.
     *
     * @param array{name?:string,description?:string,image?:string,sku?:string,brand?:string,
     *              price?:string|float|int,availability?:string,url?:string,rating?:float,review_count?:int} $product
     *
     * @return array<string,mixed>
     */
    public function product(array $product): array
    {
        $data = [
            '@context' => 'https://schema.org',
            '@type' => 'Product',
            'name' => (string) ($product['name'] ?? ''),
        ];

        if (! empty($product['description'])) {
            $data['description'] = $this->plain((string) $product['description']);
        }
        if (! empty($product['image'])) {
            $data['image'] = $this->absolute((string) $product['image']);
        }
        if (! empty($product['sku'])) {
            $data['sku'] = (string) $product['sku'];
        }
        if (! empty($product['brand'])) {
            $data['brand'] = ['@type' => 'Brand', 'name' => (string) $product['brand']];
        }

        if (isset($product['price']) && $product['price'] !== '') {
            $available = ($product['availability'] ?? 'in_stock') === 'in_stock'
                ? 'https://schema.org/InStock'
                : 'https://schema.org/OutOfStock';
            $data['offers'] = [
                '@type' => 'Offer',
                'priceCurrency' => $this->currency,
                'price' => number_format((float) $product['price'], 2, '.', ''),
                'availability' => $available,
                'url' => $this->absolute((string) ($product['url'] ?? '')),
                'seller' => ['@type' => 'Organization', 'name' => $this->storeName],
            ];
        }

        if (! empty($product['rating']) && ! empty($product['review_count'])) {
            $data['aggregateRating'] = [
                '@type' => 'AggregateRating',
                'ratingValue' => (string) $product['rating'],
                'reviewCount' => (int) $product['review_count'],
            ];
        }

        return $data;
    }

    /**
     * schema.org/BreadcrumbList from an ordered [label => url] map.
     *
     * @param array<string,string> $trail
     *
     * @return array<string,mixed>
     */
    public function breadcrumbs(array $trail): array
    {
        $items = [];
        $position = 1;
        foreach ($trail as $label => $url) {
            $items[] = [
                '@type' => 'ListItem',
                'position' => $position++,
                'name' => $label,
                'item' => $this->absolute($url),
            ];
        }

        return [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $items,
        ];
    }

    /** @return array<string,mixed> */
    public function organization(?string $logoUrl = null, array $sameAs = []): array
    {
        $data = [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => $this->storeName,
            'url' => $this->baseUrl,
        ];
        if ($logoUrl !== null && $logoUrl !== '') {
            $data['logo'] = $this->absolute($logoUrl);
        }
        if ($sameAs !== []) {
            $data['sameAs'] = array_values($sameAs);
        }

        return $data;
    }

    /**
     * Render a JSON-LD array as a ready-to-embed <script> tag.
     *
     * @param array<string,mixed> $data
     */
    public function script(array $data): string
    {
        $json = json_encode(
            $data,
            JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG
        );

        return '<script type="application/ld+json">' . $json . '</script>';
    }

    private function absolute(string $url): string
    {
        if ($url === '' || preg_match('#^https?://#i', $url)) {
            return $url;
        }
        return rtrim($this->baseUrl, '/') . '/' . ltrim($url, '/');
    }

    private function plain(string $html): string
    {
        return trim(preg_replace('/\s+/', ' ', strip_tags($html)) ?? '');
    }
}
