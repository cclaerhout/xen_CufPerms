<?php
class Sedo_CufPerms_ViewPublic_Account_ContactPreferences extends XFCP_Sedo_CufPerms_ViewPublic_Account_ContactPreferences
{
	public function renderHtml()
	{
		if(is_callable('parent::renderHtml'))
		{
			parent::renderHtml();
		}

		if(!isset($this->_params['customFields']))
		{
			return;
		}

		$cufs =  &$this->_params['customFields'];

		$visitor = XenForo_Visitor::getInstance();
		$visitorUserGroupIds = array_merge(array((string)$visitor['user_group_id']), (explode(',', $visitor['secondary_group_ids'])));
		
		foreach($cufs as $k => $cuf)
		{
			if(empty($cuf['sedo_perms_input_enable']))
			{
				continue;
			}
			
			$validUsergroups = @unserialize($cuf['sedo_perms_input_val']);
			
			if(!array_intersect($visitorUserGroupIds, $validUsergroups) && !in_array('all', $validUsergroups))
			{
				unset($cufs[$k]);
			}
		}
	}
}
//Zend_Debug::dump($pagesData);