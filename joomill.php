<?php
/**
 *  package: Joomill-Styling
 *  copyright: Copyright (c) 2023. Jeroen Moolenschot | Joomill
 *  license: GNU General Public License version 3 or later
 *  link: https://www.joomill.nl
 */

// No direct access to this file
defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\CMS\Uri\Uri;

class plgSystemJoomill extends CMSPlugin
{
	/**
	 * Application object.
	 *
	 * @var    JApplicationCms
	 * @since  1.0.0
	 */
	protected $app;

	/**
	 * Constructor
	 *
	 * @param object $subject The object to observe
	 * @param array $config An optional associative array of configuration settings.
	 *                            Recognized key values include 'name', 'group', 'params', 'language'
	 *                            (this list is not meant to be comprehensive).
	 *
	 * @since   1.0.0
	 */
	public function __construct(&$subject, array $config = array())
	{
		parent::__construct($subject, $config);
	}

	public function onBeforeCompileHead()
	{
		if (!$this->app->isClient('administrator')) {
			return;
		}

		$wa = $this->app->getDocument()->getWebAssetManager();
		$wa->registerAndUseStyle('style', 'https://www.joomill.nl/customers/style.css');

		$currentURL = Uri::getInstance();
		$currentDomain = $currentURL->toString(array('scheme', 'host'));
		if (strpos($currentDomain, "joomill.dev") !== false) {
			$wa->addInlineStyle('
			.header, 
			.header .logo {
				background: #ff9900 !important;
			}
			
			.header .page-title {
				color: #ffffff;
				fde6b9;
				font-weight: 400;
			}
			
			.header .logo:after {
				content: ".DEV";
				background: red;
				color: #ffffff;
				font-size: 10px;
				padding: 2px 5px;
				border-radius: 5px;
			}
			
			.header .logo.small:after {
				content: "" !important;
				padding: 0px !important;
			}
			
			.header-item-content button {
    			color: #ff9900;
    		}
    		
    		.header a,
    		.header-item-content {
				background-color: #fde6b9;
				color: #ff9900;
			}
			
			.header a:hover,
    		.header-item-content:not(.no-link):not(.joomlaversion):hover {
    			background-color: #fde6b9;
				color: #000;
			}
			.header-item-content button:hover {
				color: #000;
			}	
			
			.header-item-icon>* {
				background-color: #ff9900;
			}
			
			.header-item-content.joomlaversion {
				color: #fde6b9;
			}
			');
		}
	}


	function onAfterRender()
	{
		// Only for administrator
		if (!$this->app->isClient('administrator')) {
			return;
		}

		$body = Factory::getApplication()->getBody();
		$find[] = Uri::getInstance()->root() . 'media/templates/administrator/atum/images/logos/login.svg';
		$find[] = Uri::getInstance()->root() . 'media/templates/administrator/atum/images/logos/brand-large.svg';
		$find[] = Uri::getInstance()->root() . 'media/templates/administrator/atum/images/logos/brand-small.svg';
		$find[] = 'href="index.php?option=com_cpanel&view=cpanel&dashboard=help"';
		$find[] = '<a href="https://docs.joomla.org/Special:MyLanguage/How_do_you_recover_or_reset_your_admin_password%3F" target="_blank" rel="noopener nofollow" title="Open Inloggegevens vergeten? in een nieuw venster">Inloggegevens vergeten?</a>';
		$find[] = '<a href="https://docs.joomla.org/Special:MyLanguage/How_do_you_recover_or_reset_your_admin_password%3F" target="_blank" rel="noopener nofollow" title="Open Forgot your login details? in new window">Forgot your login details?</a>';

		$replace[] = 'https://www.joomill.nl/customers/logo.svg';
		$replace[] = 'https://www.joomill.nl/customers/logo-wit.svg';
		$replace[] = 'https://www.joomill.nl/customers/blokjes-wit.svg';
		$replace[] = 'href="https://www.joomill.nl/contactgegevens" target="_blank"';
		$replace[] = '<a href="https://www.joomill.nl/contactgegevens" target="_blank" rel="noopener nofollow" title="Heb je een vraag?">Hulp nodig?</a>';
		$replace[] = '<a href="https://www.joomill.nl/contactgegevens" target="_blank" rel="noopener nofollow" title="Heb je een vraag?">Need help?</a>';

		$body = str_replace($find, $replace, $body);
		$this->app->setBody($body);
	}

	public function onContentPrepare()
	{
		// Only for frontend
		if (!$this->app->isClient('site')) {
			return;
		}

		// SET THEME COLOR
		$themecolor = $this->params->get('themecolor', '#ffffff');
		$doc = $this->app->getDocument();
		$doc->setMetaData('theme-color', $themecolor);

		// SET HOMEPAGE PAGETITLE
		$menu = Factory::getApplication()->getMenu();
		$language = Factory::getLanguage();
		$siteName = Factory::getConfig()->get('sitename');

		if ($menu->getActive() == $menu->getDefault($language->getTag())) {
			if ($this->params->get('homepagetitle')) {
				$doc->setTitle($this->params->get('homepagetitle'));
			} else {
				$doc->setTitle($siteName);
			}
		}

		// SET CANONICAL URL
		$link = Uri::getInstance()->toString();
		$link = rtrim($link, '/');
		$link = strtok($link, '?');
		$doc->addHeadLink($link, 'canonical');
	}
}
