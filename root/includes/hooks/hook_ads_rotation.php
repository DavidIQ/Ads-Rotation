<?php
/**
*
* @package Ads Rotation
* @copyright (c) 2012 DavidIQ.com
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*/

/**
* @ignore
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

//Don't load hook if not installed.
if (!isset($config['ads_rotation_version']))
{
	return;
}

/**
 * A hook that is used to change the behavior of phpBB just before the templates
 * are displayed.
 * @param	phpbb_hook	$hook	the phpBB hook object
 * @return	void
 */
function ads_rotation_template_hook(&$hook)
{
	global $template, $phpEx, $phpbb_root_path, $config, $user;

	$page_name = substr($user->page['page_name'], 0, strpos($user->page['page_name'], '.'));

	//Don't do anything if we're on one of these pages or in admin
	if (!in_array($page_name, array('index', 'viewforum', 'viewtopic')) || defined('ADMIN_START'))
	{
		return;
	}

	if (!class_exists('ads_rotation'))
	{
		include($phpbb_root_path . 'includes/ads_rotation.' . $phpEx);
	}

	$ads_rotation = new ads_rotation();
	$ads_rotation_enabled = $ads_rotation->get_config('ads_rotation_enabled', false);

	//Standard template variables
	$template->assign_vars(array(
		'S_ADS_ROTATION'					=> $ads_rotation_enabled,
	));

	//If we're enabled let's get the list
	if ($ads_rotation_enabled)
	{
		$ads_list = $ads_rotation->get_ads_list(0);

		if (sizeof($ads_list))
		{
			$ads_rotation_ad_count = $ads_rotation->get_config('ads_rotation_ad_count', 1);
			shuffle($ads_list); //Randomize array
			$cnt = 0;
			$img_slide = '';
			$ad_slides = array();

			foreach ($ads_list as $ad)
			{
				$ad_image = $phpbb_root_path . $config['ads_rotation_upload_dir'] . '/' . $ad['ad_image_file'];

				if (!empty($ad['ad_url']))
				{
					$ad_img = sprintf('<a href="%1$s" title="%2$s"><img src="%3$s" alt="%2$s" class="phpbb_ad_rotation_img" /></a>', $ad['ad_url'], $ad['ad_description'], $ad_image);
				}
				else
				{
					$ad_img = sprintf('<img src="%1$s" alt="%2$s" title="%2$s" class="phpbb_ad_rotation_img" />', $ad_image, $ad['ad_description']);
				}

				$img_slide .= $ad_img;
				$cnt++;

				//Add to our second array if we've reached the desired count
				if (($cnt % $ads_rotation_ad_count) == 0)
				{
					$cnt = 0;
					$ad_slides[] = $img_slide;
					$img_slide = '';
				}
			}

			//Make sure we didn't miss one
			if (!empty($img_slide))
			{
				$ad_slides[] = $img_slide;
			}

			$first_ad = true;

			foreach ($ad_slides as $slide)
			{
				$template->assign_block_vars('ads', array(
					'AD_SLIDE'			=> $slide,
					'AD_FIRST'			=> $first_ad,
				));
				$first_ad = false;
			}
		}
	}
}

// Register
$phpbb_hook->register(array('template', 'display'), 'ads_rotation_template_hook');
