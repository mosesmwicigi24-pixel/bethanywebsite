<?php

declare(strict_types=1);

namespace Bethany\Services\Tests\Ai;

use Bethany\Services\Ai\AiException;
use Bethany\Services\Ai\AnthropicClient;
use Bethany\Services\Tests\Fake\FakeTransport;
use PHPUnit\Framework\TestCase;

final class AnthropicClientTest extends TestCase
{
    public function test_sends_expected_request_and_extracts_text(): void
    {
        $transport = new FakeTransport([
            'content' => [
                ['type' => 'text', 'text' => 'Hello world'],
            ],
        ]);
        $client = new AnthropicClient($transport, 'sk-test', 'claude-haiku-4-5-20251001');

        $out = $client->complete('Say hi', ['system' => 'be brief', 'max_tokens' => 50, 'temperature' => 0.2]);

        self::assertSame('Hello world', $out);
        self::assertSame('sk-test', $transport->headers['x-api-key']);
        self::assertSame('2023-06-01', $transport->headers['anthropic-version']);
        self::assertSame('claude-haiku-4-5-20251001', $transport->body['model']);
        self::assertSame('be brief', $transport->body['system']);
        self::assertSame(50, $transport->body['max_tokens']);
        self::assertSame('Say hi', $transport->body['messages'][0]['content']);
    }

    public function test_concatenates_multiple_text_blocks(): void
    {
        $transport = new FakeTransport([
            'content' => [
                ['type' => 'text', 'text' => 'foo '],
                ['type' => 'text', 'text' => 'bar'],
            ],
        ]);
        $client = new AnthropicClient($transport, 'sk-test');

        self::assertSame('foo bar', $client->complete('x'));
    }

    public function test_missing_api_key_throws(): void
    {
        $client = new AnthropicClient(new FakeTransport([]), '');

        $this->expectException(AiException::class);
        $client->complete('x');
    }

    public function test_api_error_payload_throws(): void
    {
        $transport = new FakeTransport(['error' => ['message' => 'overloaded']]);
        $client = new AnthropicClient($transport, 'sk-test');

        $this->expectException(AiException::class);
        $this->expectExceptionMessage('overloaded');
        $client->complete('x');
    }

    public function test_empty_text_throws(): void
    {
        $transport = new FakeTransport(['content' => [['type' => 'text', 'text' => '   ']]]);
        $client = new AnthropicClient($transport, 'sk-test');

        $this->expectException(AiException::class);
        $client->complete('x');
    }
}
