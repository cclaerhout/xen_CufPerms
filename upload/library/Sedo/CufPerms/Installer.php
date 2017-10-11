<?php
class Sedo_CufPerms_Installer
{
	public static $xenTables = array(
		'xf_user_field' => array(
			'sedo_perms_input_enable'		=> "TINYINT UNSIGNED NOT NULL DEFAULT 0",
			'sedo_perms_output_pp_enable'		=> "TINYINT UNSIGNED NOT NULL DEFAULT 0",
			'sedo_perms_output_ui_enable'		=> "TINYINT UNSIGNED NOT NULL DEFAULT 0",
			'sedo_perms_input_val'			=> "BLOB DEFAULT NULL",
			'sedo_perms_output_pp_val'		=> "BLOB DEFAULT NULL",
			'sedo_perms_output_ui_val'		=> "BLOB DEFAULT NULL"
		)
	);

	public static function install($addon)
	{
		$db = XenForo_Application::get('db');
		
		if(empty($addon) || $addon['version_id'] < 1)
		{
			//Force uninstall on fresh install
			self::uninstall();
			
			foreach(self::$xenTables as $tableName => $tableData)
			{
				foreach($tableData as $fieldName => $fieldAttr)
				{
					self::addColumnIfNotExist($db, $tableName, $fieldName, $fieldAttr);
				}
			}
		}

		if(!empty($addon['version_id']) && $addon['version_id'] < 10002)
		{
			foreach(self::$xenTables as $tableName => $tableData)
			{
				foreach($tableData as $fieldName => $fieldAttr)
				{
					self::changeColumnValueIfExist($db, $tableName, $fieldName, $fieldAttr);
				}
			}
		}
	}

	public static function uninstall()
	{
		$db = XenForo_Application::get('db');

		foreach(self::$xenTables as $tableName => $tableData)
		{
			foreach($tableData as $fieldName => $fieldAttr)
			{
				if ($db->fetchRow("SHOW COLUMNS FROM $tableName WHERE Field = ?", $fieldName))
				{
					$db->query("ALTER TABLE $tableName DROP $fieldName");
				}
			}
		}
	}
	
	public static function addColumnIfNotExist($db, $table, $field, $attr)
	{
		if ($db->fetchRow("SHOW COLUMNS FROM $table WHERE Field = ?", $field))
		{
			return;
		}
	 
		return $db->query("ALTER TABLE $table ADD $field $attr");
	}
	
	public static function changeColumnValueIfExist($db, $table, $field, $attr)
	{
		if (!$db->fetchRow("SHOW COLUMNS FROM $table WHERE Field = ?", $field))
		{
			return;
		}

		return $db->query("ALTER TABLE $table CHANGE $field $field $attr");
	}
}