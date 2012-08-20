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

/**
* @package acp
*/
class acp_ads_rotation
{
	var $u_action;
	var $permissions = 0755;

	function main($id, $mode)
	{
		global $db, $user, $template;
		global $config, $phpbb_root_path, $phpEx;
		include($phpbb_root_path . 'includes/ads_rotation.' . $phpEx);
		$ads_rotation = new ads_rotation();

		$this->tpl_name = 'acp_ads_rotation';
		$this->page_title = 'ACP_ADS_ROTATION_CONFIG';

		$form_name = 'acp_ads_rotation';
		add_form_key($form_name);

		$submit = (isset($_POST['submit'])) ? true : false;
		$ads_rotation_enabled = request_var('ads_rotation_enabled', (bool) $ads_rotation->get_config('ads_rotation_enabled', false));
		$ads_rotation_ad_count = request_var('ads_rotation_ad_count', (int) $ads_rotation->get_config('ads_rotation_ad_count', 1));
		$upload_dir = $phpbb_root_path . $config['ads_rotation_upload_dir'];

		$action	= request_var('action', 'add');

		switch ($action)
		{
			case 'add':
				if ($submit)
				{
					if (!check_form_key($form_name))
					{
						trigger_error($user->lang['FORM_INVALID']. adm_back_link($this->u_action), E_USER_WARNING);
					}

					//If we have a file to upload and ads rotation is enabled let's take care of it
					if ($ads_rotation_enabled)
					{
						if (empty($_FILES['ads_rotation_image']['name']))
						{
							//Update configuration now
							set_config('ads_rotation_enabled', $ads_rotation_enabled);
							set_config('ads_rotation_ad_count', $ads_rotation_ad_count);

							add_log('admin', 'LOG_ADS_ROTATION_ENABLED');
							trigger_error($user->lang['ADS_ROTATION_ENABLED'] . adm_back_link($this->u_action));
						}

						//We need to make sure the upload directory exists
						if (!is_dir($upload_dir))
						{
							//Let's try to create the folder
							mkdir($upload_dir, $this->permissions);

							//Still no folder?
							if (!is_dir($upload_dir))
							{
								//Throw an error...user must create manually
								trigger_error(sprintf($user->lang['NO_UPLOAD_DIR'], $upload_dir) . adm_back_link($this->u_action), E_USER_WARNING);
							}
						}

						include($phpbb_root_path . 'includes/functions_upload.' . $phpEx);
						$upload = new fileupload();
						$upload->set_allowed_extensions(array('gif', 'jpg', 'jpeg', 'png'));	// Only allow image files

						$file = $upload->form_upload('ads_rotation_image');

						if (empty($file->filename))
						{
							trigger_error($user->lang['NO_AD_IMAGE_FILE'] . adm_back_link($this->u_action), E_USER_WARNING);
						}
						else if ($file->init_error || sizeof($file->error))
						{
							$file->remove();
							trigger_error((sizeof($file->error) ? implode('<br />', $file->error) : $user->lang['AD_UPLOAD_INIT_FAIL']) . adm_back_link($this->u_action), E_USER_WARNING);
						}

						$file->clean_filename('real');
						$file->move_file($config['ads_rotation_upload_dir'], true);
						//phpbb_chmod does not give us the right permissions
						@chmod($upload_dir . '/' . $file->realname, $this->permissions);

						if (sizeof($file->error))
						{
							$file->remove();
							trigger_error(implode('<br />', $file->error) . adm_back_link($this->u_action), E_USER_WARNING);
						}

						$ad_info = array(
							'ad_description'	=>	request_var('ads_rotation_description', ''),
							'ad_image_file'		=>	$file->realname,
							'ad_url'			=>	request_var('ads_rotation_url', ''),
						);

						$ads_rotation->insert_ad($ad_info);

						add_log('admin', 'LOG_ADS_ROTATION_UPLOADED', $ad_info['ad_image_file'], $ad_info['ad_description']);
						trigger_error($user->lang['ADS_ROTATION_UPLOADED'] . adm_back_link($this->u_action));
					}
					else if (!$ads_rotation_enabled)
					{
						//Update configuration now
						set_config('ads_rotation_enabled', $ads_rotation_enabled);

						add_log('admin', 'LOG_ADS_ROTATION_DISABLED');
						trigger_error($user->lang['ADS_ROTATION_DISABLED'] . adm_back_link($this->u_action));
					}
				}
			break;

			case 'delete':
				$ad_id = request_var('ad_id', 0);
				$ad_info = $ads_rotation->get_ad($ad_id);

				if (isset($ad_info['ad_id']))
				{
					$ads_rotation->delete_ad($ad_id);

					//Delete the file now
					if (file_exists($upload_dir . '/' . $ad_info['ad_image_file']))
					{
						unlink($upload_dir . '/' . $ad_info['ad_image_file']);
					}

					add_log('admin', 'LOG_ADS_ROTATION_DELETED', $ad_id);
					trigger_error($user->lang['ADS_ROTATION_DELETED'] . adm_back_link($this->u_action));
				}
			break;

			case 'status':
				$ad_id = request_var('ad_id', 0);
				$status = request_var('status', false);
				$ads_rotation->change_ad_status($ad_id, $status);

				if (!$status)
				{
					add_log('admin', 'LOG_ADS_ROTATION_ACTIVATED', $ad_id);
					trigger_error($user->lang['ADS_ROTATION_ACTIVATED'] . adm_back_link($this->u_action));
				}
				else
				{
					add_log('admin', 'LOG_ADS_ROTATION_DEACTIVATED', $ad_id);
					trigger_error($user->lang['ADS_ROTATION_DEACTIVATED'] . adm_back_link($this->u_action));
				}
			break;
		}

		$ad_count_options = array();
		$i = 0;

		//Build the number of ads drop-down
		for ($i = 1; $i <= 5; $i++)
		{
			$selected = '';
			if ($i == $ads_rotation_ad_count)
			{
				$selected = ' selected="selected"';
			}
			$ad_count_options[] = sprintf('<option value="%1$d"%2$s>%1$d</option>', $i, $selected);
		}

		$template->assign_vars(array(
			'ADS_ROTATION_VERSION'		=> $ads_rotation->get_config('ads_rotation_version', '1.0.0'),
			'ADS_ROTATION_IMG_DIR'		=> $config['ads_rotation_upload_dir'],
			'S_ADS_ROTATION_ENABLED'	=> $ads_rotation_enabled,
			'S_AD_COUNT_OPTIONS'		=> implode("", $ad_count_options),
			'U_ACTION'					=> $this->u_action,
		));

		$ads_list = $ads_rotation->get_ads_list();

		if (sizeof($ads_list))
		{
			foreach ($ads_list as $ad)
			{
				$template->assign_block_vars('ads', array(
					'AD_ID'				=> $ad['ad_id'],
					'AD_DESCRIPTION'	=> $ad['ad_description'],
					'AD_IMAGE'			=> $ad['ad_image_file'],
					'AD_URL'			=> $ad['ad_url'],

					'S_DISABLED'		=> $ad['ad_disabled'],

					'U_IMAGE'			=> $upload_dir . '/' . $ad['ad_image_file'],
					'U_DELETE'			=> $this->u_action . '&amp;action=delete&amp;ad_id=' . $ad['ad_id'],
					'U_STATUS_CHANGE'	=> $this->u_action . '&amp;action=status&amp;ad_id=' . $ad['ad_id'] . '&amp;status=' . (($ad['ad_disabled']) ? 0 : 1),
				));
			}
		}
	}
}

?>