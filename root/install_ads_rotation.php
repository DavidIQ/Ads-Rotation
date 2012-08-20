<?php
/**
*
* @author DavidIQ (David Colon) davidiq@phpbb.com
* @package umil
* @copyright (c) 2012 DavidIQ
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/**
* @ignore
*/
define('UMIL_AUTO', true);
define('IN_PHPBB', true);
$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : './';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
include($phpbb_root_path . 'common.' . $phpEx);
$user->session_begin();
$auth->acl($user->data);
$user->setup();

if (!file_exists($phpbb_root_path . 'umil/umil_auto.' . $phpEx))
{
	trigger_error('Please download the latest UMIL (Unified MOD Install Library) from: <a href="http://www.phpbb.com/mods/umil/">phpBB.com/mods/umil</a>', E_USER_ERROR);
}

$mod_name = 'ACP_CAT_ADS_ROTATION';
$version_config_name = 'ads_rotation_version';
$language_file = 'mods/info_acp_ads_rotation';
include($phpbb_root_path . 'includes/ads_rotation.' . $phpEx);

$versions = array(
	'1.0.0' => array(
		'config_add' => array(
			array('ads_rotation_enabled', true),
			array('ads_rotation_ad_count', 1),
			array('ads_rotation_upload_dir', 'images/ads'),
		),

		'permission_add' => array(
			array('a_ads_rotation', true),
		),

		'module_add' => array(
			array('acp', 'ACP_CAT_DOT_MODS', 'ACP_CAT_ADS_ROTATION'),
			
			array('acp', 'ACP_CAT_ADS_ROTATION', array(
					'module_basename'		=> 'ads_rotation',
					'module_langname'		=> 'ACP_ADS_ROTATION_CONFIG',
					'modes'					=> 'main',
					'module_auth'			=> 'acl_a_ads_rotation',
				),
			),
		),

		'table_add'	=> array(
			array(ADS_ROTATION_TABLE, array(
					'COLUMNS'	=> array(
						'ad_id'				=> array('UINT', NULL, 'auto_increment'),
						'ad_description'	=> array('VCHAR:255', ''),
						'ad_image_file'		=> array('VCHAR', ''),
						'ad_url'			=> array('VCHAR:500', ''),
						'ad_disabled'		=> array('TINT:1', '0'),
					),
					'PRIMARY_KEY'	=> array('ad_id'),
				),
			),
		),
	),
);

include($phpbb_root_path . 'umil/umil_auto.' . $phpEx);

//Clear cache
$umil->cache_purge(array(
	array(''),
	array('auth'),
	array('template'),
	array('theme'),
));