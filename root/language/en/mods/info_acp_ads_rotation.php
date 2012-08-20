<?php
/**
*
* ads_rotation [English]
*
* @package language
* @copyright (c) 2012 DavidIQ.com
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/**
* DO NOT CHANGE
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

// DEVELOPERS PLEASE NOTE
//
// All language files should use UTF-8 as their encoding and the files must not contain a BOM.
//
// Placeholders can now contain order information, e.g. instead of
// 'Page %s of %s' you can (and should) write 'Page %1$s of %2$s', this allows
// translators to re-order the output of data while ensuring it remains correct
//
// You do not need this where single placeholders are used, e.g. 'Message %d' is fine
// equally where a string contains only two placeholders which are used to wrap text
// in a url you again do not need to specify an order e.g., 'Click %sHERE%s' is fine
//
// NOTE TO TRANSLATORS:  Text in parenthesis refers to keys on the keyboard

$lang = array_merge($lang, array(
	'ACP_CAT_ADS_ROTATION'			=> 'Ads Rotation',
	'ACP_ADS_ROTATION_CONFIG'		=> 'Ads Rotation configuration',
	'ACP_ADS_ROTATION_CONFIG_EXPLAIN'	=> 'Ads rotation gives you the ability to upload advertisement images, which will be included in an image rotation that is triggered via JavaScript.',

	'ADS_ROTATION_OPTIONS'			=> 'Ads Rotation options',
	'ADS_ROTATION_VERSION'			=> 'Ads Rotation version',
	'ADS_ROTATION_IMG_DIR'			=> 'Ads Rotation image directory',
	'ADS_ROTATION_ENABLE'			=> 'Enable Ads Rotation',
	'ADS_ROTATION_AD_COUNT'			=> 'Maximum number of ads per slide',
	'ADS_ROTATION_AD_COUNT_EXPLAIN' => 'The maximum number of ads to show at the same time on a given slide of the rotating ads.',
	'ADS_ROTATION_ADD'				=> 'Upload an ad',
	'ADS_ROTATION_IMAGE'			=> 'Ad Image',
	'ADS_ROTATION_IMAGE_EXPLAIN'	=> 'Select the image filename to upload for use as an ad.  Allowed file extensions: gif, jpg, jpeg, png.',
	'ADS_ROTATION_DESCRIPTION'		=> 'Ad Description (Optional)',
	'ADS_ROTATION_DESCRIPTION_EXPLAIN'	=> 'A description of the ad so you know what the ad is for.',
	'ADS_ROTATION_URL'				=> 'Ad URL (Optional)',
	'ADS_ROTATION_URL_EXPLAIN'		=> 'The URL to be used for the ad',
	'NO_ADS'						=> 'No ads are available',
	'NO_UPLOAD_DIR'					=> 'Upload directory is not available. You must manually create the upload directory at %s',
	'ADS_ROTATION_AD_STATUS'		=> 'Ad Status',
	'ADS_ROTATION_AD_DISABLE'		=> 'Disable Ad',
	'ADS_ROTATION_AD_ENABLE'		=> 'Enable Ad',
	'ADS_ROTATION_AD_DELETE'		=> 'Delete Ad',
	'NO_AD_IMAGE_FILE'				=> 'You have not selected an image file for the ad.',
	'AD_UPLOAD_INIT_FAIL'			=> 'Error initiating ad upload',

	'ADS_ROTATION_DISABLED'			=> 'Ads Rotation has been disabled.',
	'ADS_ROTATION_ENABLED'			=> 'Ads Rotation has been enabled.',
	'ADS_ROTATION_UPLOADED'			=> 'Ads Rotation ad has been uploaded.',
	'ADS_ROTATION_DELETED'			=> 'Ads Rotation ad deleted.',
	'ADS_ROTATION_ACTIVATED'		=> 'Ads Rotation ad activated.',
	'ADS_ROTATION_DEACTIVATED'		=> 'Ads Rotation ad de-activated.',

	'LOG_ADS_ROTATION_DISABLED'		=> '<strong>Ads Rotation MOD disabled</strong>',
	'LOG_ADS_ROTATION_ENABLED'		=> '<strong>Ads Rotation MOD enabled</strong>',
	'LOG_ADS_ROTATION_UPLOADED'		=> '<strong>Uploaded ad for Ads Rotation</strong>',
	'LOG_ADS_ROTATION_DELETED'		=> '<strong>Deleted ad from Ads Rotation</strong>',
	'LOG_ADS_ROTATION_ACTIVATED'	=> '<strong>Activated ad from Ads Rotation</strong>',
	'LOG_ADS_ROTATION_DEACTIVATED'	=> '<strong>De-activated ad from Ads Rotation</strong>',

	'DELETE_CONFIRM'				=> 'Are you sure you want to delete this ad? This cannot be undone.',
));

$lang = array_merge($lang, array(
	'acl_a_ads_rotation'	=> array('lang' => 'Can manage Ads Rotation settings', 'cat' => 'settings')
));

