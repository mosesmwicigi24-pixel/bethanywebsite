<?php

declare(strict_types=1);

namespace Bethany\Services\Tests\Seo;

use Bethany\Services\Seo\StructuredData;
use PHPUnit\Framework\TestCase;

final class StructuredDataTest extends TestCase
{
    public function test_product_offer_and_absolute_urls(): void
    {
        $sd = new StructuredData('Bethany House', 'https://bethanyhouse.co.ke', 'KES');

        $data = $sd->product([
            'name' => 'Clergy Robe',
            'description' => '<p>Fine  robe</p>',
            'image' => 'uploads/robe.jpg',
            'price' => 4500,
            'availability' => 'in_stock',
            'url' => 'product/clergy-robe',
            'brand' => 'Bethany',
        ]);

        self::assertSame('Product', $data['@type']);
        self::assertSame('Fine robe', $data['description']);
        self::assertSame('https://bethanyhouse.co.ke/uploads/robe.jpg', $data['image']);
        self::assertSame('KES', $data['offers']['priceCurrency']);
        self::assertSame('4500.00', $data['offers']['price']);
        self::assertSame('https://schema.org/InStock', $data['offers']['availability']);
        self::assertSame('https://bethanyhouse.co.ke/product/clergy-robe', $data['offers']['url']);
    }

    public function test_out_of_stock_and_partial_data(): void
    {
        $sd = new StructuredData();
        $data = $sd->product(['name' => 'X', 'price' => 100, 'availability' => 'out']);

        self::assertSame('https://schema.org/OutOfStock', $data['offers']['availability']);
        self::assertArrayNotHasKey('image', $data);
        self::assertArrayNotHasKey('brand', $data);
    }

    public function test_no_offer_without_price(): void
    {
        $sd = new StructuredData();
        $data = $sd->product(['name' => 'X']);

        self::assertArrayNotHasKey('offers', $data);
    }

    public function test_breadcrumbs_positions(): void
    {
        $sd = new StructuredData();
        $data = $sd->breadcrumbs(['Home' => '/', 'Communion' => '/category/communion']);

        self::assertSame(1, $data['itemListElement'][0]['position']);
        self::assertSame('Communion', $data['itemListElement'][1]['name']);
        self::assertSame(2, $data['itemListElement'][1]['position']);
    }

    public function test_script_wraps_json_ld(): void
    {
        $sd = new StructuredData();
        $html = $sd->script($sd->organization('assets/logo.png', ['https://facebook.com/bethany']));

        self::assertStringStartsWith('<script type="application/ld+json">', $html);
        self::assertStringContainsString('"@type":"Organization"', $html);
        self::assertStringContainsString('https://bethanyhouse.co.ke/assets/logo.png', $html);
    }
}
