<?php
/**
 * RedBean Setup
 * Helper class to quickly setup RedBean for you.
 * 
 * @file 			RedBean/Setup.php
 * @desc			Helper class to quickly setup RedBean for you
 * @author			Gabor de Mooij and the RedBeanPHP community
 * @license			BSD/GPLv2
 *
 * copyright (c) G.J.G.T. (Gabor) de Mooij and the RedBeanPHP Community
 * This source file is subject to the BSD/GPLv2 License that is bundled
 * with this source code in the file license.txt.
 */
class RedBean_Setup {
	/**
	 * This method checks the DSN string.
	 * 
	 * @throws RedBean_Exception_NotImplemented
	 * 
	 * @param string $dsn
	 * 
	 * @return boolean $true
	 */
	private static function checkDSN($dsn) {
		if (!preg_match('/^(mysql|sqlite|pgsql|cubrid|oracle):/',strtolower(trim($dsn)))) trigger_error('Unsupported DSN');
		return true;
	}
	/**
	 * Initializes the database and prepares a toolbox.
	 *
	 * @param  string|PDO $dsn      Database Connection String (or PDO instance)
	 * @param  string     $username Username for database
	 * @param  string     $password Password for database
	 * @param  boolean    $frozen   Start in frozen mode?
	 *
	 * @return RedBean_ToolBox $toolbox
	 */
	public static function kickstart($dsn, $username=NULL, $password=NULL, $frozen=false ) {
		if ($dsn instanceof PDO) {
			$db = new RedBean_Driver_PDO($dsn);
			$dsn = $db->getDatabaseType();
		}
		else {
			self::checkDSN($dsn);
			if (strpos($dsn, 'oracle') === 0) 
				$db = new RedBean_Driver_OCI($dsn,$username,$password);	
			else
				$db = new RedBean_Driver_PDO($dsn,$username,$password);			
		}
		$adapter = new RedBean_Adapter_DBAdapter($db);
		if (strpos($dsn,'pgsql')===0) {
			$writer = new RedBean_QueryWriter_PostgreSQL($adapter);
		} else if (strpos($dsn,'sqlite')===0) {
			$writer = new RedBean_QueryWriter_SQLiteT($adapter);
		} else if (strpos($dsn,'cubrid')===0) {
			$writer = new RedBean_QueryWriter_CUBRID($adapter);
		} else if (strpos($dsn,'oracle')===0) { $writer = new RedBean_QueryWriter_Oracle($adapter); }	//layout important for unit test - this line cannot always be tested. 	
		else {
			$writer = new RedBean_QueryWriter_MySQL($adapter);
		}
		$redbean = new RedBean_OODB($writer);
		if ($frozen) $redbean->freeze(true);
		$toolbox = new RedBean_ToolBox($redbean,$adapter,$writer);
		return $toolbox;
	}
}