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

Router::get('/',			'HomeController/index');
Router::get('home/',		'HomeController/index');
Router::get('home/index',	'HomeController/index');
Router::get('home/start',	'HomeController/start');

//Rotas das páginas do site
//Router::get('rules/index', 'SiteController/index');
Router::get('rules/', 'SiteController/indexRules');

Router::get('backoffice/', 'SiteController/indexBackoffice');

Router::get('matches/', 'SiteController/indexMatches');

Router::get('login/', 'SiteController/indexLoginPage');
//Router::get('login/loginPage', 'SiteController/loginPage');

Router::get('top10/', 'SiteController/indexTop10');
//Router::get('top10/top10', 'SiteController/top10page');

Router::get('register/', 'SiteController/indexRegisterpage');

Router::get('home/', 'SiteController/indexHomePage');

Router::get('game/', 'SiteController/indexGamePage');


/************** End of URLEncoder Routing Rules ************************************/