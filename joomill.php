<?php
/*
 *  package: Joomill-Styling
 *  copyright: Copyright (c) 2022. Jeroen Moolenschot | Joomill
 *  license: GNU General Public License version 3 or later
 *  link: https://www.joomill.nl
 */

// Check to ensure this file is included in Joomla!
defined( '_JEXEC' ) or die( 'Restricted access' );

use Joomla\CMS\Factory;
use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\CMS\Uri\Uri;

class plgSystemJoomill extends CMSPlugin
{
    public function onBeforeCompileHead() {
        // Only work in the administrator
        $app = Factory::getApplication();
        if ($app->isClient('site')){
            // Do nothing
            return true;
        }

        $wa = Factory::getApplication()->getDocument()->getWebAssetManager();
        $wa->registerAndUseStyle('style', 'https://www.joomill.nl/customers/style.css');
        return true;
    }


    function onAfterRender() {
        // Only work in the administrator
        $app = Factory::getApplication();
        if ($app->isClient('site')){
            // Do nothing
            return true;
        }

        $body = Factory::getApplication()->getBody();
        $find[] = Uri::getInstance()->root().'media/templates/administrator/atum/images/logos/login.svg';
        $find[] = 'href="index.php?option=com_cpanel&view=cpanel&dashboard=help"';
        $find[] = '<a href="https://docs.joomla.org/Special:MyLanguage/How_do_you_recover_or_reset_your_admin_password%3F" target="_blank" rel="noopener nofollow" title="Open Inloggegevens vergeten? in een nieuw venster">Inloggegevens vergeten?</a>';
        $find[] = '<a href="https://docs.joomla.org/Special:MyLanguage/How_do_you_recover_or_reset_your_admin_password%3F" target="_blank" rel="noopener nofollow" title="Open Forgot your login details? in new window">Forgot your login details?</a>';

        $replace[] = 'https://www.joomill.nl/customers/login-logo.png';
        $replace[] = 'href="https://www.joomill.nl/contactgegevens" target="_blank"';
        $replace[] = '<a href="https://www.joomill.nl/contactgegevens" target="_blank" rel="noopener nofollow" title="Heb je een vraag?">Hulp nodig?</a>';
        $replace[] = '<a href="https://www.joomill.nl/contactgegevens" target="_blank" rel="noopener nofollow" title="Heb je een vraag?">Need help?</a>';

        $body = str_replace($find, $replace, $body);
        Factory::getApplication()->setBody($body);

        return true;
    }
}
