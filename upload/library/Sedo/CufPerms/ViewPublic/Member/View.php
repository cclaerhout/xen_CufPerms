<?php
class Sedo_CufPerms_ViewPublic_Member_View extends XFCP_Sedo_CufPerms_ViewPublic_Member_View
{
	public function renderHtml()
	{
		parent::renderHtml();

		if(!isset($this->_params['customFieldsGrouped'], $this->_params['customFieldsGrouped']['personal']))
		{
			return;
		}

		$cufs =  &$this->_params['customFieldsGrouped']['personal'];

		$visitor = XenForo_Visitor::getInstance();
		$visitorUserGroupIds = array_merge(array((string)$visitor['user_group_id']), (explode(',', $visitor['secondary_group_ids'])));
		
		foreach($cufs as $k => $cuf)
		{
			if(empty($cuf['sedo_perms_output_pp_enable']))
			{
				continue;
			}
			
			$validUsergroups = @unserialize($cuf['sedo_perms_output_pp_val']);
			
			if(!array_intersect($visitorUserGroupIds, $validUsergroups) && !in_array('all', $validUsergroups))
			{
				unset($cufs[$k]);
			}
		}
	}
}
//Zend_Debug::dump($pagesData);