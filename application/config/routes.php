<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'home';

$route['shop'] = 'home/shop';
$route['product/(:any)'] = 'home/product/$1';
$route['category/(:any)'] = 'home/category/$1';

$route['new-arrivals'] = 'home/new_arrivals';
$route['featured'] = 'home/featured';
$route['special-offers'] = 'home/special_offers';
$route['deal-of-the-week'] = 'home/deal_of_the_week';

$route['compare'] = 'home/compare';
$route['about-us'] = 'home/about_us';
$route['terms-and-conditions'] = 'home/terms_and_conditions';
$route['privacy-policy'] = 'home/privacy_policy';
$route['return-policy'] = 'home/return_policy';
$route['how-to-shop'] = 'home/how_to_shop';
$route['faqs'] = 'home/faqs';
$route['contact-us'] = 'home/contact_us';

$route['blog'] = 'home/blog';
$route['blog/category/(:any)'] = 'home/blog_category/$1';
$route['blog/(:any)'] = 'home/blog_article/$1';


$route['affiliates/refer/invalid'] = 'affiliates/invalid_referral';

$route['affiliates/account/referrals'] = 'affiliates/account_referrals';
$route['affiliates/account/withdrawals'] = 'affiliates/account_withdrawals';
$route['affiliates/account/clicks'] = 'affiliates/account_clicks';
$route['affiliates/account/pswdchange'] = 'affiliates/change_password';

$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

//BACK END
$route['be'] = 'be/dashboard';

//SHOP
$route['pos'] = 'pos/dashboard';

