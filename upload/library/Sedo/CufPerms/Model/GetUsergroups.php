<?php
class Sedo_CufPerms_Model_GetUserGroups extends XenForo_Model
{
	public function getUserGroupOptions($selectedUserGroupIds, $addAllOption = false)
	{
		$userGroups = array();
		foreach ($this->getDbUserGroups($addAllOption) AS $userGroup)
		{
			$userGroups[] = array(
				'label' => filter_var($userGroup['title'], FILTER_SANITIZE_STRING),
				'value' => $userGroup['user_group_id'],
				'selected' => in_array($userGroup['user_group_id'], $selectedUserGroupIds)
			);
		}

		return $userGroups;
	}

	public function getDbUserGroups($addAllOption = false)
	{
		$usergroups = $this->_getDb()->fetchAll('
			SELECT user_group_id, title
			FROM xf_user_group
			WHERE user_group_id
			ORDER BY user_group_id
		');
		
		if(!$addAllOption)
		{
			return $usergroups;
		}
		
		$allOption = array(
			'title' => 'all',
			'user_group_id' => 'all'
		);
		
		array_unshift($usergroups, $allOption);
		return $usergroups;
	}
}
	
	
