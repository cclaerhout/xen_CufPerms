<?php
class Sedo_CufPerms_ViewAdmin_UserField_Edit extends XFCP_Sedo_CufPerms_ViewAdmin_UserField_Edit
{
	protected $_sedoPermsValKeys = array('sedo_perms_input_val', 'sedo_perms_output_pp_val', 'sedo_perms_output_ui_val');

	public function renderHtml()
	{
		if(is_callable('parent::renderHtml'))
		{
			parent::renderHtml();
		}

		if(!isset($this->_params['field'], $this->_params['field']['sedo_perms_input_val']))
		{
			return;
		}

		$optionGetUsergroupsModel = XenForo_Model::create('Sedo_CufPerms_Model_GetUsergroups');
		foreach($this->_sedoPermsValKeys as $key)
		{
			$settings = @unserialize($this->_params['field'][$key]);

			if(!is_array($settings))
			{
				$settings = array();
			}

			$this->_params['field'][$key] = $optionGetUsergroupsModel->getUserGroupOptions($settings, true);
		}
	}
}
//Zend_Debug::dump($pagesData);