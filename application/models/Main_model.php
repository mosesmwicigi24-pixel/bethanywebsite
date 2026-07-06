<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

class Main_model extends CI_Model{
    public function __construct(){
        parent::__construct();
        $this->load->library('phpmailer');
        $this->load->model('be/email_accounts_model');
        $this->load->model('be/email_notification_settings_model');
    }


    //META INFORMATION
    function get_global_meta(){
        $meta_title = '';
        $meta_description = '';
        $meta_keywords = '';
        $meta_sitemap_link = '';
        $meta_image = '';

        //GLOBAL META
        $this->db->from('seo');
        $seo = $this->db->get()->result();

        foreach ($seo as $row) {
            $meta_title = $row->site_title;
            $meta_description = $row->site_description;
            $meta_keywords = $row->site_keywords;
            $meta_sitemap_link = $row->sitemap_link;
        }

        //STORE INFORMATION
        $this->db->from('store_information');
        $store_information = $this->db->get()->result();

        foreach ($store_information as $row) {
            if ($row->store_logo != '' && file_exists("./uploads/store_logo/" . $row->store_logo)) {
                $meta_image =  base_url() . 'uploads/store_logo/' . $row->store_logo;
            } else {
                $meta_image = base_url() . 'assets/fe/img/logo.png';
            }
        }

        $arrayMeta = array('title' => $meta_title, 'description' => $meta_description, 'keywords' => $meta_keywords, 'sitemap' => $meta_sitemap_link, 'image' => $meta_image);

        return $arrayMeta;
    }

    function get_product_meta($product_reference_id){
        $globalMeta = $this->get_global_meta();
        
        $product_meta_title = '';
        $product_meta_description = '';
        $product_meta_keywords = '';
        $product_meta_image = '';

        $product = $this->get_product_by_reference_id($product_reference_id);
        foreach ($product as $row) {
            $product_meta_title = $row->product_name;
            if ($row->seo_description == ''){
                $product_meta_description = $row->seo_description;
            } else {
                $product_meta_description = $row->product_description;
            }
            if ($row->seo_keywords == ''){
                $product_meta_keywords = $row->seo_keywords;
            } else {
                $product_meta_keywords = $row->product_name;
            }
            if ($row->product_image_thumb != '' && file_exists("./uploads/product_images/thumbs/" . $row->product_image_thumb)) {
                $product_meta_image =  base_url() . 'uploads/product_images/thumbs/' . $row->product_image_thumb;
            }
        }

        if ($product_meta_image == '') {
            $product_meta_image = $globalMeta['image'];
        }

        $arrayMeta = array('title' => $product_meta_title, 'description' => $product_meta_description, 'keywords' => $product_meta_keywords, 'sitemap' => $globalMeta['sitemap'], 'image' => $product_meta_image);

        return $arrayMeta;
    }

    function get_product_category_meta($product_category_reference_id){
        $globalMeta = $this->get_global_meta();

        $category_meta_title = '';
        $category_meta_description = '';
        $category_meta_keywords = '';
        $category_meta_image = '';

        $category = $this->get_product_category_by_reference_id($product_category_reference_id);
        foreach ($category as $row) {
            if ($row->seo_title != '') {
                $category_meta_title = $row->seo_title;
            } else {
                $category_meta_title = $row->product_category_name;
            }
            if ($row->seo_description == ''){
                $category_meta_description = $row->seo_description;
            } else {
                $category_meta_description = $row->description;
            }
            if ($row->seo_keywords == ''){
                $category_meta_keywords = $row->seo_keywords;
            }
            if ($row->thumb_image != '' && file_exists("./uploads/product_category_cover_images/thumbs/" . $row->thumb_image)) {
                $category_meta_image =  base_url() . 'uploads/product_category_cover_images/thumbs/' . $row->thumb_image;
            }
        }
        if ($category_meta_description == '') {
            $category_meta_description = $globalMeta['description'];
        }
        if ($category_meta_keywords == '') {
            $category_meta_keywords = $globalMeta['keywords'];
        }
        if ($category_meta_image == '') {
            $category_meta_image = $globalMeta['image'];
        }
        $arrayMeta = array('title' => $category_meta_title, 'description' => $category_meta_description, 'keywords' => $category_meta_keywords, 'sitemap' => $globalMeta['sitemap'], 'image' => $category_meta_image);
        return $arrayMeta;
    }

    function get_blog_article_meta($blog_article_reference_id){
        $globalMeta = $this->get_global_meta();

        $blog_meta_title = '';
        $blog_meta_description = '';
        $blog_meta_keywords = '';
        $blog_meta_image = '';

        $blog = $this->get_blog_article($blog_article_reference_id);

        foreach ($blog as $row) {
            $blog_meta_title = $row->blog_article_title;
            if ($row->seo_description == ''){
                $blog_meta_description = $row->seo_description;
            }
            if ($row->seo_keywords == ''){
                $blog_meta_keywords = $row->seo_keywords;
            }
            if ($row->thumb_image != '' && file_exists("./uploads/blog_article_cover_images/thumbs/" . $row->thumb_image)) {
                $blog_meta_image =  base_url() . 'uploads/blog_article_cover_images/thumbs/' . $row->thumb_image;
            }
        }
        if ($blog_meta_description == '') {
            $blog_meta_description = $globalMeta['description'];
        }
        if ($blog_meta_keywords == '') {
            $blog_meta_keywords = $globalMeta['keywords'];
        }
        if ($blog_meta_image == '') {
            $blog_meta_image = $globalMeta['image'];
        }
        $arrayMeta = array('title' => $blog_meta_title, 'description' => $blog_meta_description, 'keywords' => $blog_meta_keywords, 'sitemap' => $globalMeta['sitemap'], 'image' => $blog_meta_image);
        return $arrayMeta;
    }

    //STORE INFORMATION
    function get_store_information(){
        $this->db->from('store_information');
        return $this->db->get()->result();
    }

    //HOME SLIDERS
    function get_home_sliders(){
        $this->db->from('home_sliders');
        $this->db->where( array('is_deleted'=>0,'is_active'=>1));
        $this->db->order_by('sort_key', 'ASC');
        return $this->db->get()->result();
    }

    //BRANDS
    function get_brands() {
        $this->db->select('brands.*');
        $this->db->from('brands');
        $this->db->where( array('brands.is_deleted'=>0, 'brands.is_active'=>1));
        $this->db->order_by("brands.sort_key", "asc");
        
        $query = $this->db->get();
        $record_count = $query->num_rows();
        $records = $query->result();

        $arr_return = array('records' => $records, 'record_count' => $record_count);

        return $arr_return;
    }

    //NESTED CATEGORIES
    function get_nested_product_categories(){
        $this->db->select('product_categories.*, icons.icon_name');
        $this->db->from('product_categories');
        $this->db->join('icons', 'icons.icon_id = product_categories.icon_id', 'left outer');
        $this->db->where( array('product_categories.is_deleted'=>0, 'product_categories.product_category_parent_id'=>0));
        $this->db->order_by("product_categories.sort_key", "asc");
        
        $parent_category = $this->db->get();
        
        $product_categories = $parent_category->result();
        $i = 0;
        foreach($product_categories as $p_cat){
            $product_categories[$i]->sub = $this->product_sub_categories($p_cat->product_category_id);
            $i++;
        }
        return $product_categories;
    }

    function get_nested_product_subcategories_by_reference_id($product_category_reference_id) {

        $parent_category_id = 0;
        $product_category = $this->get_product_category_by_reference_id($product_category_reference_id);
        foreach ($product_category as $row) {
            $parent_category_id = $row->product_category_id;
        }

        $this->db->select('product_categories.*, icons.icon_name');
        $this->db->from('product_categories');
        $this->db->join('icons', 'icons.icon_id = product_categories.icon_id', 'left outer');
        $this->db->where( array('product_categories.is_deleted'=>0, 'product_categories.product_category_parent_id'=>$parent_category_id));
        $this->db->order_by("product_categories.sort_key", "asc");
        
        $parent_category = $this->db->get();
        
        $product_categories = $parent_category->result();
        $i = 0;
        foreach($product_categories as $p_cat){
            $product_categories[$i]->sub = $this->product_sub_categories($p_cat->product_category_id);
            $i++;
        }
        return $product_categories;
    }

    //HOME NESTED CATEGORIES
    function get_home_nested_product_categories(){
        $this->db->select('product_categories.*, icons.icon_name');
        $this->db->from('product_categories');
        $this->db->join('icons', 'icons.icon_id = product_categories.icon_id', 'left outer');
        $this->db->where( array('product_categories.is_deleted'=>0, 'product_categories.product_category_parent_id'=>0));
         $this->db->order_by("product_categories.sort_key", "asc");
        $this->db->limit(8);
        
        $parent_category = $this->db->get();
        
        $product_categories = $parent_category->result();
        $i = 0;
        foreach($product_categories as $p_cat){
            $product_categories[$i]->sub = $this->product_sub_categories($p_cat->product_category_id);
            $i++;
        }
        return $product_categories;
    }
    function product_sub_categories($id){
        $this->db->select('product_categories.*, icons.icon_name');
        $this->db->from('product_categories');
        $this->db->join('icons', 'icons.icon_id = product_categories.icon_id', 'left outer');
        $this->db->where( array('product_categories.is_deleted'=>0, 'product_categories.product_category_parent_id'=>$id));
        $this->db->order_by("product_categories.sort_key", "asc");

        $child_category = $this->db->get();
        $product_categories = $child_category->result();
        $i=0;
        foreach($product_categories as $p_cat){
            $product_categories[$i]->sub = $this->product_sub_categories($p_cat->product_category_id);
            $i++;
        }
        return $product_categories;       
    }

    function get_home_top_product_categories(){
        $this->db->select('htpc.*, pc.product_category_sku_code, pc.product_category_reference_id, pc.product_category_name, pc.cover_image, pc.thumb_image, i.icon_name');
        $this->db->from('home_top_product_categories htpc');
        $this->db->join('product_categories pc', 'pc.product_category_id = htpc.product_category_id');
        $this->db->join('icons i', 'i.icon_id = pc.icon_id', 'left outer');
        $this->db->where( array('htpc.is_deleted'=>0));
        $this->db->order_by('htpc.position', 'ASC');
        $this->db->limit(4);
        
        $main_categories = $this->db->get()->result();
        $i = 0;
        foreach($main_categories as $maincat){
            $main_categories[$i]->sub = $this->get_home_top_product_subcategories($maincat->home_top_product_category_id);
            $i++;
        }
        return $main_categories;        
    }

    function get_home_top_product_subcategories($home_top_product_category_id){
        $this->db->select('htps.*, pc.product_category_sku_code, pc.product_category_reference_id, pc.product_category_name, pc.cover_image, pc.thumb_image, i.icon_name');
        $this->db->from('home_top_product_subcategories htps');
        $this->db->join('product_categories pc', 'pc.product_category_id = htps.product_category_id');
        $this->db->join('icons i', 'i.icon_id = pc.icon_id', 'left outer');
        $this->db->where( array('htps.is_deleted'=>0, 'htps.home_top_product_category_id'=>$home_top_product_category_id));

        return $this->db->get()->result();   
    }

    //HOME FEATURED CATEGORIES
    function get_home_featured_product_categories(){
        $this->db->select('hfpc.*, pc.product_category_sku_code, pc.product_category_reference_id, pc.product_category_name, pc.cover_image, pc.thumb_image, i.icon_name');
        $this->db->from('home_featured_product_categories hfpc');
        $this->db->join('product_categories pc', 'pc.product_category_id = hfpc.product_category_id');
        $this->db->join('icons i', 'i.icon_id = pc.icon_id', 'left outer');
        $this->db->where( array('hfpc.is_deleted'=>0));
        $this->db->order_by('pc.sort_key', 'ASC');
        $this->db->limit(3);

        $query = $this->db->get();
        $record_count = $query->num_rows();
        
        $featured_categories = $query->result();
        $i = 0;
        foreach($featured_categories as $featuredcat){
            $featured_categories[$i]->prods = $this->get_home_featured_product_category_products($featuredcat->product_category_id);
            $i++;
        }

        $arr_return = array('records' => $featured_categories, 'record_count' => $record_count);

        return $arr_return;
    }

    function get_home_featured_product_category_products($product_category_id){

        $this->db->select("p.*, b.brand_name, u.unit_code, u.unit_name");
        $this->db->from('products p');
        $this->db->join('brands b', 'b.brand_id = p.brand_id', 'left outer');
        $this->db->join('units u', 'u.unit_id = p.unit_id', 'left outer');
        $this->db->join('product_product_categories ppca', 'ppca.product_id = p.product_id');

        $this->db->where( array('ppca.product_category_id'=>$product_category_id));
        $this->db->where( array('p.is_deleted'=>0, 'p.is_online'=>1, 'p.is_featured'=>1));

        $this->db->order_by('p.product_id', 'RANDOM');
        $this->db->limit(8); 

        return $this->db->get()->result();
    }

    function get_product_category_by_reference_id($product_category_reference_id) {
        $this->db->select('pc.*, i.icon_name');
        $this->db->from('product_categories pc');
        $this->db->join('icons i', 'i.icon_id = pc.icon_id', 'left outer');
        $this->db->where( array('pc.product_category_reference_id' => $product_category_reference_id));
        return $this->db->get()->result();   
    }

    function get_category_products_by_reference_id($product_category_reference_id) {
        $this->db->select("p.*, b.brand_name, u.unit_code, u.unit_name");
        $this->db->from('products p');
        $this->db->join('brands b', 'b.brand_id = p.brand_id', 'left outer');
        $this->db->join('units u', 'u.unit_id = p.unit_id', 'left outer');
        $this->db->join('product_product_categories ppca', 'ppca.product_id = p.product_id');
        $this->db->join('product_categories pc', 'pc.product_category_id = ppca.product_category_id');

        $this->db->where( array('pc.product_category_reference_id'=>$product_category_reference_id));
        $this->db->where( array('p.is_deleted'=>0, 'p.is_online'=>1));

        $query = $this->db->get();
        $record_count = $query->num_rows();
        $records = $query->result();

        $arr_return = array('records' => $records, 'record_count' => $record_count);

        return $arr_return;

    }


    //SHOP PRODUCTS
    function get_shop_products(){

        $this->db->select("p.*, b.brand_name, u.unit_code, u.unit_name");
        $this->db->from('products p');
        $this->db->join('brands b', 'b.brand_id = p.brand_id', 'left outer');
        $this->db->join('units u', 'u.unit_id = p.unit_id', 'left outer');

        $this->db->where( array('p.is_deleted'=>0, 'p.is_online'=>1));

        $query = $this->db->get();
        $record_count = $query->num_rows();
        $records = $query->result();

        $arr_return = array('records' => $records, 'record_count' => $record_count);

        return $arr_return;
    }

    function get_new_arrival_products(){
        $this->db->select("p.*, b.brand_name, u.unit_code, u.unit_name");
        $this->db->from('products p');
        $this->db->join('brands b', 'b.brand_id = p.brand_id', 'left outer');
        $this->db->join('units u', 'u.unit_id = p.unit_id', 'left outer');

        $this->db->where( array('p.is_deleted'=>0, 'p.is_online'=>1, 'p.is_new_arrival'=>1));

        $query = $this->db->get();
        $record_count = $query->num_rows();
        $records = $query->result();

        $arr_return = array('records' => $records, 'record_count' => $record_count);

        return $arr_return;
    }

    function get_featured_products(){
        $this->db->select("p.*, b.brand_name, u.unit_code, u.unit_name");
        $this->db->from('products p');
        $this->db->join('brands b', 'b.brand_id = p.brand_id', 'left outer');
        $this->db->join('units u', 'u.unit_id = p.unit_id', 'left outer');

        $this->db->where( array('p.is_deleted'=>0, 'p.is_online'=>1, 'p.is_featured'=>1));

        $query = $this->db->get();
        $record_count = $query->num_rows();
        $records = $query->result();

        $arr_return = array('records' => $records, 'record_count' => $record_count);

        return $arr_return;
    }

    function get_special_offer_products(){
        $this->db->select("p.*, b.brand_name, u.unit_code, u.unit_name");
        $this->db->from('products p');
        $this->db->join('brands b', 'b.brand_id = p.brand_id', 'left outer');
        $this->db->join('units u', 'u.unit_id = p.unit_id', 'left outer');

        $this->db->where( array('p.is_deleted'=>0, 'p.is_online'=>1, 'p.is_special_offer'=>1));

        $query = $this->db->get();
        $record_count = $query->num_rows();
        $records = $query->result();

        $arr_return = array('records' => $records, 'record_count' => $record_count);

        return $arr_return;
    }

    function get_deal_of_the_week_products() {
        $this->db->select("p.*, b.brand_name, u.unit_code, u.unit_name");
        $this->db->from('products p');
        $this->db->join('brands b', 'b.brand_id = p.brand_id', 'left outer');
        $this->db->join('units u', 'u.unit_id = p.unit_id', 'left outer');

        $this->db->where( array('p.is_deleted'=>0, 'p.is_online'=>1, 'p.is_deal_of_the_week'=>1));

        $query = $this->db->get();
        $record_count = $query->num_rows();
        $records = $query->result();

        $arr_return = array('records' => $records, 'record_count' => $record_count);

        return $arr_return;
    }

    function get_filter_shop_products(){

        $sort_by = $this->input->post('sort_by');
        $brand_id = $this->input->post('filter_shop_brand_id');
        $price_range_min = $this->input->post('filter_price_range_min');
        $price_range_max = $this->input->post('filter_price_range_max');
        $product_category_id = $this->input->post('filter_shop_product_category_id');

        $this->db->select("p.*, b.brand_name, u.unit_code, u.unit_name");
        $this->db->from('products p');
        $this->db->join('brands b', 'b.brand_id = p.brand_id', 'left outer');
        $this->db->join('units u', 'u.unit_id = p.unit_id', 'left outer');
        $this->db->join('product_product_categories ppca', 'ppca.product_id = p.product_id', 'left outer');

        $this->db->where( array('p.is_deleted'=>0, 'p.is_online'=>1));

        //BRAND
        if ($brand_id != '' && $brand_id != null){
            $i = 0;
            $this->db->group_start();
            foreach ($brand_id as $temp_id){
                 if ($i == 0) {
                     $this->db->where( array('p.brand_id' => $temp_id));
                 }else{
                    $this->db->or_where( array('p.brand_id' => $temp_id)); 
                }
                $i++;
            }  
            $this->db->group_end(); 
        }

        //PRICE RANGE
        if ($price_range_min != '' && $price_range_max != '') {
            $this->db->where('p.regular_price >= ', $price_range_min);
            $this->db->where('p.regular_price <= ', $price_range_max);
        }

        //PRODUCT CATEGORY
        if ($product_category_id != '' && $product_category_id != null) {
            $this->db->where('ppca.product_category_id', $product_category_id);
        }

        //SORT
        if ($sort_by != ''){
            switch ($sort_by) {
                case 'Newest':
                    $this->db->order_by('p.created_on', 'DESC');
                    break;
                case 'Price Ascending':
                    $this->db->order_by('p.regular_price', 'ASC');
                    break;
                case 'Price Descending':
                    $this->db->order_by('p.regular_price', 'DESC');
                    break;
                default:
                    # code...
                    break;
            }
        }
        $this->db->group_by('p.product_id');

        $query = $this->db->get();
        $record_count = $query->num_rows();
        $records = $query->result();

        $arr_return = array('records' => $records, 'record_count' => $record_count);

        return $arr_return;

    }

    //HOME PROMO BANNERS
    function get_home_promo_banners(){
        $this->db->from('home_promo_banners');
        $this->db->where( array('is_deleted'=>0, 'is_active'=>1));
        $this->db->order_by('home_promo_banner_id', 'RANDOM');
        $this->db->limit(2);
        return $this->db->get()->result();
    }

    //HOME TESTIMONIALS
    function get_home_testimonials(){
        $this->db->from('testimonials');
        $this->db->where( array('is_deleted'=>0, 'is_active'=>1));
        $this->db->order_by('testimonial_id', 'RANDOM');
        $this->db->limit(6);

        $query = $this->db->get();
        $record_count = $query->num_rows();        
        $records = $query->result();

        $arr_return = array('records' => $records, 'record_count' => $record_count);

        return $arr_return;


        //return $this->db->get()->result();
    }

    //HOME BLOG ARTICLES
    function get_home_blog_articles(){
        $this->db->select('blog.*');
        $this->db->from('blog');
        $this->db->where( array('blog.is_deleted'=>0, 'blog.is_published'=>1));
        $this->db->order_by('blog.blog_article_id', 'DESC');
        $this->db->limit(3);
        return $this->db->get()->result();
    }
    function get_num_home_blog_articles(){
        $this->db->select('blog.*');
        $this->db->from('blog');
        $this->db->where( array('blog.is_deleted'=>0, 'blog.is_published'=>1));
        $this->db->order_by('blog.blog_article_id', 'DESC');
        $this->db->limit(3);
        return $this->db->get()->result();
    }

    //PRODUCT BY ID
    function get_product_by_id($product_id){
        $this->db->select("p.*, b.brand_reference_id, b.brand_name, u.unit_code, u.unit_name");
        $this->db->from('products p');
        $this->db->join('brands b', 'b.brand_id = p.brand_id', 'left outer');
        $this->db->join('units u', 'u.unit_id = p.unit_id', 'left outer');
        $this->db->where( array('p.product_id'=>$product_id));
        return $this->db->get()->result();
    }
    function get_product_product_categories_by_id($product_id){
        $this->db->from('product_product_categories');
        $this->db->where( array('product_id'=>$product_id));
        return $this->db->get()->result();
    }
    function get_product_images_by_id($product_id){
        $this->db->from('product_images');
        $this->db->where( array('product_id'=>$product_id, 'is_deleted'=>0));
        return $this->db->get()->result();
    }
    function get_product_num_images_by_id($product_id){
        $this->db->from('product_images');
        $this->db->where( array('product_id'=>$product_id, 'is_deleted'=>0));
        return $this->db->count_all_results();
    }
    function get_product_product_sizes_by_id($product_id){
        $this->db->select("ps.*");
        $this->db->from('product_sizes ps');
        $this->db->join('product_product_sizes pps', 'pps.product_size_id = ps.product_size_id');
        $this->db->where( array('pps.product_id'=>$product_id, 'ps.is_deleted'=>0));
        return $this->db->get()->result();
    }
    function get_num_product_product_sizes_by_id($product_id){
        $this->db->select("ps.*");
        $this->db->from('product_sizes ps');
        $this->db->join('product_product_sizes pps', 'pps.product_size_id = ps.product_size_id');
        $this->db->where( array('pps.product_id'=>$product_id, 'ps.is_deleted'=>0));
        return $this->db->count_all_results();
    }
    function get_product_colors_by_id($product_id){
        $this->db->from('product_colors');
        $this->db->where( array('product_id'=>$product_id, 'is_deleted'=>0));
        return $this->db->get()->result();
    }
    function get_num_product_colors_by_id($product_id){
        $this->db->from('product_colors');
        $this->db->where( array('product_id'=>$product_id, 'is_deleted'=>0));
        return $this->db->count_all_results();
    }
    function get_product_attributes_by_id($product_id){

        $this->db->from('product_attributes');
        $this->db->where( array('product_id'=>$product_id, 'is_deleted'=>0));

        $product_attributes = $this->db->get()->result();

        $i = 0;
        foreach($product_attributes as $row){
            $product_attributes[$i]->values = $this->get_product_attribute_values($row->product_attribute_id);
            $i++;
        }
        return $product_attributes;

    }
    function get_num_product_attributes_by_id($product_id){
        $this->db->from('product_attributes');
        $this->db->where( array('product_id'=>$product_id, 'is_deleted'=>0));
        return $this->db->count_all_results();
    }

    //PRODUCT BY REFERENCE ID
    function get_product_by_reference_id($product_reference_id){
        $this->db->select("p.*, b.brand_reference_id, b.brand_name, u.unit_code, u.unit_name");
        $this->db->from('products p');
        $this->db->join('brands b', 'b.brand_id = p.brand_id', 'left outer');
        $this->db->join('units u', 'u.unit_id = p.unit_id', 'left outer');
        $this->db->where( array('p.product_reference_id'=>$product_reference_id));
        return $this->db->get()->result();
    }
    function get_product_product_categories_by_reference_id($product_reference_id){
        $this->db->select("pc.*");
        $this->db->from('product_categories pc');
        $this->db->join('product_product_categories ppc', 'ppc.product_category_id = pc.product_category_id');
        $this->db->join('products p', 'p.product_id = ppc.product_id');
        $this->db->where( array('p.product_reference_id'=>$product_reference_id, 'pc.is_deleted'=>0));
        return $this->db->get()->result();
    }
    function get_product_images_by_reference_id($product_reference_id){
        $this->db->select("pi.*");
        $this->db->from('product_images pi');
        $this->db->join('products p', 'p.product_id = pi.product_id');
        $this->db->where( array('p.product_reference_id'=>$product_reference_id, 'pi.is_deleted'=>0));
        return $this->db->get()->result();
    }
    function get_product_num_images_by_reference_id($product_reference_id){
        $this->db->select("pi.*");
        $this->db->from('product_images pi');
        $this->db->join('products p', 'p.product_id = pi.product_id');
        $this->db->where( array('p.product_reference_id'=>$product_reference_id, 'pi.is_deleted'=>0));
        return $this->db->count_all_results();
    }
    function get_product_product_sizes_by_reference_id($product_reference_id){
        $this->db->select("ps.*");
        $this->db->from('product_sizes ps');
        $this->db->join('product_product_sizes pps', 'pps.product_size_id = ps.product_size_id');
        $this->db->join('products p', 'p.product_id = pps.product_id');
        $this->db->where( array('p.product_reference_id'=>$product_reference_id, 'ps.is_deleted'=>0));
        return $this->db->get()->result();
    }
    function get_num_product_product_sizes_by_reference_id($product_reference_id){
        $this->db->select("ps.*");
        $this->db->from('product_sizes ps');
        $this->db->join('product_product_sizes pps', 'pps.product_size_id = ps.product_size_id');
        $this->db->join('products p', 'p.product_id = pps.product_id');
        $this->db->where( array('p.product_reference_id'=>$product_reference_id, 'ps.is_deleted'=>0));
        return $this->db->count_all_results();
    }
    function get_product_colors_by_reference_id($product_reference_id){
        $this->db->select("pc.*");
        $this->db->from('product_colors pc');
        $this->db->join('products p', 'p.product_id = pc.product_id');
        $this->db->where( array('p.product_reference_id'=>$product_reference_id, 'pc.is_deleted'=>0));
        return $this->db->get()->result();
    }
    function get_num_product_colors_by_reference_id($product_reference_id){
        $this->db->select("pc.*");
        $this->db->from('product_colors pc');
        $this->db->join('products p', 'p.product_id = pc.product_id');
        $this->db->where( array('p.product_reference_id'=>$product_reference_id, 'pc.is_deleted'=>0));
        return $this->db->count_all_results();
    }
    function get_product_attributes_by_reference_id($product_reference_id){
        $this->db->select("pa.*");
        $this->db->from('product_attributes pa');
        $this->db->join('products p', 'p.product_id = pa.product_id');
        $this->db->where( array('p.product_reference_id'=>$product_reference_id, 'pa.is_deleted'=>0));

        $product_attributes = $this->db->get()->result();

        $i = 0;
        foreach($product_attributes as $row){
            $product_attributes[$i]->values = $this->get_product_attribute_values($row->product_attribute_id);
            $i++;
        }
        return $product_attributes;
    }

    function get_product_attribute_values($product_attribute_id) {
        $this->db->from('product_attribute_values');
        $this->db->where( array('product_attribute_id' => $product_attribute_id, 'is_deleted'=>0));
        return $this->db->get()->result();
    }

    function get_num_product_attributes_by_reference_id($product_reference_id){
        $this->db->select("pa.*");
        $this->db->from('product_attributes pa');
        $this->db->join('products p', 'p.product_id = pa.product_id');
        $this->db->where( array('p.product_reference_id'=>$product_reference_id, 'pa.is_deleted'=>0));
        return $this->db->count_all_results();
    }

    function get_product_variations_by_reference_id($product_reference_id){
        $this->db->select("pv.*");
        $this->db->from('product_variations pv');
        $this->db->join('products p', 'p.product_id = pv.product_id');
        $this->db->where( array('p.product_reference_id'=>$product_reference_id, 'pv.is_deleted'=>0));

        // $this->db->where( array('product_id'=>$product_id, 'is_deleted'=>0));

        $product_variations = $this->db->get()->result();

        $i = 0;
        foreach($product_variations as $row){
            $product_variations[$i]->attributes = $this->get_product_variation_attributes($row->product_variation_id);
            $i++;
        }
        return $product_variations;
    }

    function get_num_product_variations_by_reference_id($product_reference_id){
        $this->db->select("pv.*");
        $this->db->from('product_variations pv');
        $this->db->join('products p', 'p.product_id = pv.product_id');
        $this->db->where( array('p.product_reference_id'=>$product_reference_id, 'pv.is_deleted'=>0));
        return $this->db->count_all_results();
    }

    function get_product_variations_by_id($product_id){
        $this->db->from('product_variations');
        $this->db->where( array('product_id' => $product_id, 'is_deleted'=>0));

        $product_variations = $this->db->get()->result();

        $i = 0;
        foreach($product_variations as $row){
            $product_variations[$i]->attributes = $this->get_product_variation_attributes($row->product_variation_id);
            $i++;
        }
        return $product_variations;
    }

    function get_num_product_variations_by_id($product_id){
        $this->db->from('product_variations');
        $this->db->where( array('product_id' => $product_id, 'is_deleted'=>0));
        return $this->db->count_all_results();
    }
    
    function get_product_variation($product_variation_id){
        $this->db->from('product_variations');
        $this->db->where( array('product_variation_id' => $product_variation_id));

        $product_variations = $this->db->get()->result();

        $i = 0;
        foreach($product_variations as $row){
            $product_variations[$i]->attributes = $this->get_product_variation_attributes($row->product_variation_id);
            $i++;
        }
        return $product_variations;
    }

    function get_product_variation_attributes($product_variation_id) {
        $this->db->select('pva.*, pa.product_attribute_name, pav.product_attribute_value');
        $this->db->from('product_variation_attributes pva');
        $this->db->join('product_attributes pa', 'pa.product_attribute_id = pva.product_attribute_id', 'left outer');
        $this->db->join('product_attribute_values pav', 'pav.product_attribute_value_id = pva.product_attribute_value_id', 'left outer');

        $this->db->where( array('pva.product_variation_id' => $product_variation_id, 'pva.is_deleted'=>0));
        return $this->db->get()->result();
    }

    function get_product_related_products_by_reference_id($product_reference_id) {
        $product_categories = $this->get_product_product_categories_by_reference_id($product_reference_id);

        $i = 0;
        $catArray = [];
        foreach($product_categories as $row){
            $catArray[$i] = $row->product_category_id;
            $i++;
        }

        $this->db->select("p.*, b.brand_name");
        $this->db->from('products p');
        $this->db->join('brands b', 'b.brand_id = p.brand_id', 'left outer');
        // $this->db->join('units u', 'u.unit_id = p.unit_id', 'left outer');
        $this->db->join('product_product_categories ppca', 'ppca.product_id = p.product_id');

        $this->db->where( array('p.is_deleted'=>0, 'p.is_online'=>1, 'p.product_reference_id !='=>$product_reference_id));
        //$this->db->where( array('ppca.product_category_id'=>$product_category_id));
        if(!empty($catArray)){
            $this->db->where_in('ppca.product_category_id', $catArray); 
        }
        $this->db->group_by('p.product_id');
        $this->db->order_by('p.product_id', 'RANDOM');
        $this->db->limit(8); 

        return $this->db->get()->result();
    }

    function get_product_reviews_by_reference_id($product_reference_id){
        $this->db->select("pr.*");
        $this->db->from('product_reviews pr');
        $this->db->join('products p', 'p.product_id = pr.product_id');
        $this->db->where( array('p.product_reference_id' => $product_reference_id));

        $query = $this->db->get();
        $records_count = $query->num_rows();
        $records = $query->result();

        $avg_review_value = 0;
        $sum_reviews = 0;
        foreach ($records as $row) {
            $sum_reviews = $sum_reviews + $row->review_value;
        }
        if ($records_count > 0){
            $avg_review_value = ($sum_reviews/$records_count);  
        }

        $arr_return = array('records' => $records,'records_count' => $records_count,'average_review' => $avg_review_value);

        return $arr_return;
    }

    //BLOG
    function get_blog_articles(){
        $this->db->select('blog.*');
        $this->db->from('blog');
        $this->db->where( array('blog.is_deleted'=>0, 'blog.is_published'=>1));
        $this->db->order_by('blog.blog_article_id', 'DESC');
        $query = $this->db->get();
        $record_count = $query->num_rows();        
        $records = $query->result();

        $arr_return = array('records' => $records, 'record_count' => $record_count);

        return $arr_return;
    }

    function get_blog_recent_articles(){
        $this->db->select('blog.*');
        $this->db->from('blog');
        $this->db->where( array('blog.is_deleted'=>0, 'blog.is_published'=>1));
        $this->db->order_by('blog.blog_article_id', 'DESC');
        $this->db->limit(5);
        $query = $this->db->get();
        $record_count = $query->num_rows();        
        $records = $query->result();

        $arr_return = array('records' => $records, 'record_count' => $record_count);

        return $arr_return;
    }

    function get_blog_categories(){
        $this->db->select('blog_categories.*');
        $this->db->from('blog_categories');
        $this->db->join('blog_article_categories', 'blog_article_categories.blog_category_id = blog_categories.blog_category_id');
        $this->db->join('blog', 'blog.blog_article_id = blog_article_categories.blog_article_id');

        $this->db->where( array('blog_categories.is_deleted'=>0, 'blog_article_categories.is_deleted'=>0, 'blog.is_published'=>1));

        $this->db->group_by('blog_categories.blog_category_id');
        return $this->db->get()->result();
    }

    function get_num_blog_categories(){
        $this->db->select('blog_categories.*');
        $this->db->from('blog_categories');
        $this->db->join('blog_article_categories', 'blog_article_categories.blog_category_id = blog_categories.blog_category_id');
        $this->db->join('blog', 'blog.blog_article_id = blog_article_categories.blog_article_id');

        $this->db->where( array('blog_categories.is_deleted'=>0, 'blog_article_categories.is_deleted'=>0, 'blog.is_published'=>1));

        $this->db->group_by('blog_categories.blog_category_id');
        return $this->db->count_all_results();
    }

    function get_blog_category($blog_category_reference_id){
        $this->db->from('blog_categories');
        $this->db->where( array('blog_category_reference_id'=>$blog_category_reference_id));
        return $this->db->get()->result();
    }

    function get_blog_article($blog_article_reference_id){
        $this->db->select('blog.*');
        $this->db->from('blog');
        $this->db->where( array('blog.blog_article_reference_id'=>$blog_article_reference_id));
        return $this->db->get()->result();
    }

    function get_blog_category_articles($blog_category_reference_id){
        $this->db->select('blog.*');
        $this->db->from('blog');
        $this->db->join('blog_article_categories', 'blog_article_categories.blog_article_id = blog.blog_article_id');
        $this->db->join('blog_categories', 'blog_categories.blog_category_id = blog_article_categories.blog_category_id');
        $this->db->where( array('blog.is_deleted'=>0,'blog.is_published'=>1,'blog_categories.blog_category_reference_id'=>$blog_category_reference_id));
        $this->db->order_by("blog.blog_article_id", "desc");
        $query = $this->db->get();
        $record_count = $query->num_rows();        
        $records = $query->result();

        $arr_return = array('records' => $records, 'record_count' => $record_count);

        return $arr_return;
    }

    function submit_review($data) {
        $insert = $this->db->insert('product_reviews', $data);
        if ($insert){
            $arr_return = array('res' => true,'dt' => 'Review submitted successfully. The review will appear on the website after moderation.');
        }else{
            $arr_return = array('res' => false,'dt' => 'Could not submit eview successfully. Please try again.');
        }
        return $arr_return;
    }


    //SEARCH 
    function get_ajax_search_results($search_keyword) {
        $this->db->select("p.*, b.brand_name, u.unit_code, u.unit_name");
        $this->db->from('products p');
        $this->db->join('brands b', 'b.brand_id = p.brand_id', 'left outer');
        $this->db->join('units u', 'u.unit_id = p.unit_id', 'left outer');
        $this->db->where( array('p.is_deleted'=>0, 'p.is_online'=>1));

        $this->db->like('p.product_name',$search_keyword);
        $this->db->limit(4);

        $query = $this->db->get();
        $record_count = $query->num_rows();
        $records = $query->result();

        $arr_return = array('records' => $records, 'record_count' => $record_count);

        return $arr_return;
    }

    function get_num_ajax_search_results($search_keyword) {
        $this->db->select("p.*, b.brand_name, u.unit_code, u.unit_name");
        $this->db->from('products p');
        $this->db->join('brands b', 'b.brand_id = p.brand_id', 'left outer');
        $this->db->join('units u', 'u.unit_id = p.unit_id', 'left outer');
        $this->db->where( array('p.is_deleted'=>0, 'p.is_online'=>1));

        $this->db->like('p.product_name',$search_keyword);
        return $this->db->count_all_results();
    }

    function get_search_results($search_keyword) {
        $this->db->select("p.*, b.brand_name, u.unit_code, u.unit_name");
        $this->db->from('products p');
        $this->db->join('brands b', 'b.brand_id = p.brand_id', 'left outer');
        $this->db->join('units u', 'u.unit_id = p.unit_id', 'left outer');
        $this->db->where( array('p.is_deleted'=>0, 'p.is_online'=>1));

        $this->db->like('p.product_name',$search_keyword);

        $query = $this->db->get();
        $record_count = $query->num_rows();
        $records = $query->result();

        $arr_return = array('records' => $records, 'record_count' => $record_count);

        return $arr_return;
    }

    function add_to_favorites(){
        $customer_id = $this->session->userdata('customer_id');
        $product_id = $this->input->post('product_id');

        $this->db->from('favorite_products');
        $this->db->where( array('product_id' => $product_id, 'customer_id' => $customer_id, 'is_deleted' => 0));
        $query = $this->db->get();

        if ($query->num_rows() > 0){
            $arr_return = array('res' => true,'dt' => 'Product favorited Successfully');
        }else{
            $data = array(
                'product_id' => $product_id,
                'customer_id' => $customer_id
            );
            $insert = $this->db->insert('favorite_products', $data);
            if ($insert){
                $arr_return = array('res' => true,'dt' => 'Product Favorited successfully.');
            }else{
                $arr_return = array('res' => false,'dt' => 'Could not favorite product successfully. Please try again.');
            }
        }
        return $arr_return;
    }

    //COMPARE PRODUCTS
    function get_compare_products(){
        $compare_product_id =  $this->session->userdata('compare_product_id');
        $comp_product_id = array();
        foreach ($compare_product_id as $var) {
            array_push($comp_product_id,$var['0']);
        }

        $this->db->select("p.*, b.brand_name, u.unit_code, u.unit_name");
        $this->db->from('products p');
        $this->db->join('brands b', 'b.brand_id = p.brand_id', 'left outer');
        $this->db->join('units u', 'u.unit_id = p.unit_id', 'left outer');

        $this->db->where( array('p.is_deleted'=>0, 'p.is_online'=>1));
        $this->db->where_in('p.product_id', $comp_product_id);

        return $this->db->get()->result();
    }

    //ABOUT US
    function get_about_us(){
        $this->db->from('about_us');
        return $this->db->get()->result();
    }

    //TERMS AND CONDITIONS
    function get_terms_and_conditions(){
        $this->db->from('terms_and_conditions');
        return $this->db->get()->result();
    }

    //PRIVACY POLICY
    function get_privacy_policy(){
        $this->db->from('privacy_policy');
        return $this->db->get()->result();
    }

    //RETURN POLICY
    function get_return_policy(){
        $this->db->from('return_policy');
        return $this->db->get()->result();
    }

    //HOW TO SHOP
    function get_how_to_shop(){
        $this->db->from('how_to_shop');
        return $this->db->get()->result();
    }

    //FAQS
    function get_faqs(){
        $this->db->from('faqs');
        $this->db->where( array('is_deleted'=>0));
        $this->db->order_by('faq_id', 'ASC');
        return $this->db->get()->result();
    }

    function submit_contact(){
        $contact_name = $this->input->post('contact_name');
        $contact_email = $this->input->post('contact_email');
        $contact_subject = $this->input->post('contact_subject');
        $contact_message = $this->input->post('contact_message');

        $mail_host = '';
        $mail_port = 465;
        $mail_username = '';
        $mail_password = '';
        $mail_sender = '';
        $mail_sender_name = '';
        $mail_use_ssl = 1;

        $defaul_email_address = $this->email_accounts_model->get_default_email_account();
        foreach ($defaul_email_address as $row) {
            $mail_host = $row->mail_server_name;
            $mail_port = $row->mail_server_port;
            $mail_username = $row->user_name;
            $mail_password = $row->password;
            $mail_sender = $row->sender_email_address;
            $mail_sender_name = $row->sender_name;
            $mail_use_ssl = $row->use_ssl;
        }

        $recipient_email_address = '';
        $cc_email_address = '';
        $bcc_email_address = '';

        $email_notification_settings = $this->email_notification_settings_model->get_email_notification_settings();
        foreach ($email_notification_settings as $row) {
            $recipient_email_address = $row->email_address;
            $cc_email_address = $row->cc_email_address;
            $bcc_email_address = $row->bcc_email_address;
        }

        $mail          = new PHPMailer();
        $mail->IsSMTP();
        if ($mail_use_ssl == 1){
            $mail->SMTPSecure = 'ssl';
            $mail->SMTPAuth   = true;
        }
        $mail->Host       = $mail_host;
        $mail->Port       = $mail_port;
        $mail->Username   = $mail_username;
        $mail->Password   = $mail_password;
        
        $mail->SetFrom($mail_sender, $mail_sender_name);
        $email_to = $recipient_email_address;
                 
        $mail->Subject = 'Contact Form Feedback : ' . $contact_subject;

        $email_message = "<b>Name: </b> " . $contact_name . "<br /><br />"; 
        $email_message .= "<b>Email:</b> " . $contact_email . "<br /><br />"; 
        
        $email_message .= "<b>Message:</b><br /><br />"; 
        $email_message .= $contact_message . "<br /><br />";

        $message = file_get_contents(base_url().'email_temp/emheader');
        $message .= file_get_contents(base_url().'email_temp/embody');
        $message .= file_get_contents(base_url().'email_temp/emfooter');
        $logo = base_url().'assets/fe/img/logo.png';
                    
        $replacements = array(
            '({logo})' => $logo, 
            '({message_subject})' => 'Contact Form Feedback', 
            '({message_body})' => nl2br( stripslashes( $email_message ) )
        );
        $message = preg_replace( array_keys( $replacements ), array_values( $replacements ), $message );
                
        $plaintext = $message;
        $plaintext = strip_tags( stripslashes( $plaintext ), '<p><br><h2><h3><h1><h4>' );
        $plaintext = str_replace( array( '<p>', '<br />', '<br>', '<h1>', '<h2>', '<h3>', '<h4>' ), PHP_EOL, $plaintext );
        $plaintext = str_replace( array( '</p>', '</h1>', '</h2>', '</h3>', '</h4>' ), '', $plaintext );
        $plaintext = html_entity_decode( stripslashes( $plaintext ) );
                
                    
        $mail->MsgHTML( stripslashes( $message ) ); 
                    
        $mail->AltBody = $plaintext;
        $mail->AddAddress($email_to, "");
        if ($cc_email_address != '') {
            $mail->addCC($cc_email_address, "");
        }
        if ($bcc_email_address != '') {
            $mail->addBCC($bcc_email_address, "");
        }
                
        if( !$mail->Send() ){
            $arr_return = array('res' => FALSE,'dt' => 'Message could not be sent. ' . $mail->ErrorInfo);
        }
        else{
            $arr_return = array('res' => TRUE,'dt' => 'Your message was sent successfully.');
        }
        return $arr_return;

    }

    


}