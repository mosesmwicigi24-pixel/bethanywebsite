<?php

declare(strict_types=1);

namespace Bethany\Services\Tests\Seo;

use Bethany\Services\Ai\AiException;
use Bethany\Services\Seo\SeoMetaService;
use Bethany\Services\Tests\Fake\FakeAiClient;
use PHPUnit\Framework\TestCase;

final class SeoMetaServiceTest extends TestCase
{
    public function test_parses_clean_json(): void
    {
        $ai = new FakeAiClient('{"title":"Communion Cups","description":"Buy quality communion cups.","keywords":["communion cups","church supplies"]}');
        $svc = new SeoMetaService($ai);

        $meta = $svc->generate(['name' => 'Communion Cups', 'category' => 'Communion']);

        self::assertSame('Communion Cups', $meta->title);
        self::assertSame('Buy quality communion cups.', $meta->description);
        self::assertSame(['communion cups', 'church supplies'], $meta->keywords);
        // Product facts must reach the model.
        self::assertStringContainsString('Communion Cups', (string) $ai->lastPrompt);
    }

    public function test_tolerates_markdown_fenced_json(): void
    {
        $ai = new FakeAiClient("```json\n{\"title\":\"T\",\"description\":\"D\",\"keywords\":[\"a\"]}\n```");
        $svc = new SeoMetaService($ai);

        $meta = $svc->generate(['name' => 'Widget']);

        self::assertSame('T', $meta->title);
        self::assertSame(['a'], $meta->keywords);
    }

    public function test_clamps_overlong_fields(): void
    {
        $longTitle = str_repeat('a', 120);
        $longDesc = str_repeat('b', 300);
        $ai = new FakeAiClient(json_encode(['title' => $longTitle, 'description' => $longDesc, 'keywords' => []]));
        $svc = new SeoMetaService($ai);

        $meta = $svc->generate(['name' => 'X']);

        self::assertLessThanOrEqual(60, mb_strlen($meta->title));
        self::assertLessThanOrEqual(155, mb_strlen($meta->description));
    }

    public function test_deduplicates_and_caps_keywords(): void
    {
        $kw = ['a', 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l'];
        $ai = new FakeAiClient(json_encode(['title' => 'T', 'description' => 'D', 'keywords' => $kw]));
        $svc = new SeoMetaService($ai);

        $meta = $svc->generate(['name' => 'X']);

        self::assertLessThanOrEqual(10, count($meta->keywords));
        self::assertSame(array_values(array_unique($meta->keywords)), $meta->keywords);
    }

    public function test_missing_name_throws(): void
    {
        $svc = new SeoMetaService(new FakeAiClient('{}'));

        $this->expectException(AiException::class);
        $svc->generate(['category' => 'Communion']);
    }

    public function test_unparseable_reply_throws(): void
    {
        $svc = new SeoMetaService(new FakeAiClient('the model rambled with no json'));

        $this->expectException(AiException::class);
        $svc->generate(['name' => 'X']);
    }
}
