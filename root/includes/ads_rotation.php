<?php
/**
*
* @package Ads Rotation
* @copyright (c) 2012 DavidIQ.com
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/**
* @ignore
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

//Define the ads rotation table here so we don't have to do any edits
global $table_prefix;
define('ADS_ROTATION_TABLE',				$table_prefix . 'ads_rotation');

class ads_rotation
{
	/*
	* Get the configuration value and if it doesn't exist return the default
	*
	* @param string $config_name The configuration value to retrieve
	* @param string $default_value Default value to return if config value does not exist
	*/
	function get_config($config_name, $default_value = '')
	{
		global $config;

		if (isset($config[$config_name]) && !empty($config[$config_name]))
		{
			return $config[$config_name];
		}
		else
		{
			return $default_value;
		}
	}

	/*
	* Retrieves a list of ads from the ads rotation table
	*
	* @param integer $ad_disabled Filters ads to be retrieved by ad status
	*/
	function get_ads_list($ad_disabled = false)
	{
		global $db, $cache;
		$rowset = array();

		$sql = 'SELECT * 
				FROM ' . ADS_ROTATION_TABLE;

		if ($ad_disabled !== false)
		{
			$sql .= ' WHERE ad_disabled = ' . (int) $ad_disabled;
		}

		if ($ad_disabled === false || ($rowset = $cache->get('_ads_rotation')) === false)
		{
			$rowset = array();  //Re-initialize array just in case
			$result = $db->sql_query($sql);
			while ($row = $db->sql_fetchrow($result))
			{
				$rowset[] = $row;
			}
			$db->sql_freeresult($result);

			if ($ad_disabled !== false)
			{
				$cache->put('_ads_rotation', $rowset);
			}
		}

		return $rowset;
	}

	/*
	* Stores the ad information into the ads rotation table
	*
	* @param array $ad_info The ad data to be stored
	*/
	function insert_ad($ad_info)
	{
		global $db, $cache;
		$sql_array = array(
			'ad_description'	=> $ad_info['ad_description'],
			'ad_image_file'		=> $ad_info['ad_image_file'],
			'ad_url'			=> $ad_info['ad_url'],
		);

		$db->sql_query('INSERT INTO ' . ADS_ROTATION_TABLE . ' ' . $db->sql_build_array('INSERT', $sql_array));

		$cache->destroy('_ads_rotation');
	}

	/*
	* Changes the ad status from enabled to disabled and vice-versa
	*
	* @param integer $ad_id The ad's identifier in the table
	* @param integer $ad_disabled The status that will be set for the ad
	*/
	function change_ad_status($ad_id, $ad_disabled)
	{
		global $db, $cache;
		$sql = 'UPDATE ' . ADS_ROTATION_TABLE . '
				SET ad_disabled = ' . (int) $ad_disabled . '
				WHERE ad_id = ' . (int) $ad_id;

		$db->sql_query($sql);

		$cache->destroy('_ads_rotation');
	}

	/*
	* Deletes an ad from the ads rotation table
	*
	* @param integer $ad_id The ad to be deleted
	*/
	function delete_ad($ad_id)
	{
		global $db, $cache;
		$sql = 'DELETE FROM ' . ADS_ROTATION_TABLE . '
				WHERE ad_id = ' . (int) $ad_id;

		$db->sql_query($sql);

		$cache->destroy('_ads_rotation');
	}

	/*
	* Gets an ad entry
	*
	* @param integer $ad_id The ad's identifier in the table
	*/
	function get_ad($ad_id)
	{
		global $db;
		$row = array();

		$sql = 'SELECT * 
				FROM ' . ADS_ROTATION_TABLE . '
				WHERE ad_id = ' . (int) $ad_id;

		$result = $db->sql_query_limit($sql, 1);
		$row = $db->sql_fetchrow($result);
		$db->sql_freeresult($result);

		return $row;
	}
}
