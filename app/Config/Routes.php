<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Auth::login');

$routes->group('users', ['namespace' => 'App\Controllers'], function ($routes) {
	$routes->get('/', 'Users::index');
	$routes->get('update', 'Users::update');
	$routes->add('create', 'Users::create');
	$routes->add('stored/(:num)', 'Users::stored/$1');
	$routes->get('upload-user', 'Users::upload_user');
	$routes->add('stored-user', 'Users::stored_user');
	$routes->get('getpart', 'Users::getpart');
});

$routes->group('invoicing', ['namespace' => 'App\Controllers'], function ($routes) {
	$routes->get('gr', 'Invoicing::gr_index');
	$routes->add('gr', 'Invoicing::gr_index');
	$routes->add('real', 'Invoicing::gr_real');
	$routes->get('registration-dropbox', 'Invoicing::reistration_dropbox');
	$routes->get('status/(:any)', 'Invoicing::status_update/$1');
	$routes->get('approve/(:any)', 'Invoicing::gr_approve/$1');
	$routes->get('printgr/(:any)', 'Invoicing::print_gr/$1');
	$routes->get('table', 'Invoicing::table_res');
	$routes->get('verify-transaction', 'Invoicing::verify_transaction');
	$routes->add('verify', 'Invoicing::jsonverify');
});

$routes->group('dashboard', ['namespace' => 'App\Controllers'], function ($routes) {
	$routes->get('/', 'Dashboard::sup_index');
	$routes->add('real', 'Dashboard::dash_real');
	$routes->add('value', 'Dashboard::card_value');
});

// $routes->group('auth', ['namespace' => 'IonAuth\Controllers'], function ($routes) {
$routes->group('auth', ['namespace' => 'App\Controllers'], function ($routes) {
	$routes->add('login', 'Auth::login');
	$routes->get('logout', 'Auth::logout');
	$routes->add('forgot_password', 'Auth::forgot_password');
	$routes->get('/', 'Auth::index');
	$routes->add('create_user', 'Auth::create_user');
	$routes->add('edit_user/(:num)', 'Auth::edit_user/$1');
	$routes->add('create_group', 'Auth::create_group');
	$routes->get('activate/(:num)', 'Auth::activate/$1');
	$routes->get('activate/(:num)/(:hash)', 'Auth::activate/$1/$2');
	$routes->add('deactivate/(:num)', 'Auth::deactivate/$1');
	$routes->get('reset_password/(:hash)', 'Auth::reset_password/$1');
	$routes->post('reset_password/(:hash)', 'Auth::reset_password/$1');
});


$routes->get('excel/upload', 'Temp::upload');
$routes->get('temp', 'Temp::temp');
$routes->get('temp/getpart', 'Temp::getPart');
$routes->get('temp/uploadsap', 'Temp::postDataToSap');
$routes->add('users/inspect/(:num)', 'Users::temp/$1');
$routes->get('qrcode', 'QRCode::generateQRCode');
$routes->get('pdf', 'Pdf::index');
