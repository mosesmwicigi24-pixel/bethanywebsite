<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Bethany\Services\Ai\AnthropicClient;
use Bethany\Services\Ai\GuzzleTransport;
use Bethany\Services\Seo\SeoMetaService;
use Bethany\Services\Seo\StructuredData;
use GuzzleHttp\Client as GuzzleClient;

/**
 * CI3 bridge to the portable Bethany\Services\{Ai,Seo} layer.
 *
 * All CodeIgniter-specific wiring (config lookup, service construction) lives here;
 * the underlying services stay framework-agnostic and unit-tested. The Anthropic API
 * key is read from the ANTHROPIC_API_KEY environment variable (12-factor), passed into
 * the container by docker-compose — it is never committed.
 *
 * Load with: $this->load->library('ai_seo');
 */
class Ai_seo {

	/** @var CI_Controller */
	protected $CI;
	private $metaService = null;

	public function __construct() {
		$this->CI =& get_instance();
	}

	/** True when an API key is configured, so callers can degrade gracefully. */
	public function available() {
		return $this->api_key() !== '';
	}

	private function api_key() {
		// getenv() reads the container process env; $_SERVER covers Apache PassEnv.
		$key = getenv('ANTHROPIC_API_KEY');
		if (($key === false || $key === '') && isset($_SERVER['ANTHROPIC_API_KEY'])) {
			$key = $_SERVER['ANTHROPIC_API_KEY'];
		}
		if ($key === false || $key === '') {
			// Optional fallback if the key is placed in application/config/config.php.
			$key = $this->CI->config->item('anthropic_api_key');
		}
		return is_string($key) ? trim($key) : '';
	}

	private function store_name() {
		$name = $this->CI->config->item('store_name');
		return (is_string($name) && $name !== '') ? $name : 'Bethany House';
	}

	private function meta_service() {
		if ($this->metaService === null) {
			$transport = new GuzzleTransport(new GuzzleClient());
			$client = new AnthropicClient($transport, $this->api_key());
			$this->metaService = new SeoMetaService($client, $this->store_name());
		}
		return $this->metaService;
	}

	/**
	 * Generate SEO meta for a product.
	 *
	 * @param array $product name/description/category/brand/price
	 * @return \Bethany\Services\Seo\SeoMeta
	 * @throws \Bethany\Services\Ai\AiException
	 */
	public function generate_meta(array $product) {
		return $this->meta_service()->generate($product);
	}

	/** JSON-LD builder for storefront structured data (no AI, no network). */
	public function structured_data() {
		$base = $this->CI->config->item('base_url');
		$base = is_string($base) && $base !== '' ? rtrim($base, '/') : 'https://bethanyhouse.co.ke';
		return new StructuredData($this->store_name(), $base, 'KES');
	}
}
