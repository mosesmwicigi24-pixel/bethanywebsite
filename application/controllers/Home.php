<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {
    function __construct(){
		parent::__construct();
        $this->flexi = new stdClass;
        $this->load->library('flexi_cart');     
		$this->load->model('main_model');
        $this->load->model('be/currencies_model');

	}

	function index(){

        $meta = $this->main_model->get_global_meta();
        $data['meta'] = $meta;

        $data['page_title'] = $meta['title'];

        $data['cur'] = 'Home';

        $data['store_information'] = $this->main_model->get_store_information();
        $data['home_nested_product_categories'] = $this->main_model->get_home_nested_product_categories();
        $data['home_top_product_categories'] = $this->main_model->get_home_top_product_categories();
        
        //FEATURED CATEGORIES
        $q = $this->main_model->get_home_featured_product_categories();
        $data['home_featured_product_categories'] = $q['records'];
        $data['num_home_featured_product_categories'] = $q['record_count'];

        //PROMO BANNERS
        $data['home_promo_banners'] = $this->main_model->get_home_promo_banners();

        $data['home_blog_articles'] = $this->main_model->get_home_blog_articles();
        $data['num_home_blog_articles'] = $this->main_model->get_num_home_blog_articles();

        $data['home_sliders'] = $this->main_model->get_home_sliders();

        $q = $this->main_model->get_home_testimonials();
        $data['home_testimonials'] = $q['records'];
        $data['num_home_testimonials'] = $q['record_count'];

        $data['default_currency'] = $this->currencies_model->get_default_currency();

        $data['cart_data'] = $this->flexi_cart->cart_items();

		// $data['main_content'] = 'fe/landing';
        $this->load->view('fe/landing',$data);
	}

    function landing() {
        $meta = $this->main_model->get_global_meta();
        $data['meta'] = $meta;

        $data['page_title'] = $meta['title'];

        $data['cur'] = 'Landing';

        $data['store_information'] = $this->main_model->get_store_information();
        $data['home_nested_product_categories'] = $this->main_model->get_home_nested_product_categories();
        $data['home_top_product_categories'] = $this->main_model->get_home_top_product_categories();
        
        //FEATURED CATEGORIES
        $q = $this->main_model->get_home_featured_product_categories();
        $data['home_featured_product_categories'] = $q['records'];
        $data['num_home_featured_product_categories'] = $q['record_count'];

        //PROMO BANNERS
        $data['home_promo_banners'] = $this->main_model->get_home_promo_banners();

        $data['home_blog_articles'] = $this->main_model->get_home_blog_articles();
        $data['num_home_blog_articles'] = $this->main_model->get_num_home_blog_articles();

        $data['home_sliders'] = $this->main_model->get_home_sliders();

        $q = $this->main_model->get_home_testimonials();
        $data['home_testimonials'] = $q['records'];
        $data['num_home_testimonials'] = $q['record_count'];

        $data['default_currency'] = $this->currencies_model->get_default_currency();

        $data['cart_data'] = $this->flexi_cart->cart_items();

        // $data['main_content'] = 'fe/landing';
        $this->load->view('fe/landing',$data);

    }

    function load_product_quickview($product_id) {

        $data['product'] = $this->main_model->get_product_by_id($product_id);

        $data['product_product_categories'] = $this->main_model->get_product_product_categories_by_id($product_id);
        $data['product_images'] = $this->main_model->get_product_images_by_id($product_id);
        $data['product_num_images'] = $this->main_model->get_product_num_images_by_id($product_id);
        // $data['product_product_sizes'] = $this->main_model->get_product_product_sizes_by_id($product_id);
        // $data['num_product_product_sizes'] = $this->main_model->get_num_product_product_sizes_by_id($product_id);
        // $data['product_colors'] = $this->main_model->get_product_colors_by_id($product_id);
        // $data['num_product_colors'] = $this->main_model->get_num_product_colors_by_id($product_id);
        $data['product_attributes'] = $this->main_model->get_product_attributes_by_id($product_id);
        $data['num_product_attributes'] = $this->main_model->get_num_product_attributes_by_id($product_id);
        $data['product_variations'] = $this->main_model->get_product_variations_by_id($product_id);
        $data['num_product_variations'] = $this->main_model->get_num_product_variations_by_id($product_id);

        // $data['product_product_categories'] = $this->main_model->get_product_product_categories_by_id($product_id);
        // $data['product_images'] = $this->main_model->get_product_images_by_id($product_id);
        // $data['product_num_images'] = $this->main_model->get_product_num_images_by_id($product_id);
        // $data['product_product_sizes'] = $this->main_model->get_product_product_sizes_by_id($product_id);
        // $data['num_product_product_sizes'] = $this->main_model->get_num_product_product_sizes_by_id($product_id);
        // $data['product_colors'] = $this->main_model->get_product_colors_by_id($product_id);
        // $data['num_product_colors'] = $this->main_model->get_num_product_colors_by_id($product_id);
        // $data['product_attributes'] = $this->main_model->get_product_attributes_by_id($product_id);
        // $data['num_product_attributes'] = $this->main_model->get_num_product_attributes_by_id($product_id);
        $data['default_currency'] = $this->currencies_model->get_default_currency();

        $this->load->view('fe/jsloads/product_quickview',$data);

    }

    function add_to_favorites() {
        if($this->session->userdata('bgs_fe_login_state')){
            $q = $this->main_model->add_to_favorites();
            if($q['res'] == TRUE){
                $resp = array('status' => 'SUCCESS','message' => $q['dt']);         
            }else{                  
                $resp = array('status' => 'ERR','message' => $q['dt']);         
            }
        }else{
            $resp = array('status' => 'ERR','message' => 'Please Login to add this product to Favorites');         
        }
        echo json_encode($resp);
    }

    function add_to_compare() {
        $product_id = $this->input->post('product_id');
        if ($this->session->userdata('compare_product_id')){
            $old_compare_product_id =  $this->session->userdata('compare_product_id');

            $found = array_search($product_id, $old_compare_product_id);
            if ($found == false) {
                array_push($old_compare_product_id, $product_id);

                $this->session->set_userdata('compare_product_id', $old_compare_product_id);

                $resp = array('status' => 'SUCCESS','message' => 'Product added successfuly to comparison list.'); 
            }else{
                $resp = array('status' => 'SUCCESS','message' => 'Product added successfuly to comparison list.');
            }
        }else{
            $compare_product_id = array();
            $compare_product_id[] = $product_id;

            $this->session->set_userdata('compare_product_id', $compare_product_id);
            $resp = array('status' => 'SUCCESS','message' => 'Product added successfuly to comparison list.');
        }        
        echo json_encode($resp);
    }

    function remove_compare_product($product_id) {
        if ($this->session->userdata('compare_product_id')){
            $old_compare_product_id =  $this->session->userdata('compare_product_id');

            if (($key = array_search($product_id, $old_compare_product_id)) !== false) {
                unset($old_compare_product_id[$key]);

                $this->session->set_userdata('compare_product_id', $old_compare_product_id);
            }

        }     
        $resp = array('status' => 'SUCCESS','message' => 'Product removed successfully from the comparison list');  
        echo json_encode($resp);
    }

    function loadjs_compare_products() {
        $data['default_currency'] = $this->currencies_model->get_default_currency();        
        if ($this->session->userdata('compare_product_id')){
            $data['compare_products'] = $this->main_model->get_compare_products();
        }
       $this->load->view('fe/jsloads/compare',$data);
   }

    function shop() {
        $meta = $this->main_model->get_global_meta();
        $data['meta'] = $meta;

        $data['page_title'] = $meta['title'];
        $data['cur'] = '';

        $data['store_information'] = $this->main_model->get_store_information();
        $data['home_nested_product_categories'] = $this->main_model->get_home_nested_product_categories();
        $data['home_top_product_categories'] = $this->main_model->get_home_top_product_categories();
        
        //FEATURED CATEGORIES
        $q = $this->main_model->get_home_featured_product_categories();
        $data['home_featured_product_categories'] = $q['records'];
        $data['num_home_featured_product_categories'] = $q['record_count'];

        //PROMO BANNERS
        $data['home_promo_banners'] = $this->main_model->get_home_promo_banners();

        $data['home_blog_articles'] = $this->main_model->get_home_blog_articles();
        $data['num_home_blog_articles'] = $this->main_model->get_num_home_blog_articles();

        $data['home_sliders'] = $this->main_model->get_home_sliders();

        $q = $this->main_model->get_home_testimonials();
        $data['home_testimonials'] = $q['records'];
        $data['num_home_testimonials'] = $q['record_count'];

        // $data['store_information'] = $this->main_model->get_store_information();
        // $data['home_nested_product_categories'] = $this->main_model->get_home_nested_product_categories();
        // $data['nested_product_categories'] = $this->main_model->get_nested_product_categories();

        // $q = $this->main_model->get_brands();
        // $data['brands'] = $q['records'];
        // $data['num_brands'] = $q['record_count'];

        // $q = $this->main_model->get_shop_products();
        // $data['shop_products'] = $q['records'];
        // $data['num_shop_products'] = $q['record_count'];

        $data['default_currency'] = $this->currencies_model->get_default_currency();

        $data['cart_data'] = $this->flexi_cart->cart_items();

        // $data['main_content'] = 'fe/shop';
        $data['main_content'] = 'fe/home';
        $this->load->view('fe/includes/template',$data);

    }

    function product($product_reference_id) {

        $meta = $this->main_model->get_product_meta($product_reference_id);
        $data['meta'] = $meta;

        $product = $this->main_model->get_product_by_reference_id($product_reference_id);
        foreach ($product as $row) {
            $product_name = $row->product_name;
        }

        $data['page_title'] = $meta['title'];
        $data['cur'] = '';

        $data['store_information'] = $this->main_model->get_store_information();
        $data['home_nested_product_categories'] = $this->main_model->get_home_nested_product_categories();

        $data['product'] = $product;
        
        $data['product_product_categories'] = $this->main_model->get_product_product_categories_by_reference_id($product_reference_id);
        $data['product_images'] = $this->main_model->get_product_images_by_reference_id($product_reference_id);
        $data['product_num_images'] = $this->main_model->get_product_num_images_by_reference_id($product_reference_id);
        // $data['product_product_sizes'] = $this->main_model->get_product_product_sizes_by_reference_id($product_reference_id);
        // $data['num_product_product_sizes'] = $this->main_model->get_num_product_product_sizes_by_reference_id($product_reference_id);
        // $data['product_colors'] = $this->main_model->get_product_colors_by_reference_id($product_reference_id);
        // $data['num_product_colors'] = $this->main_model->get_num_product_colors_by_reference_id($product_reference_id);
        $data['product_attributes'] = $this->main_model->get_product_attributes_by_reference_id($product_reference_id);
        $data['num_product_attributes'] = $this->main_model->get_num_product_attributes_by_reference_id($product_reference_id);
        $data['product_variations'] = $this->main_model->get_product_variations_by_reference_id($product_reference_id);
        $data['num_product_variations'] = $this->main_model->get_num_product_variations_by_reference_id($product_reference_id);

        $q = $this->main_model->get_product_reviews_by_reference_id($product_reference_id);
        $data['product_reviews'] = $q['records'];
        $data['num_product_reviews'] = $q['records_count'];
        $data['average_product_review'] = $q['average_review'];

        $data['related_products'] = $this->main_model->get_product_related_products_by_reference_id($product_reference_id);

        $data['default_currency'] = $this->currencies_model->get_default_currency();

        $data['cart_data'] = $this->flexi_cart->cart_items();

        $data['main_content'] = 'fe/product';
        $this->load->view('fe/includes/template',$data);

    }

    function submit_product_review() {
        $data = array(
            'product_id' => $this->input->post('product_id'),
            'review_value' => $this->input->post('review_value'),
            'review_description' => $this->input->post('review_description'),
            'review_name' => $this->input->post('review_name'),
            'review_email' => $this->input->post('review_email')
        );
        $q = $this->main_model->submit_review($data);
        if($q['res'] == true){
            $resp = array('status' => 'SUCCESS','message' => $q['dt']);
        }else{
            $resp = array('status' => 'ERR','message' => $q['dt']);
        }
        echo json_encode($resp);

    }

    function loadjs_filter_products() {

        $q = $this->main_model->get_filter_shop_products();
        $data['default_currency'] = $this->currencies_model->get_default_currency();
        $data['shop_products'] = $q['records'];
        $dt = $this->load->view('fe/jsloads/ajax_shop_products',$data, true);
        $response = array('products' => $dt, 'products_count' => $q['record_count']);
        echo json_encode($response);

    }

    function category($product_category_reference_id) {

        $meta = $this->main_model->get_product_category_meta($product_category_reference_id);
        $data['meta'] = $meta;

        $product_category = $this->main_model->get_product_category_by_reference_id($product_category_reference_id);
        foreach ($product_category as $row) {
            $product_category_name = $row->product_category_name;
        }

        $data['page_title'] = $meta['title'];
        $data['cur'] = '';

        $data['store_information'] = $this->main_model->get_store_information();
        $data['home_nested_product_categories'] = $this->main_model->get_home_nested_product_categories();

        $data['product_category'] = $product_category;
        $data['product_category_name'] = $product_category_name;

        $q = $this->main_model->get_category_products_by_reference_id($product_category_reference_id);
        $data['shop_products'] = $q['records'];
        $data['num_shop_products'] = $q['record_count'];

        $data['nested_product_categories'] = $this->main_model->get_nested_product_categories();
        // $this->main_model->get_nested_product_subcategories_by_reference_id($product_category_reference_id);

        $q = $this->main_model->get_brands();
        $data['brands'] = $q['records'];
        $data['num_brands'] = $q['record_count'];

        $data['default_currency'] = $this->currencies_model->get_default_currency();

        $data['cart_data'] = $this->flexi_cart->cart_items();

        $data['main_content'] = 'fe/product_category';
        $this->load->view('fe/includes/template',$data);
    }

    function new_arrivals() {
        $meta = $this->main_model->get_global_meta();
        $data['meta'] = $meta;

        $data['page_title'] = 'New Arrivals';
        $data['cur'] = 'New Arrivals';

        $data['store_information'] = $this->main_model->get_store_information();
        $data['home_nested_product_categories'] = $this->main_model->get_home_nested_product_categories();
        $data['nested_product_categories'] = $this->main_model->get_nested_product_categories();

        $q = $this->main_model->get_brands();
        $data['brands'] = $q['records'];
        $data['num_brands'] = $q['record_count'];

        $q = $this->main_model->get_new_arrival_products();
        $data['shop_products'] = $q['records'];
        $data['num_shop_products'] = $q['record_count'];

        $data['default_currency'] = $this->currencies_model->get_default_currency();

        $data['cart_data'] = $this->flexi_cart->cart_items();

        $data['main_content'] = 'fe/new_arrivals';
        $this->load->view('fe/includes/template',$data);

    }

    function featured() {
        $meta = $this->main_model->get_global_meta();
        $data['meta'] = $meta;

        $data['page_title'] = 'Featured Products';
        $data['cur'] = 'Featured';

        $data['store_information'] = $this->main_model->get_store_information();
        $data['home_nested_product_categories'] = $this->main_model->get_home_nested_product_categories();
        $data['nested_product_categories'] = $this->main_model->get_nested_product_categories();

        $q = $this->main_model->get_brands();
        $data['brands'] = $q['records'];
        $data['num_brands'] = $q['record_count'];

        $q = $this->main_model->get_featured_products();
        $data['shop_products'] = $q['records'];
        $data['num_shop_products'] = $q['record_count'];

        $data['default_currency'] = $this->currencies_model->get_default_currency();

        $data['cart_data'] = $this->flexi_cart->cart_items();

        $data['main_content'] = 'fe/featured';
        $this->load->view('fe/includes/template',$data);
    }

    function special_offers() {
        $meta = $this->main_model->get_global_meta();
        $data['meta'] = $meta;

        $data['page_title'] = 'Special Offers';
        $data['cur'] = 'Special Offers';

        $data['store_information'] = $this->main_model->get_store_information();
        $data['home_nested_product_categories'] = $this->main_model->get_home_nested_product_categories();
        $data['nested_product_categories'] = $this->main_model->get_nested_product_categories();

        $q = $this->main_model->get_brands();
        $data['brands'] = $q['records'];
        $data['num_brands'] = $q['record_count'];

        $q = $this->main_model->get_special_offer_products();
        $data['shop_products'] = $q['records'];
        $data['num_shop_products'] = $q['record_count'];

        $data['default_currency'] = $this->currencies_model->get_default_currency();

        $data['cart_data'] = $this->flexi_cart->cart_items();

        $data['main_content'] = 'fe/special_offers';
        $this->load->view('fe/includes/template',$data);
    }

    function deal_of_the_week() {
        $meta = $this->main_model->get_global_meta();
        $data['meta'] = $meta;

        $data['page_title'] = 'Deal of the Week';
        $data['cur'] = 'Deal of the Week';

        $data['store_information'] = $this->main_model->get_store_information();
        $data['home_nested_product_categories'] = $this->main_model->get_home_nested_product_categories();
        $data['nested_product_categories'] = $this->main_model->get_nested_product_categories();

        $q = $this->main_model->get_brands();
        $data['brands'] = $q['records'];
        $data['num_brands'] = $q['record_count'];

        $q = $this->main_model->get_deal_of_the_week_products();
        $data['shop_products'] = $q['records'];
        $data['num_shop_products'] = $q['record_count'];

        $data['default_currency'] = $this->currencies_model->get_default_currency();

        $data['cart_data'] = $this->flexi_cart->cart_items();

        $data['main_content'] = 'fe/deal_of_the_week';
        $this->load->view('fe/includes/template',$data);
    }

    function ajax_search() {
        $search_result = '';
        $search_keyword = $this->input->post('search_keyword');
        $search_element_id = $this->input->post('search_element_id');

        $q = $this->main_model->get_ajax_search_results($search_keyword);
        $search_results = $q['records'];
        $num_search_results = $this->main_model->get_num_ajax_search_results($search_keyword);

        $search_result .= '<p><b>Search Results</b></p>';

        if ($num_search_results > 0) {
            $search_result .= '<ul>';

            foreach ($search_results as $row) {
                $product_price = 0;
                if ($row->sale_price > 0) {
                    $product_price = $row->sale_price;
                }else {
                    $product_price = $row->regular_price;
                }
                if ($row->product_image_thumb != '' && file_exists("./uploads/product_images/thumbs/" . $row->product_image_thumb)) {
                    $search_result .= '<li><a href="' . base_url() . 'product/' . $row->product_reference_id . '"><img src="' . base_url() . 'uploads/product_images/thumbs/' . $row->product_image_thumb . '" style="width:40px;"><div class="prod"><span class="font-weight-500">' . $row->product_name . '</span><br>KES ' . number_format($product_price) . '</div></a></li>';
                }else {
                    $search_result .= '<li><a href="' . base_url() . 'product/' . $row->product_reference_id . '"><img src="' . base_url() . 'assets/fe/img/placeholder.png" style="width:40px;"><div class="prod"><span class="font-weight-500">' . $row->product_name . '</span><br>KES ' . number_format($product_price) . '</div></a></li>';
                }
                
            }

            $search_result .= '</ul>';
        }

        $search_result .= '<div class="search-footer text-center"><a href="javascript:void(0);" onclick="return submit_search(' . "'" . $search_element_id . "'" . ');"><b>See All Results (' . number_format($num_search_results) . ')</b></a></div>';

        echo $search_result;

    }

    function search() {
        $meta = $this->main_model->get_global_meta();
        $data['meta'] = $meta;

        $data['page_title'] = 'Search Results';
        $data['cur'] = 'Search Results';

        $search_keyword = $this->input->post('search_keyword');

        $q = $this->main_model->get_search_results($search_keyword);
        $data['shop_products'] = $q['records'];
        $data['num_shop_products'] = $q['record_count'];

        $data['search_keyword'] = $search_keyword;

        $data['store_information'] = $this->main_model->get_store_information();
        $data['home_nested_product_categories'] = $this->main_model->get_home_nested_product_categories();
        $data['nested_product_categories'] = $this->main_model->get_nested_product_categories();

        $q = $this->main_model->get_brands();
        $data['brands'] = $q['records'];
        $data['num_brands'] = $q['record_count'];

        $data['default_currency'] = $this->currencies_model->get_default_currency();

        $data['main_content'] = 'fe/search_results';
        $this->load->view('fe/includes/template',$data);

    }
    function compare() {
        $meta = $this->main_model->get_global_meta();
        $data['meta'] = $meta;

        $data['page_title'] = 'Compare Products';
        $data['cur'] = '';

        $data['store_information'] = $this->main_model->get_store_information();
        $data['home_nested_product_categories'] = $this->main_model->get_home_nested_product_categories();
        $data['nested_product_categories'] = $this->main_model->get_nested_product_categories();

        $q = $this->main_model->get_brands();
        $data['brands'] = $q['records'];
        $data['num_brands'] = $q['record_count'];

        if ($this->session->userdata('compare_product_id')){
            $data['compare_products'] = $this->main_model->get_compare_products();
        }

        $data['default_currency'] = $this->currencies_model->get_default_currency();

        $data['cart_data'] = $this->flexi_cart->cart_items();

        $data['main_content'] = 'fe/compare';
        $this->load->view('fe/includes/template',$data);
    }

    function blog(){
        $meta = $this->main_model->get_global_meta();
        $data['meta'] = $meta;

        $data['page_title'] = 'Blog';
        $data['cur'] = 'Blog';

        $q = $this->main_model->get_blog_articles();
        $data['blog_articles'] = $q['records'];
        $data['num_blog_articles'] = $q['record_count'];

        $q = $this->main_model->get_blog_recent_articles();
        $data['blog_recent_articles'] = $q['records'];
        $data['num_blog_recent_articles'] = $q['record_count'];

        //$data['blog_articles'] = $this->main_model->get_blog_articles();
        $data['blog_categories'] = $this->main_model->get_blog_categories();
        $data['num_blog_categories'] = $this->main_model->get_num_blog_categories();

        $data['store_information'] = $this->main_model->get_store_information();
        $data['home_nested_product_categories'] = $this->main_model->get_home_nested_product_categories();
        $data['nested_product_categories'] = $this->main_model->get_nested_product_categories();
        $data['default_currency'] = $this->currencies_model->get_default_currency();
        $data['cart_data'] = $this->flexi_cart->cart_items();

        $data['main_content'] = 'fe/blog';
        $this->load->view('fe/includes/template',$data);
    }

    function blog_article($blog_article_reference_id) {

        $meta = $this->main_model->get_blog_article_meta($blog_article_reference_id);
        $data['meta'] = $meta;

        $blog_article_title = '';
        $blog_article = $this->main_model->get_blog_article($blog_article_reference_id);

        foreach($blog_article  as $row){
            $blog_article_title = $row->blog_article_title;
        }
        $data['blog_article'] = $blog_article;
        $data['blog_article_title'] = $blog_article_title;

        $data['blog_categories'] = $this->main_model->get_blog_categories();
        $data['num_blog_categories'] = $this->main_model->get_num_blog_categories();

        $q = $this->main_model->get_blog_recent_articles();
        $data['blog_recent_articles'] = $q['records'];
        $data['num_blog_recent_articles'] = $q['record_count'];

        $data['page_title'] = $meta['title'];
        $data['cur'] = 'Blog';

        $data['store_information'] = $this->main_model->get_store_information();
        $data['home_nested_product_categories'] = $this->main_model->get_home_nested_product_categories();
        $data['nested_product_categories'] = $this->main_model->get_nested_product_categories();
        $data['default_currency'] = $this->currencies_model->get_default_currency();
        $data['cart_data'] = $this->flexi_cart->cart_items();

        $data['main_content'] = 'fe/blog_article';
        $this->load->view('fe/includes/template',$data);
    }

    function blog_category($blog_category_reference_id) {
        $meta = $this->main_model->get_global_meta();
        $data['meta'] = $meta;

        $blog_category_name = '';
        $blog_category = $this->main_model->get_blog_category($blog_category_reference_id);

        foreach($blog_category  as $row){
            $blog_category_name = $row->blog_category_name;
        }
        $data['blog_category'] = $blog_category;
        $data['blog_category_name'] = $blog_category_name;

        $data['blog_categories'] = $this->main_model->get_blog_categories();
        $data['num_blog_categories'] = $this->main_model->get_num_blog_categories();

        $q = $this->main_model->get_blog_category_articles($blog_category_reference_id);
        $data['blog_category_articles'] = $q['records'];
        $data['num_blog_category_articles'] = $q['record_count'];

        $data['page_title'] = 'Blog | ' . $blog_category_name;
        $data['cur'] = 'Blog';

        $q = $this->main_model->get_blog_recent_articles();
        $data['blog_recent_articles'] = $q['records'];
        $data['num_blog_recent_articles'] = $q['record_count'];

        $data['store_information'] = $this->main_model->get_store_information();
        $data['home_nested_product_categories'] = $this->main_model->get_home_nested_product_categories();
        $data['nested_product_categories'] = $this->main_model->get_nested_product_categories();
        $data['default_currency'] = $this->currencies_model->get_default_currency();
        $data['cart_data'] = $this->flexi_cart->cart_items();

        $data['main_content'] = 'fe/blog_category';
        $this->load->view('fe/includes/template',$data);
    }
    function about_us() {
        $meta = $this->main_model->get_global_meta();
        $data['meta'] = $meta;

        $data['page_title'] = 'About Us';
        $data['cur'] = 'About Us';

        $data['store_information'] = $this->main_model->get_store_information();
        $data['home_nested_product_categories'] = $this->main_model->get_home_nested_product_categories();
        $data['home_top_product_categories'] = $this->main_model->get_home_top_product_categories();
        
        $data['default_currency'] = $this->currencies_model->get_default_currency();

        $data['about_us'] = $this->main_model->get_about_us();

        $data['cart_data'] = $this->flexi_cart->cart_items();

        $data['main_content'] = 'fe/about_us';
        $this->load->view('fe/includes/template',$data);

    }

    function terms_and_conditions() {
        $meta = $this->main_model->get_global_meta();
        $data['meta'] = $meta;

        $data['page_title'] = 'Terms &amp; Conditions';
        $data['cur'] = 'About Us';

        $data['store_information'] = $this->main_model->get_store_information();
        $data['home_nested_product_categories'] = $this->main_model->get_home_nested_product_categories();
        $data['home_top_product_categories'] = $this->main_model->get_home_top_product_categories();
        
        $data['default_currency'] = $this->currencies_model->get_default_currency();

        $data['terms_and_conditions'] = $this->main_model->get_terms_and_conditions();

        $data['cart_data'] = $this->flexi_cart->cart_items();

        $data['main_content'] = 'fe/terms_and_conditions';
        $this->load->view('fe/includes/template',$data);

    }

    function privacy_policy() {
        $meta = $this->main_model->get_global_meta();
        $data['meta'] = $meta;

        $data['page_title'] = 'Privacy Policy';
        $data['cur'] = 'About Us';

        $data['store_information'] = $this->main_model->get_store_information();
        $data['home_nested_product_categories'] = $this->main_model->get_home_nested_product_categories();
        $data['home_top_product_categories'] = $this->main_model->get_home_top_product_categories();
        
        $data['default_currency'] = $this->currencies_model->get_default_currency();

        $data['privacy_policy'] = $this->main_model->get_privacy_policy();

        $data['cart_data'] = $this->flexi_cart->cart_items();

        $data['main_content'] = 'fe/privacy_policy';
        $this->load->view('fe/includes/template',$data);

    }

    function return_policy() {
        $meta = $this->main_model->get_global_meta();
        $data['meta'] = $meta;

        $data['page_title'] = 'Return Policy';
        $data['cur'] = 'About Us';

        $data['store_information'] = $this->main_model->get_store_information();
        $data['home_nested_product_categories'] = $this->main_model->get_home_nested_product_categories();
        $data['home_top_product_categories'] = $this->main_model->get_home_top_product_categories();
        
        $data['default_currency'] = $this->currencies_model->get_default_currency();

        $data['return_policy'] = $this->main_model->get_return_policy();

        $data['cart_data'] = $this->flexi_cart->cart_items();

        $data['main_content'] = 'fe/return_policy';
        $this->load->view('fe/includes/template',$data);

    }

    function how_to_shop() {
        $meta = $this->main_model->get_global_meta();
        $data['meta'] = $meta;

        $data['page_title'] = 'How To Shop';
        $data['cur'] = 'Help Center';

        $data['store_information'] = $this->main_model->get_store_information();
        $data['home_nested_product_categories'] = $this->main_model->get_home_nested_product_categories();
        $data['home_top_product_categories'] = $this->main_model->get_home_top_product_categories();
        
        $data['default_currency'] = $this->currencies_model->get_default_currency();

        $data['how_to_shop'] = $this->main_model->get_how_to_shop();

        $data['cart_data'] = $this->flexi_cart->cart_items();

        $data['main_content'] = 'fe/how_to_shop';
        $this->load->view('fe/includes/template',$data);

    }

    function faqs() {
        $meta = $this->main_model->get_global_meta();
        $data['meta'] = $meta;

        $data['page_title'] = 'Frequently Asked Questions';
        $data['cur'] = 'Help Center';

        $data['store_information'] = $this->main_model->get_store_information();
        $data['home_nested_product_categories'] = $this->main_model->get_home_nested_product_categories();
        $data['home_top_product_categories'] = $this->main_model->get_home_top_product_categories();
        
        $data['default_currency'] = $this->currencies_model->get_default_currency();

        $data['faqs'] = $this->main_model->get_faqs();

        $data['cart_data'] = $this->flexi_cart->cart_items();

        $data['main_content'] = 'fe/faqs';
        $this->load->view('fe/includes/template',$data);

    }

    function contact_us() {
        $meta = $this->main_model->get_global_meta();
        $data['meta'] = $meta;

        $data['page_title'] = 'Contact Us';
        $data['cur'] = 'Help Center';

        $data['store_information'] = $this->main_model->get_store_information();
        $data['home_nested_product_categories'] = $this->main_model->get_home_nested_product_categories();
        $data['home_top_product_categories'] = $this->main_model->get_home_top_product_categories();
        
        $data['default_currency'] = $this->currencies_model->get_default_currency();

        $data['cart_data'] = $this->flexi_cart->cart_items();

        $data['main_content'] = 'fe/contact_us';
        $this->load->view('fe/includes/template',$data);

    }

    function submit_contact(){
        $q = $this->main_model->submit_contact();

        if ($q['res'] == true){
            $resp = array('status' => 'SUCCESS','message' => $q['dt']);
        }else{
            $resp = array('status' => 'ERR','message' => $q['dt']);
        }
        echo json_encode($resp);
    }



}