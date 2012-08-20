<?php
/**
*
* @package Ads Rotation
* @copyright (c) 2012 DavidIQ.com
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/**
* @package module_install
*/
class acp_ads_rotation_info
{
	function module()
	{
		return array(
			'filename'	=> 'acp_ads_rotation',
			'title'		=> 'ACP_ADS_ROTATION_CONFIG',
			'version'	=> '1.0.0',
			'modes'		=> array(
				'main'		=> array(
						'title' => 'ACP_ADS_ROTATION_CONFIG',
						'auth'	=> 'acl_a_ads_rotation',
						'cat' 	=> array('ACP_CAT_ADS_ROTATION'),
				),
			),
		);
	}

	function install()
	{
	}

	function uninstall()
	{
	}
}

?>