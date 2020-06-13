<?php
/**
 * Created by PhpStorm.
 * User: smendes
 * Date: 02-05-2016
 * Time: 11:18
 */
use ArmoredCore\Facades\Router;

/****************************************************************************
 *  URLEncoder/HTTPRouter Routing Rules
 *  Use convention: controllerName@methodActionName
 ****************************************************************************/

//Router::get('/',			'HomeController/index');
//Router::get('home/',		'HomeController/index');
//Router::get('home/index',	'HomeController/index');
//Router::get('home/start',	'HomeController/start');

//Rotas das páginas do site
Router::get('/', 'SiteController/Home');
Router::get('stbox/', 'SiteController/Home');
Router::get('stbox/rules', 'SiteController/Rules');
//Router::get('stbox/backoffice', 'UserController/index');
Router::get('stbox/matches', 'SiteController/Matches');
Router::get('stbox/login', 'SiteController/Login');
Router::get('stbox/top10', 'SiteController/Top10');
Router::get('stbox/register', 'SiteController/Register');
Router::get('stbox/home', 'SiteController/Home');
Router::get('stbox/profile','SiteController/Profile');
Router::get('stbox/error', 'SiteController/Erro');
Router::get('stbox/game', 'SiteController/Game');

//Router::post('stbox/game', 'GameController/mostrarDado');
Router::resource('game', 'GameController');


Router::resource('user', 'UserController');

Router::resource('admin', 'AdminController');

/************** End of URLEncoder Routing Rules ************************************/