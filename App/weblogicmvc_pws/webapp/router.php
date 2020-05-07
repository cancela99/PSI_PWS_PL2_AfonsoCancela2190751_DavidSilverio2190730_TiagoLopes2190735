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

Router::get('rules/index', 'RulesController/index');
Router::get('rules/', 'RulesController/index');

Router::get('login/', 'RulesController/loginPage');
Router::get('login/loginPage', 'RulesController/loginPage');
Router::get('top10/', 'RulesController/top10page');
Router::get('top10/top10', 'RulesController/top10page');

Router::get('register/', 'RulesController/registerpage');
Router::get('home/', 'RulesController/homepage');




/************** End of URLEncoder Routing Rules ************************************/