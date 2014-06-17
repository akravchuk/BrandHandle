<?php

/**
 * @package 	BrandHandle plugin
 * @subpackage 	ServerPlugins
 * @since 		v8.0
 * @copyright	LEO. All Rights Reserved. 
 */

require_once BASEDIR.'/server/interfaces/plugins/EnterprisePlugin.class.php';
require_once BASEDIR.'/server/interfaces/plugins/PluginInfoData.class.php';
 
class BrandHandle_EnterprisePlugin extends EnterprisePlugin
{
	public function getPluginInfo()
	{ 
		$info = new PluginInfoData(); 
		$info->DisplayName = 'BrandHandle_vs_MetaDataFill';
		$info->Version     = 'v2.0'; // don't use PRODUCTVERSION
	    $info->Description = 'Change value in Dialog Forms';
		$info->Copyright   = 'Icenter Ukraine, Edited by leo, last_edit_05_02_14';
		return $info;
	}
	
	final public function getConnectorInterfaces() 
	{ 
		return array('WflGetDialog2_EnterpriseConnector', 
					 'WflCreateObjects_EnterpriseConnector'
					); 
	
	}
}