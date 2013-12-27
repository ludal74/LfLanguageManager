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
   
    'service_manager' => array(
        'factories' => array(
        		'translator' => 'Zend\I18n\Translator\TranslatorServiceFactory',
        ),
        
        'services' => array(
        		'session' => new \Zend\Session\Container('locale'),
        ),
    ),
    
    'translator' => array(
    		'locale' => 'fr',
    		'translation_file_patterns' => array(
    				array(
    						'type'     => 'gettext',
    						'base_dir' => __DIR__ . '/../language',
    						'pattern'  => '%s.mo',
    				        'text_domain' => __NAMESPACE__,
    				),
    		),
    ),
    
    'controllers' => array(
    		'invokables' => array(
    				'LfLanguageManagerIndexController'   => 'LfLanguageManager\Controller\IndexController',
    		),
    ),
    
    //--------------------------
    //VIEW HELPER CONFIGURATION
    //--------------------------
    'view_helpers' => array(
    		'invokables' => array(
    				'LanguageHelper'    => 'LfLanguageManager\View\Helper\LanguageHelper',
    		)
    ),
    
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'lf-language-manager/index/index' => __DIR__ . '/../view/lf-language-manager/index/index.phtml',

        ),
    ),
);

