<?php

class Sedo_CufPerms_DataWriter_UserField extends XFCP_Sedo_CufPerms_DataWriter_UserField
{
	protected function _getFields()
	{
		$fields = parent::_getFields();
		
		if(isset($fields['xf_user_field']))
		{
			$fields['xf_user_field'] += array(
				'sedo_perms_input_enable' => array('type' => self::TYPE_BOOLEAN, 'default' => 0),
				'sedo_perms_output_pp_enable' => array('type' => self::TYPE_BOOLEAN, 'default' => 0),
				'sedo_perms_output_ui_enable' => array('type' => self::TYPE_BOOLEAN, 'default' => 0),
				'sedo_perms_input_val' => array('type' => self::TYPE_SERIALIZED, 'default' => ''),
				'sedo_perms_output_pp_val' => array('type' => self::TYPE_SERIALIZED, 'default' => ''),
				'sedo_perms_output_ui_val' => array('type' => self::TYPE_SERIALIZED, 'default' => '')
			);
		}
		
		return $fields;
	}

	protected function _preSave()
	{
	        $_input = new XenForo_Input($_REQUEST);

		$sedo_perms_input_enable = $_input->filterSingle('sedo_perms_input_enable', XenForo_Input::UINT);
		$sedo_perms_output_pp_enable = $_input->filterSingle('sedo_perms_output_pp_enable', XenForo_Input::UINT);
		$sedo_perms_output_ui_enable = $_input->filterSingle('sedo_perms_output_ui_enable', XenForo_Input::UINT);

		$sedo_perms_input_val = $_input->filterSingle('sedo_perms_input_val', XenForo_Input::STRING, array('array' => true));
		$sedo_perms_output_pp_val = $_input->filterSingle('sedo_perms_output_pp_val', XenForo_Input::STRING, array('array' => true));
		$sedo_perms_output_ui_val = $_input->filterSingle('sedo_perms_output_ui_val', XenForo_Input::STRING, array('array' => true));

		$this->set('sedo_perms_input_enable', $sedo_perms_input_enable);
		$this->set('sedo_perms_output_pp_enable', $sedo_perms_output_pp_enable);
		$this->set('sedo_perms_output_ui_enable', $sedo_perms_output_ui_enable);
		
		$this->set('sedo_perms_input_val', $sedo_perms_input_val);
		$this->set('sedo_perms_output_pp_val', $sedo_perms_output_pp_val);
		$this->set('sedo_perms_output_ui_val', $sedo_perms_output_ui_val);

		return parent::_preSave();
	}
}
//Zend_Debug::dump($class);