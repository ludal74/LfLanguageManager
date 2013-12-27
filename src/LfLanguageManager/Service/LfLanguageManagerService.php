<?php

namespace LfLanguageManager\Service;

class LfLanguageManagerService
{
    protected $language;
    protected $defaultLanguage;
    protected $defaultRouteLanguageSeparator;
    protected $defaultRouteLanguageParameter;
    protected $defaultRoute;

	/**
	 * Constructor
	*/
	function __construct()
	{    
        
	}
	
	/**
	 * Set current language
	 */
	public function setLanguage( $language )
	{
		$this->language = $language;
	}
	
	/**
	 * Set current language
	 */
	public function setDefaultLanguage( $language )
	{
		$this->defaultLanguage = $language;
	}
	
	/**
	 * Set language list
	 */
	public function setLanguagesList( $languagesList )
	{
		$this->languageList = $languagesList;
	}
	
	public function setDefaultRoutelanguageSeparator( $separator )
	{
	    $this->defaultRouteLanguageSeparator = $separator;
	}
	
	public function setDefaultRoutelanguageParameter( $parameter )
	{
	    $this->defaultRouteLanguageParameter = $parameter;
	}
	
	public function setDefaultRoute( $parameter )
	{
		$this->defaultRoute = $parameter;
	}
	
	/**
	 * Get current language
	 */
	public function getLanguage()
	{
	   return $this->language;
	}

	public function getDefaultRoutelanguageSeparator()
	{
		return $this->defaultRouteLanguageSeparator;
	}
	
	public function getDefaultRoutelanguageParameter()
	{
		return $this->defaultRouteLanguageParameter;
	}
	
	public function getDefaultRoute()
	{
		return $this->defaultRoute;
	}

	public function getDefaultLanguage()
	{
		return $this->defaultLanguage;
	}
	
	/**
	 * Set language list
	 */
	public function getLanguagesList()
	{
		return $this->languageList;
	}
}