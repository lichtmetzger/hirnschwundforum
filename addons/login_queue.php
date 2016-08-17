<?php

/**
 * Provides everything needed to manage and use a login queue for FluxBB 1.5.x.
 * The login queue will store each login attempt in a new row in the database
 * and process the entries one at at time, with a forced delay between them.
 * The reason for this is to make brute-force attacks on the login system almost impossible.
 * The database table will be created the first time a user logs in.
 *
 * @author Chris98 (https://fluxbb.org/forums/profile.php?id=59229)
 * @date 23/01/2015
 */

class addon_login_queue extends flux_addon
{
    function register($manager)
    {
            $manager->bind('login_before_validation', [$this, 'hook_login_before_validation']);
    }

    function hook_login_before_validation()
    {
		global $db, $db_type;

			// This has not been installed yet. Let's create the login queue and go!
		if (!$db->table_exists('login_queue'))
		{
			define('PUN_DEBUG', 1);
			$schema = array
			(
				'FIELDS'		=> array(
					'id'		=> array(
						'datatype'		=> 'SERIAL',
						'allow_null'	=> false,
					),
					'last_checked'		=> array(
						'datatype'		=> 'timestamp',
						'allow_null'	=> false,
						'default'		=> 'CURRENT_TIMESTAMP'
					),
					'ip_address'		=> array(
						'datatype'		=> 'varchar(39)',
						'allow_null'	=> false,
					),
					'username'		=> array(
						'datatype'		=> 'varchar(100)',
						'allow_null'	=> false,
					),				
				),
				'PRIMARY KEY'	=> array('id'),
				'UNIQUE KEYS'	=> array(
					'ip_address_idx'	=> array('ip_address')
				),
			);

			$db->create_table('login_queue', $schema) or error('Unable to create login queue table', __FILE__, __LINE__, $db->error());
		}

        $form_username = pun_trim($_POST['req_username']);
		$form_password = pun_trim($_POST['req_password']);
		$save_pass = isset($_POST['save_pass']);

		define('MAX_ATTEMPTS', 30); 
		define('MAX_PER_USER', 5);
		define('ATTEMPT_DELAY', 1000);
		define('TIMEOUT', 5000);

		$result = $db->query('SELECT COUNT(*) AS overall, COUNT(IF(username = \''.$db->escape($form_username).'\', TRUE, NULL)) AS user FROM '.$db->prefix.'login_queue WHERE last_checked > NOW() - INTERVAL '.(TIMEOUT * 1000).' MICROSECOND') or error('Unable to fetch queue info', __FILE__, __LINE__, $db->error()); 
		$count = $db->fetch_assoc($result);
	
		if (!$count)
			message('Failed to query queue size');
		else if ($count['overall'] >= MAX_ATTEMPTS || $count['user'] >= MAX_PER_USER)
			message('The login queue size has been exceeded.');	

		$db->query('INSERT INTO '.$db->prefix.'login_queue (ip_address, username) VALUES (\''.$db->escape(get_remote_address()).'\', \''.$db->escape($form_username).'\')') or message('Your IP Address has already been added to the login queue.'); 
		$attempt = $db->insert_id();
	
		while (!$this->check_queue($form_username, $attempt, $db))
			usleep(250 * 1000);

		$username_sql = ($db_type == 'mysql' || $db_type == 'mysqli' || $db_type == 'mysql_innodb' || $db_type == 'mysqli_innodb') ? 'username=\''.$db->escape($form_username).'\'' : 'LOWER(username)=LOWER(\''.$db->escape($form_username).'\')';
		$result = $db->query('SELECT * FROM '.$db->prefix.'users WHERE '.$username_sql) or error('Unable to fetch user info', __FILE__, __LINE__, $db->error());
		$cur_user = $db->fetch_assoc($result);

			//Force delay between logins, remove dead attempt
		usleep(ATTEMPT_DELAY * 1000);
		
		$db->query('DELETE FROM '.$db->prefix.'login_queue WHERE id = '.$attempt.' OR last_checked < NOW() - INTERVAL '.(TIMEOUT * 1000).' MICROSECOND') or error('Unable to delete data from login queue', __FILE__, __LINE__, $db->error());
    }

	function check_queue($form_username, $attempt, $db)
	{
		$result = $db->query('SELECT id FROM '.$db->prefix.'login_queue WHERE last_checked > NOW() - INTERVAL '.(TIMEOUT * 1000).' MICROSECOND AND username = \''.$db->escape($form_username).'\' ORDER BY id ASC LIMIT 1') or error('Unable to get login attempt data', __FILE__, __LINE__, $db->error()); 
		$id = $db->result($result);

		$db->query('UPDATE '.$db->prefix.'login_queue SET last_checked = CURRENT_TIMESTAMP WHERE id = '.$attempt.' LIMIT 1') or error('Unable to update queue', __FILE__, __LINE__, $db->error()); 
		return ($id == $attempt) ? true : false;
	}
}