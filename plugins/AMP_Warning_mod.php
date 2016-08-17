<?php
 
/**
 * Copyright (C) 2008-2010 FluxBB
 * based on code by Rickard Andersson copyright (C) 2002-2008 PunBB
 * License: http://www.gnu.org/licenses/gpl.html GPL version 2 or higher
 * Warning mod by adaur, 2011
 */

 
// Make sure no one attempts to run this script "directly"
if (!defined('PUN'))
	exit;
 
// Load the language file
if (file_exists(PUN_ROOT.'lang/'.$pun_user['language'].'/warning_mod.php'))
    require PUN_ROOT.'lang/'.$pun_user['language'].'/warning_mod.php';
else
    require PUN_ROOT.'lang/English/warning_mod.php';
 
// Tell admin_loader.php that this is indeed a plugin and that it is loaded
define('PUN_PLUGIN_LOADED', 1);
define('PLUGIN_VERSION', '1.0');
define('PLUGIN_URL', $_SERVER['REQUEST_URI']);
define('PLUGIN_URL_BASE', 'admin_loader.php?plugin=AMP_Warning_mod.php');
 
// Add a warning and ban if necessary
if (isset($_POST['add']))
{
	$tid = (isset($_GET['tid'])) ? intval($_GET['tid']) : 'NULL';
	$pid = (isset($_GET['pid'])) ? intval($_GET['pid']) : 'NULL';

	// First, we want a username AND a reason
	if ($_POST['username'] == '')
		message($lang_warning['No username']);
		
	if ($_POST['reason'] == '')
		message($lang_warning['No reason']);
		
	// Let's grab the user ID, the group ID and num_warnings from the DB using the username
	$result = $db->query('SELECT id, group_id, num_warnings FROM '.$db->prefix.'users WHERE username=\''.$db->escape($_POST['username']).'\'') or error('Unable to get informations from users', __FILE__, __LINE__, $db->error());
	list($user_id, $group_id, $num_warnings) = $db->fetch_row($result);
	
	// Check if something's wrong...
	if (!$db->num_rows($result))
		message($lang_warning['Bad username']);
		
	// Don't be silly :-)
	if ($user_id == $pun_user['id'])
		message($lang_warning['Error oneself']);
		
	// Don't warn an admod
	if ($group_id == PUN_ADMIN || $group_id == PUN_MOD)
		message($lang_warning['Error admmod']);
		
	// Is he already banned ?
	if ($num_warnings == $pun_config['o_warning_max'])
		message($lang_warning['User already banned']);
		
	// Count the warnings
	$warnings = $num_warnings + 1;
		
	// Increment his field
	$db->query('UPDATE '.$db->prefix.'users SET num_warnings=num_warnings+1, num_warnings_unread=num_warnings_unread+1 WHERE id='.$user_id) or error('Unable to update user', __FILE__, __LINE__, $db->error());
	
	// Fetch the subject if necessary
	if ($tid != 'NULL')
	{
		$result = $db->query('SELECT subject FROM '.$db->prefix.'topics WHERE id='.$tid) or error('Unable to fetch subject', __FILE__, __LINE__, $db->error());
		$subject = $db->result($result);
	}
	else
		$subject = 'NULL';
		
	if ($subject != 'NULL')
		$subject_db = '\''.$db->escape($subject).'\'';
	else
		$subject_db = 'NULL';
	
	// Finally, insert the data in the table
	$db->query('INSERT INTO '.$db->prefix.'warning (username, user_id, reason, num_warning, warning_by, warning_by_id, topic_id, post_id, time, topic_subject) VALUES (\''.$db->escape($_POST['username']).'\', '.$user_id.', \''.$db->escape($_POST['reason']).'\', '.$warnings.', \''.$db->escape($pun_user['username']).'\', '.$pun_user['id'].', '.$tid.', '.$pid.', '.time().', '.$subject_db.')') or error('Unable to insert data', __FILE__, __LINE__, $db->error());
	
	// Ban the user if necessary
	// Warning level 3 - 24 hours
	if ($warnings == 3)
	{
		$banned = 1;
		$t = time();
		$db->query('INSERT INTO '.$db->prefix.'bans (username, ip, email, message, expire, ban_creator) VALUES(\''.$db->escape($_POST['username']).'\', NULL, NULL, \'Automatische Sperre (24h)\', '.($t + 86400).', '.$pun_user['id'].')') or error('Unable to add ban', __FILE__, __LINE__, $db->error());
		
		// Regenerate the bans cache
		if (!defined('FORUM_CACHE_FUNCTIONS_LOADED'))
			require PUN_ROOT.'include/cache.php';

		generate_bans_cache();
	}
	// Warning level 4 - 48 hours
	elseif ($warnings == 4) {
		$banned = 1;
		$t = time();
		$db->query('INSERT INTO '.$db->prefix.'bans (username, ip, email, message, expire, ban_creator) VALUES(\''.$db->escape($_POST['username']).'\', NULL, NULL, \'Automatische Sperre (48h)\', '.($t + 172800).', '.$pun_user['id'].')') or error('Unable to add ban', __FILE__, __LINE__, $db->error());
		
		// Regenerate the bans cache
		if (!defined('FORUM_CACHE_FUNCTIONS_LOADED'))
			require PUN_ROOT.'include/cache.php';

		generate_bans_cache();
	}
	// Warning level 5 - one week
	elseif ($warnings == 5) {
		$banned = 1;
		$t = time();
		$db->query('INSERT INTO '.$db->prefix.'bans (username, ip, email, message, expire, ban_creator) VALUES(\''.$db->escape($_POST['username']).'\', NULL, NULL, \'Automatische Sperre (Woche)\', '.($t + 604800).', '.$pun_user['id'].')') or error('Unable to add ban', __FILE__, __LINE__, $db->error());
		
		// Regenerate the bans cache
		if (!defined('FORUM_CACHE_FUNCTIONS_LOADED'))
			require PUN_ROOT.'include/cache.php';

		generate_bans_cache();
	}
	// Warning level 6 - permanent
	elseif ($warnings == 6) {
		$banned = 1;
		$db->query('INSERT INTO '.$db->prefix.'bans (username, ip, email, message, expire, ban_creator) VALUES(\''.$db->escape($_POST['username']).'\', NULL, NULL, \'Automatische Dauersperre\', NULL, '.$pun_user['id'].')') or error('Unable to add ban', __FILE__, __LINE__, $db->error());
		
		// Regenerate the bans cache
		if (!defined('FORUM_CACHE_FUNCTIONS_LOADED'))
			require PUN_ROOT.'include/cache.php';

		generate_bans_cache();
	}
	else
		$banned = 0;
 
	// Display the admin navigation menu
	generate_admin_menu($plugin);

	$redirect_message = sprintf($lang_warning['Warning added to'], pun_htmlspecialchars($_POST['username'])).' '.sprintf($lang_warning['Warning numbers'], $warnings);
	
	if ($banned == 1)
		$redirect_message = $redirect_message.' '.$lang_warning['User banned'];
	
	redirect(PLUGIN_URL_BASE, $redirect_message);
 
}

// List the warnings for a member
elseif (isset($_POST['delete']))
{
	// First, we want a username
	if ($_POST['username'] == '')
		message($lang_warning['No username']);
		
	// Let's grab the user ID, the group ID and num_warnings from the DB using the username
	$result = $db->query('SELECT id, group_id, num_warnings FROM '.$db->prefix.'users WHERE username=\''.$db->escape($_POST['username']).'\'') or error('Unable to get informations from users', __FILE__, __LINE__, $db->error());
	list($user_id, $group_id, $num_warnings) = $db->fetch_row($result);
	
	// Check if something's wrong...
	if (!$db->num_rows($result))
		message($lang_warning['Bad username']);
		
	// Don't be silly :-)
	if ($user_id == $pun_user['id'])
		message($lang_warning['Error oneself']);
		
	// Don't warn an admod
	if ($group_id == PUN_ADMIN || $group_id == PUN_MOD)
		message($lang_warning['Error admmod']);
		
	// 0 warning => problem
	if ($num_warnings == 0)
		message($lang_warning['No warning to delete']);
		
	// Count the warnings
	$warnings = $num_warnings - 1;
 
	// Display the admin navigation menu
	generate_admin_menu($plugin);
 
?>
<div class="blocktable">
	<h2><span><?php echo $lang_warning['Delete a warning'].' - '.sprintf($lang_warning['Listing'], pun_htmlspecialchars($_POST['username'])) ?></span></h2>
	<div class="box">
		<div class="inbox">
			<table cellspacing="0">
			<thead>
				<tr>
					<th class="tcl" scope="col"><?php echo $lang_common['Username'] ?></th>
					<th class="tcl" scope="col"><?php echo $lang_warning['Reason'] ?></th>
					<th class="tcl" scope="col"><?php echo $lang_warning['For message'] ?></th>
					<th class="tc3" scope="col"><?php echo $lang_warning['Num warning'] ?></th>
					<th class="tcr" scope="col"><?php echo $lang_warning['Warning by'] ?></th>
					<th class="tc3" scope="col"><?php echo $lang_warning['Time'] ?></th>
					<th class="tc5" scope="col"><?php echo $lang_warning['Action'] ?></th>
				</tr>
			</thead>
			<tbody>
<?php
	$result = $db->query('SELECT id, username, reason, num_warning, warning_by, topic_id, post_id, topic_subject, time FROM '.$db->prefix.'warning WHERE username=\''.$db->escape($_POST['username']).'\' ORDER BY num_warning ASC') or error('Unable to fetch warning list', __FILE__, __LINE__, $db->error());
	
	if (!$db->num_rows($result))
		message($lang_common['Bad request']);
		
	$num_warnings = $db->num_rows($result);
	
	if ($num_warnings == $pun_config['o_warning_max'])
		$unban = '&amp;unban=1';
	else
		$unban = null;
	
	while ($warning_data = $db->fetch_assoc($result))
	{
		$topic = ($warning_data['topic_subject'] != '') ? '<a href="viewtopic.php?id='.$warning_data['topic_id'].'#p'.$warning_data['post_id'].'">'.pun_htmlspecialchars($warning_data['topic_subject']).'</a>' : $lang_warning['No info'];
?>
			<tr>
				<td class="tcl"><?php echo pun_htmlspecialchars($warning_data['username']) ?></td>
				<td class="tcl"><?php echo pun_htmlspecialchars($warning_data['reason']) ?></td>
				<td class="tcl"><?php echo $topic ?></td>
				<td class="tc3"><?php echo $warning_data['num_warning'] ?></td>
				<td class="tcl"><?php echo pun_htmlspecialchars($warning_data['warning_by']) ?></td>
				<td class="tcl"><?php echo format_time($warning_data['time']) ?></td>
				<td class="tcl"><?php echo '<a href="'.PLUGIN_URL.'&amp;delete_warning='.$warning_data['id'].$unban.'">'.$lang_admin_common['Remove'].'</a>' // Edit to add later ?></td>
			</tr>
			
<?php
	}
?>
			</tbody>
			</table>
			<?php if ($unban != null) echo '<br /><p><strong><font color="red">'.$lang_warning['Warning unban'].'</font></strong></p>' ?>
		</div>
	</div>
</div>
<?php
 
}

// Delete a warning for real
elseif ($_GET['delete_warning'])
{
	$id = intval($_GET['delete_warning']);
	
	$result = $db->query('SELECT username, user_id FROM '.$db->prefix.'warning WHERE id='.$id) or error('Unable to get informations from users', __FILE__, __LINE__, $db->error());
	list($username, $user_id) = $db->fetch_row($result);
	
	$db->query('UPDATE '.$db->prefix.'users SET num_warnings=num_warnings-1 WHERE id='.$user_id) or error('Unable to update user', __FILE__, __LINE__, $db->error());
	$db->query('DELETE FROM '.$db->prefix.'warning WHERE id='.$id) or error('Unable to fetch warning list', __FILE__, __LINE__, $db->error());
	
	// Unban if necessary
	if ($_GET['unban'])
	{
		$db->query('DELETE FROM '.$db->prefix.'bans WHERE username=\''.$db->escape($username).'\'') or error('Unable to delete ban', __FILE__, __LINE__, $db->error());

		// Regenerate the bans cache
		if (!defined('FORUM_CACHE_FUNCTIONS_LOADED'))
			require PUN_ROOT.'include/cache.php';

		generate_bans_cache();
		
		$redirect_message = $lang_warning['Warning deleted User unbanned'];
	}
	else
		$redirect_message = $lang_warning['Warning deleted'];
	
	redirect(PLUGIN_URL_BASE, $redirect_message);
}

// Change configuration
elseif (isset($_POST['config']))
{
	$warning_max = intval($_POST['max_warnings']);
	
	if ($warning_max < 2)
		$warning_max = 2;
	else if ($warning_max > 10)
		$warning_max = 10;
	
	$db->query('UPDATE '.$db->prefix.'config SET conf_value='.$warning_max.' WHERE conf_name=\'o_warning_max\'') or error('Unable to update config', __FILE__, __LINE__, $db->error());
	
	// Regenerate the config cache
	require_once PUN_ROOT.'include/cache.php';
	generate_config_cache();
	
	redirect(PLUGIN_URL_BASE, $redirect_message);
}

else // If not, we show the "Show text" form
{
	// Display the admin navigation menu
	generate_admin_menu($plugin);
	
	$tid = (isset($_GET['tid'])) ? intval($_GET['tid']) : '';
	$pid = (isset($_GET['pid'])) ? intval($_GET['pid']) : '';
	$uid = (isset($_GET['uid'])) ? intval($_GET['uid']) : '';
	
	if ($uid != '')
	{
		$result = $db->query('SELECT username FROM '.$db->prefix.'users WHERE id='.$uid) or error('Unable to get informations from users', __FILE__, __LINE__, $db->error());
		$username = $db->result($result);
	}
	else
		$username = '';
?>
	<div class="plugin blockform">
		<h2><span><?php echo $lang_warning['Warning mod'] ?></span></h2>
		<div class="box">
			<div class="inbox">
				<p><?php echo $lang_warning['Explanation 1'].'<ul style="margin-left:20px;"><li>3 Verwarnungen: 24h Bann</li><li>4 Verwarnungen: 48h Bann</li><li>5 Verwarnungen: eine Woche Bann</li><li>6 Verwarnungen: Dauerhafte Sperre</li></ul>' ?></p>
			</div>
		</div>
 
		<h2 class="block2"><span><?php echo $lang_warning['Settings'] ?></span></h2>
		<div class="box">
				<div class="inform">
					<fieldset>
						<legend><?php echo $lang_warning['Add a warning'] ?></legend>
						<div class="infldset">
							<table class="aligntop" cellspacing="0">
								<tr>
									<th scope="row">
										<form id="example" method="post" action="<?php echo pun_htmlspecialchars(PLUGIN_URL) ?>">
											<?php echo $lang_common['Username'] ?><div><input type="text" value="<?php echo pun_htmlspecialchars($username) ?>" maxlength="100" size="50" name="username"></div>
											<?php echo $lang_warning['Reason'] ?>
											<div><textarea cols="55" rows="5" name="reason"></textarea></div>
											<?php echo $lang_warning['For admin'] ?><div><input type="text" value="<?php echo $tid ?>" maxlength="100" size="10" name="topic"> <input type="text" value="<?php echo $pid ?>" maxlength="100" size="10" name="post"></div>
											<div><input type="submit" name="add" value="<?php echo $lang_common['Submit'] ?>" /></div>
										</form>
									</th>
								</tr>
							</table>
						</div>
						<legend><?php echo $lang_warning['Delete a warning'] ?></legend>
						<div class="infldset">
							<table class="aligntop" cellspacing="0">
								<tr>
									<th scope="row">
										<form id="example" method="post" action="<?php echo pun_htmlspecialchars(PLUGIN_URL) ?>">
											<?php echo $lang_common['Username'] ?><div><input type="text" value="" maxlength="100" size="50" name="username"></div>
											<div><input type="submit" name="delete" value="<?php echo $lang_common['Submit'] ?>" /></div>
										</form>
									</th>
								</tr>
							</table>
						</div>
						<!--<legend><?php //echo $lang_warning['Change configuration'] ?></legend>
						<div class="infldset">
							<table class="aligntop" cellspacing="0">
								<tr>
									<th scope="row">
										<form id="example" method="post" action="<?php //echo pun_htmlspecialchars(PLUGIN_URL) ?>">
											<?php //echo $lang_warning['Max warnings info'] ?><div><input type="text" value="<?php //echo $pun_config['o_warning_max'] ?>" maxlength="2" size="1" name="max_warnings"></div>
											<div><input type="submit" name="config" value="<?php //echo $lang_common['Submit'] ?>" /></div>
										</form>
									</th>
								</tr>
							</table>
						</div>-->
					</fieldset>

		</div>
	</div>
<?php
 
}