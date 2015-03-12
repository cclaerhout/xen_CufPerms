<?php
class Sedo_CufPerms_Model_UserField extends XFCP_Sedo_CufPerms_Model_UserField
{
	protected $_sedoGetUserFieldsBackup;
	protected $_sedoEnableGetUserFieldsBackup = false;

	public function getUserFields(array $conditions = array(), array $fetchOptions = array())
	{
		if($this->_sedoEnableGetUserFieldsBackup && $this->_sedoGetUserFieldsBackup)
		{
			return $this->_sedoGetUserFieldsBackup;
		}

		$parent = parent::getUserFields($conditions, $fetchOptions);
		
		if($this->_sedoEnableGetUserFieldsBackup)
		{
			$this->_sedoGetUserFieldsBackup = $parent;
		}
		
		return $parent;
	}


	public function rebuildUserFieldCache()
	{
		$this->_sedoEnableGetUserFieldsBackup = true;
		$parent = parent::rebuildUserFieldCache();
		$hasChanged = false;
		
		foreach ($this->getUserFields() AS $fieldId => $field)
		{
			if(!empty($field['sedo_perms_output_ui_enable']))
			{
				$hasChanged = true;
				$parent[$fieldId]['sedo_perms_ui_users'] = @unserialize($field['sedo_perms_output_ui_val']);
			}
		}		

		if($hasChanged)
		{
			$this->_getDataRegistryModel()->set('userFieldsInfo', $parent);
		}
	
		return $parent;
	}
}
//Zend_Debug::dump($field);