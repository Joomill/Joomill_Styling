<?php
/*
 *  package: System - Joomill Styling plugin
 *  copyright: Copyright (c) 2025. Jeroen Moolenschot | Joomill
 *  license: GNU General Public License version 3 or later
 *  link: https://www.joomill.nl
 */

// No direct access
defined('_JEXEC') or die;

use Joomla\CMS\Factory;

class plgSystemJoomillInstallerScript
{

	public function install($parent)
	{
		// Enable the extension
		$this->enablePlugin();

		return true;
	}

	private function enablePlugin()
	{
		try
		{
			$db    = Factory::getContainer()->get('DatabaseDriver');
			$query = $db->getQuery(true)
				->update($db->qn('#__extensions'))
				->set($db->qn('enabled') . ' = ' . $db->q(1))
				->where('type = ' . $db->q('plugin'))
				->where('folder = ' . $db->q('system'))
				->where('element = ' . $db->q('joomill'));
			$db->setQuery($query);
			$db->execute();
		}
		catch (\Exception $e)
		{
			return;
		}
	}
}
