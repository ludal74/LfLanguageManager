<?php
namespace LanguageSelector;
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

return array(
   
  
    'languages_settings' => array(  

         'languages_list' =>array(
            'français'  =>  'fr', 
            'english'   =>  'en',
            'italiano'  =>  'it',
            'deutch'    =>  'de',
         ),
         
        'default_language_code'             => 'fr',
        'default_page_route'                => "home",
        'default_route_language_parameter'  => "lang",
        'default_route_language_separator'  => "_",
        'default_language_view_template'    => "lf-language-manager/index/index",
    ),
  
);

