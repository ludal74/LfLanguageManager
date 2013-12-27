<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace LfLanguageManager;


use Zend\Mvc\MvcEvent;


class Module
{
    private $configuration;
    private $current_language;
    
    
    //available languages for translation
    private $languages = array('fr');
    
    //set a default language ( will be override by config file value !! )
    private $default_language = 'fr';
    
    //set a default route language separator ( will be override by config file value !! )
    private $default_route_language_separator = ""; 
    
    //set a default route language parameter ( will be override by config file value !! )
    private $default_route_language_parameter = "";
    
    public function onBootstrap(MvcEvent $mvcEvent)
    {
        $this->initSettings( $mvcEvent );
        $this->injectAssets( $mvcEvent );
        $this->manageLanguageLocaleSession( $mvcEvent );
        $this->redirectDefaultLocaleLanguage( $mvcEvent );
        $this->injectLanguageSelectorViewInlayout( $mvcEvent );
    }
    
    private function injectLanguageSelectorViewInlayout( $mvcEvent )
    {
        //controllers list to attach on dispatch
        $controllersList =  array(
            'Zend\Mvc\Controller\AbstractActionController',
        );
        
        $mvcEvent->getApplication()
        ->getEventManager()
        ->getSharedManager()
        ->attach( $controllersList, MvcEvent::EVENT_DISPATCH, function ($event)
        {
            $controller = $event->getTarget();
            $controllerClass = get_class($controller);
            
            if( $controllerClass != "LfLanguageManager\Controller\IndexController" )
            {
                $forwardPlugin = $controller->forward();
                $laguageSelectorView = $forwardPlugin->dispatch('LfLanguageManagerIndexController', array('action' => 'index'));
                $controller->layout()->addChild( $laguageSelectorView , "languageSelector"  );
            }
        }); 
    }
    
    /**
     * Get module configuration file parameters ( languages codes availables and default language )
     * @param MvcEvent $mvcEvent
     */
    private function initSettings(MvcEvent $mvcEvent)
    {
        $config =  $mvcEvent->getApplication()->getServiceManager()->get('Config');
        $services = $mvcEvent->getApplication()->getServiceManager();
 
        
        $this->configuration = $config;

        $this->languages = $config['languages_settings']['languages_list'];

        if( $config['languages_settings']['default_language_code'] != Null && $config['languages_settings']['default_language_code'] != '' )
        {
            $this->default_language = $config['languages_settings']['default_language_code'];
        }
 
        if( $config['languages_settings']['default_route_language_separator'] != Null && $config['languages_settings']['default_route_language_separator'] != '' )
        {
            $this->default_route_language_separator = $config['languages_settings']['default_route_language_separator'];
        }
        
        if( $config['languages_settings']['default_route_language_parameter'] != Null && $config['languages_settings']['default_route_language_parameter'] != '' )
        {
            $this->default_route_language_parameter = $config['languages_settings']['default_route_language_parameter'];
        }
        
        
        $languageService = $services->get('LfLanguageManagerService');
        $languageService->setLanguagesList( $this->languages );
        $languageService->setDefaultRoutelanguageSeparator( $this->default_route_language_separator );
        $languageService->setDefaultRoutelanguageParameter( $this->default_route_language_parameter );
    }
    
    /**
     *
     * @return string
     */
    private function getLocaleLanguage ()
    {     
        $lang = explode(',',$_SERVER['HTTP_ACCEPT_LANGUAGE']);
        $lang = strtolower(substr(chop($lang[0]),0,2));
 
        $languageExist = false;

        foreach ($this->languages as $language => $value ) 
        {
            if ($value == $lang) 
            {
                $languageExist = true;
                break;
            }
        }
    
        if ( $languageExist == false)
        {
           $lang = $this->default_language; 
        }

        return $lang;  
    }
    

    /**
     * Assets management
     * @param MvcEvent $mvcEvent
     */
    private function injectAssets(MvcEvent $mvcEvent)
    {
        // -----------------------------------------------------------------
        // ASSETS MANAGEMENT
        // -----------------------------------------------------------------
        
        $basePath = $mvcEvent->getApplication()->getRequest()->getBasePath();
        
    }
    
    /**
     * Language locale management
     * @param MvcEvent $mvcEvent
     */
    private function manageLanguageLocaleSession(MvcEvent $mvcEvent)
    {
        $eventManager = $mvcEvent->getApplication()->getEventManager();
        $eventManager->attach( 'route',

        //set language params
        function  (MvcEvent $e)
        {      
            //$lang = $e->getRouteMatch()->getParam('lang');
            $routename = $e->getRouteMatch()->getMatchedRouteName();
            $lastUnderscore = strrpos( $routename, $this->default_route_language_separator );
            
            if( $lastUnderscore )
            {
            	$routename_language = substr( $routename, $lastUnderscore + 1, $lastUnderscore + 2 );
            }
            else
            {
            	$routename_language = $this->getLocaleLanguage();
            }
            
            $lang = $routename_language;
            $services = $e->getApplication()->getServiceManager();

            $translator = $services->get('translator');
            $lang = str_replace('-', '_', $lang);
            $translator->setLocale($lang);
            
            $this->current_language = $lang;
            
            $languageService = $services->get('LfLanguageManagerService');
            $languageService->setlanguage( $this->current_language );
           
        }, 1);

    }
    
    /**
     * Redirect to default locale language URL
     * @return unknown
     */
    private function redirectDefaultLocaleLanguage(MvcEvent $mvcEvent)
    {
        $basePath = $mvcEvent->getApplication()->getRequest()->getBasePath();
        $config =  $mvcEvent->getApplication()->getServiceManager()->get('Config');
        
        //if no language params in url redirection with default language route
        if ($_SERVER["REQUEST_URI"] == '/' || $_SERVER["REQUEST_URI"] == $basePath || $_SERVER["REQUEST_URI"] == $basePath.'/')
        {
            $defaultRoute           =  $config["languages_settings"]["default_page_route"];
            $defaultSeparator       =  $config["languages_settings"]["default_route_language_separator"];
            $languageRouteParameter =  $config["languages_settings"]["default_route_language_parameter"];
         
            $url = $mvcEvent->getRouter()->assemble(array( $languageRouteParameter => $this->getLocaleLanguage() ), array('name' => $defaultRoute.$defaultSeparator.$this->getLocaleLanguage()));
            
            $services = $mvcEvent->getApplication()->getServiceManager();
            $languageService = $services->get('LfLanguageManagerService');
            $languageService->setDefaultRoute( $defaultRoute.$defaultSeparator.$this->getLocaleLanguage() );
            
            $response = $mvcEvent->getResponse();
            
        
            $response->getHeaders()->addHeaderLine('Location', $basePath.$url);
            $response->setStatusCode(302);
            $response->sendHeaders();
            exit;
        }  
    }
    
    /**
     * 
     */
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    /**
     * 
     * @return multitype:multitype:multitype:string
     */
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
    
    
    public function getServiceConfig() {
    	return array(
    			'factories' => array(
    					'LfLanguageManagerService' => function ($sm) {
    						return new \LfLanguageManager\Service\LfLanguageManagerService();
    					},
    			),
    	);
    }
    
    /**********************/
    // Languages codes with codes like fr-FR --> to be done later
    /****************************************************************/
    
    /*
     function getDefaultLanguage()
     {
    if (isset($_SERVER["HTTP_ACCEPT_LANGUAGE"]))
        return $this->parseDefaultLanguage($_SERVER["HTTP_ACCEPT_LANGUAGE"]);
    else
        return $this->parseDefaultLanguage(NULL);
    }
    
    function parseDefaultLanguage($http_accept, $deflang = "en")
    {
    if(isset($http_accept) && strlen($http_accept) > 1)  {
    # Split possible languages into array
    $x = explode(",",$http_accept);
    foreach ($x as $val) {
    #check for q-value and create associative array. No q-value means 1 by rule
    if(preg_match("/(.*);q=([0-1]{0,1}\.\d{0,4})/i",$val,$matches))
        $lang[$matches[1]] = (float)$matches[2];
    else
        $lang[$val] = 1.0;
    }
    
    #return default language (highest q-value)
    $qval = 0.0;
    foreach ($lang as $key => $value) {
    if ($value > $qval) {
    $qval = (float)$value;
    $deflang = $key;
    }
    }
    }
    return strtolower($deflang);
    }
    */
}
