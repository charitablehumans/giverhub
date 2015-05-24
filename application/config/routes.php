<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
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
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/

$route['default_controller'] = "home";
$route['404_override'] = 'home/page_not_found';

$route['giving-pot/about'] = 'giving_pot/about';
$route['giving-pot/create'] = 'giving_pot/create';
$route['giving-pot/save-draft/(:num)'] = 'giving_pot/saveDraft/$1';
$route['giving-pot/edit/(:num)'] = 'giving_pot/edit/$1';
$route['giving-pot/publish/(:num)'] = 'giving_pot/publish/$1';
$route['giving-pot/dashboard/(:num)'] = 'giving_pot/dashboard/$1';
$route['giving-pot/add-recipients/(:num)'] = 'giving_pot/add_recipients/$1';
$route['giving-pot/set-payment-method/(:num)'] = 'giving_pot/set_payment_method/$1';

$route['search/nonprofits'] = 'home/search/nonprofits';
$route['search/nonprofits'] = 'home/search/nonprofits';
$route['search'] = 'home/search';

$route['admin'] = "admin";

$route['post/external_url'] = 'post/external_url';
$route['post/(:any)'] = 'post/index/$1';

$route['bet/(:num)'] = 'bet/index/$1';

$route['charity/(:num)'] = 'charity/index/$1';

$route['donate/(:num)'] = "donate/index/$1";

$route['bet-a-friend'] = 'home/bet_a_friend';

$route['giver-cards/(:any)/(:any)'] = 'giver_cards/$1/$2';
$route['giver-cards/(:any)'] = 'giver_cards/$1';
$route['giver-cards'] = 'giver_cards';


$route['a-z/nonprofits/(:any)/(:num)'] = 'sitemap/az/nonprofits/$1/$2';
$route['a-z/nonprofits/(:any)'] = 'sitemap/az/nonprofits/$1/1';
$route['a-z/nonprofits'] = 'sitemap/az/nonprofits';
$route['a-z/petitions/(:any)/(:num)'] = 'sitemap/az/petitions/$1/$2';
$route['a-z/petitions/(:any)'] = 'sitemap/az/petitions/$1/1';
$route['a-z/petitions'] = 'sitemap/az/petitions';
$route['a-z'] = 'sitemap/az';

$route['nonprofits/(:any)/messages'] = 'nonprofits/messages/$1';
$route['nonprofits/(:any)/manage_keywords'] = 'nonprofits/manage_keywords/$1';
$route['nonprofits/(:any)/followers'] = 'nonprofits/followers/$1';
$route['nonprofits/(:any)/reviews'] = 'nonprofits/reviews/$1';
$route['nonprofits/(:any)/missions'] = 'nonprofits/missions/$1';
$route['nonprofits/(:any)'] = 'nonprofits/index/$1';

$route['petitions/sign'] = "petitions/sign";
$route['petitions/sign_modal_body'] = "petitions/sign_modal_body";
$route['petitions/feature'] = "petitions/feature";
$route['petitions/removal_request'] = "petitions/removal_request";
$route['petitions/test'] = "petitions/test";
$route['petitions/fb_share'] = "petitions/fb_share";
$route['petitions/(:any)/news/(:num)'] = "petitions/news/$1/$2";
$route['petitions/(:any)/news'] = "petitions/news/$1";

$route['petitions/(:any)/reasons/(:num)'] = "petitions/reasons/$1/$2";
$route['petitions/(:any)/reasons'] = "petitions/reasons/$1";

$route['petitions/(:any)/signatures/(:num)'] = "petitions/signatures/$1/$2";
$route['petitions/(:any)/signatures'] = "petitions/signatures/$1";

$route['petitions/(:any)'] = "petitions/index/$1";

$route['g-petitions/(:any)/reasons/(:num)'] = "giverhub_petition/reasons/$1/$2";
$route['g-petitions/(:any)/reasons'] = "giverhub_petition/reasons/$1";

$route['g-petitions/(:any)/signatures/(:num)'] = "giverhub_petition/signatures/$1/$2";
$route['g-petitions/(:any)/signatures'] = "giverhub_petition/signatures/$1";

$route['g-petitions/(:any)/news/(:num)'] = "giverhub_petition/news/$1/$2";
$route['g-petitions/(:any)/news'] = "giverhub_petition/news/$1";

$route['g-petitions/(:any)'] = "giverhub_petition/index/$1";


$route['members/petition-history'] = "members/petition_history";

$route['member/(:any)'] = "members/index/$1";

$route['challenge/(:num)'] = "challenge/index/$1";

$route['volunteering-opportunities/(:any)/reviews'] = "volunteering_opportunity/reviews/$1";
$route['volunteering-opportunities/(:any)'] = "volunteering_opportunity/index/$1";

$route['page/(:any)'] = "page/index/$1";