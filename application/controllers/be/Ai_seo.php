<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

use Bethany\Services\Ai\AiException;

/**
 * Admin AI SEO endpoints. Additive-only: nothing here alters existing product/save
 * flows, so there is no regression surface. The storefront "generate meta with AI"
 * button POSTs the product fields here and receives ready-to-save meta as JSON.
 */
class Ai_seo extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model('be/auth_model');
		$this->load->library('ai_seo');
	}

	/**
	 * POST name, description, category, brand, price.
	 * Returns {status:'SUCCESS', meta:{title,description,keywords[]}} or an error.
	 */
	function generate(){
		if ( ! $this->session->userdata('bgs_be_active') ||
		     $this->auth_model->validate_user_access('seo_edit', $this->session->userdata('system_user_id')) == false) {
			echo json_encode(array('status' => 'ERR', 'message' => 'Access denied.'));
			return;
		}

		if ( ! $this->ai_seo->available()) {
			echo json_encode(array('status' => 'ERR', 'message' => 'AI is not configured. Set ANTHROPIC_API_KEY on the host.'));
			return;
		}

		$product = array(
			'name'        => (string) $this->input->post('name'),
			'description' => (string) $this->input->post('description'),
			'category'    => (string) $this->input->post('category'),
			'brand'       => (string) $this->input->post('brand'),
			'price'       => (string) $this->input->post('price'),
		);

		if (trim($product['name']) === '') {
			echo json_encode(array('status' => 'ERR', 'message' => 'A product name is required.'));
			return;
		}

		try {
			$meta = $this->ai_seo->generate_meta($product);
			echo json_encode(array('status' => 'SUCCESS', 'meta' => $meta->toArray()));
		} catch (AiException $e) {
			log_message('error', 'AI SEO generation failed: ' . $e->getMessage());
			echo json_encode(array('status' => 'ERR', 'message' => 'Could not generate SEO meta. Please try again.'));
		}
	}
}
