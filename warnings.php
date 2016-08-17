<?php

/**
 * Copyright (C) 2008-2010 FluxBB
 * based on code by Rickard Andersson copyright (C) 2002-2008 PunBB
 * License: http://www.gnu.org/licenses/gpl.html GPL version 2 or higher
 * Warning mod by adaur, 2011
 */

define('PUN_ROOT', dirname(__FILE__).'/');
define('PUN_ACTIVE_PAGE', 'warnings');
require PUN_ROOT.'include/common.php';
require PUN_ROOT.'include/parser.php';

$user_id = intval($_GET['uid']);

if ($pun_user['num_warnings_unread'] > 0 && $user_id == $pun_user['id'])
	$db->query('UPDATE '.$db->prefix.'users SET num_warnings_unread=0 WHERE id='.$pun_user['id']) or error('Unable to update num_warnings_unread', __FILE__, __LINE__, $db->error());

$result = $db->query('SELECT username FROM '.$db->prefix.'warning WHERE user_id='.$user_id) or error('Unable to fetch warning list', __FILE__, __LINE__, $db->error());
	
if ($db->num_rows($result))
{
	$username = $db->result($result);
	$page_title = array(pun_htmlspecialchars($pun_config['o_board_title']), sprintf($lang_warning['Listing'], pun_htmlspecialchars($username)));
	require PUN_ROOT.'header.php';
	
	$result = $db->query('SELECT username, reason, num_warning, warning_by, topic_id, post_id, topic_subject, time FROM '.$db->prefix.'warning WHERE user_id='.$user_id.' ORDER BY num_warning ASC') or error('Unable to fetch warning list', __FILE__, __LINE__, $db->error());
		
	if (!$db->num_rows($result))
		message($lang_common['Bad request']);
			
	$num_warnings = $db->num_rows($result);
	?>
	<h2><span><?php echo sprintf($lang_warning['Listing'], pun_htmlspecialchars($username)) ?></span></h2>
	<div class="blockform">
	<div class="box">
			<div class="inform">
				<fieldset>
					<legend><?php echo $lang_warning['Information'] ?></legend>
					<div class="infldset">
						<p class="clearb"><?php echo sprintf($lang_warning['Explanation 2'], $pun_config['o_warning_max']) ?></p>
						<?php if ($num_warnings == $pun_config['o_warning_max']) echo '<p class="clearb"><strong><font color="red">'.$lang_warning['Banned'].'</font></strong></p>' ?>
					</div>
				</fieldset>
			</div>
	</div>
	</div>
	
	<br />
	
	<div id="users1" class="blocktable">
		<div class="box">
			<div class="inbox">
				<table cellspacing="0">
				<thead>
					<tr>
						<th class="tcl" scope="col"><?php echo $lang_common['Username'] ?></th>
						<th class="tcl" scope="col"><?php echo $lang_warning['Reason'] ?></th>
						<th class="tcl" scope="col"><?php echo $lang_warning['For message'] ?></th>
						<th class="tc3" scope="col"><?php echo $lang_warning['Num warning'] ?></th>
						<th class="tcl" scope="col"><?php echo $lang_warning['Warning by'] ?></th>
						<th class="tc3" scope="col"><?php echo $lang_warning['Time'] ?></th>
					</tr>
				</thead>
				<tbody>
	<?php		
		while ($warning_data = $db->fetch_assoc($result))
		{
			 $topic = ($warning_data['topic_subject'] != '') ? '<a href="viewtopic.php?pid='.$warning_data['post_id'].'#p'.$warning_data['post_id'].'">'.pun_htmlspecialchars($warning_data['topic_subject']).'</a>' : $lang_warning['No info'];
	?>
				<tr>
					<td class="tcl"><?php echo pun_htmlspecialchars($warning_data['username']) ?></td>
					<td class="tcl"><?php echo parse_message($warning_data['reason'], 0) ?></td>
					<td class="tcl"><?php echo $topic ?></td>
					<td class="tc3"><?php echo $warning_data['num_warning'] ?></td>
					<td class="tcl"><?php echo pun_htmlspecialchars($warning_data['warning_by']) ?></td>
					<td class="tcl"><?php echo format_time($warning_data['time']) ?></td>
				</tr>
				
	<?php
		}
	?>
				</tbody>
				</table>
			</div>
		</div>
	</div>
	<?php
}
else
	message($lang_warning['No warnings']);

require PUN_ROOT.'footer.php';