<?php
class Sedo_CufPerms_Listener
{
	public static function CMS_user_field_edit($matches)
	{
		$template = $matches[0];

		$search[] = '#(<li><a>{xen:phrase general_options}</a></li>.*?)</ul>#sui';
		$replace[] = '$1<li><a>{xen:phrase permissions}</a></li></ul>';

		$search[] = '#</ul>[\s]*?<xen:submitunit#sui';
		$replace[] = '<xen:include template="sedo_user_field_edit_perms_pane" />$0';
		
		$template = preg_replace($search, $replace, $template);
		return $template;
	}

	public static function extendsDwUserField($class, array &$extend)
	{
		if($class == 'XenForo_DataWriter_UserField')
		{
			$extend[] = 'Sedo_CufPerms_DataWriter_UserField';
		}
	}

	public static function extendsViewAdminUserFieldEdit($class, array &$extend)
	{
		if($class == 'XenForo_ViewAdmin_UserField_Edit')
		{
			$extend[] = 'Sedo_CufPerms_ViewAdmin_UserField_Edit';
		}
	}

	public static function extendsViewPublicMemberView($class, array &$extend)
	{
		if($class == 'XenForo_ViewPublic_Member_View')
		{
			$extend[] = 'Sedo_CufPerms_ViewPublic_Member_View';
		}
	}

	public static function extendsViewPublicAccountPreferences($class, array &$extend)
	{
		if($class == 'XenForo_ViewPublic_Account_Preferences')
		{
			$extend[] = 'Sedo_CufPerms_ViewPublic_Account_Preferences';
		}
	}

	public static function extendsViewPublicAccountPersonalDetails($class, array &$extend)
	{
		if($class == 'XenForo_ViewPublic_Account_PersonalDetails')
		{
			$extend[] = 'Sedo_CufPerms_ViewPublic_Account_PersonalDetails';
		}
	}

	public static function extendsViewPublicAccountContactPreferences($class, array &$extend)
	{
		if($class == 'XenForo_ViewPublic_Account_ContactPreferences')
		{
			$extend[] = 'Sedo_CufPerms_ViewPublic_Account_ContactPreferences';
		}
	}	

	public static function extendsModelUserField($class, array &$extend)
	{
		if($class == 'XenForo_Model_UserField')
		{
			$extend[] = 'Sedo_CufPerms_Model_UserField';
		}
	}

	protected static $_userfieldvalueOriginalCallback;
	protected static $_visitorUserGroupIds;
	public static function initTemplateHelpers(XenForo_Dependencies_Abstract $dependencies, array $data)
	{
		if(!isset(XenForo_Template_Helper_Core::$helperCallbacks['userfieldvalue']))
		{
			return;
		}

		self::$_userfieldvalueOriginalCallback = XenForo_Template_Helper_Core::$helperCallbacks['userfieldvalue'];
		if(self::$_userfieldvalueOriginalCallback[0] == 'self')
		{
			self::$_userfieldvalueOriginalCallback[0] = 'XenForo_Template_Helper_Core';
		}

		XenForo_Template_Helper_Core::$helperCallbacks['userfieldvalue'] = array('Sedo_CufPerms_Listener', 'helperUserFieldValue');
	}
	
	public static function helperUserFieldValue($field, array $user = array(), $fieldValue = null)
	{
		$parent = call_user_func_array(self::$_userfieldvalueOriginalCallback, array($field, $user, $fieldValue));

		if(isset($field['sedo_perms_ui_users']))
		{
			if(!self::$_visitorUserGroupIds)
			{
				$visitor = XenForo_Visitor::getInstance();
				self::$_visitorUserGroupIds = array_merge(array((string)$visitor['user_group_id']), (explode(',', $visitor['secondary_group_ids'])));
			}

			$visitorUserGroupIds = self::$_visitorUserGroupIds;
			$validUsergroups = $field['sedo_perms_ui_users'];

			if(!array_intersect($visitorUserGroupIds, $validUsergroups) && !in_array('all', $validUsergroups))
			{
				return false;
			}			
		}

		return $parent;
	}
}
//Zend_Debug::dump($abc);