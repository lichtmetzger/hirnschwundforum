<?php
/**
 * Copyright (C) 2014 StrongholdNation (http://www.strongholdnation.co.uk)
 * License: http://www.gnu.org/licenses/gpl.html GPL version 2 or higher
 */

 define('PUN_ROOT', dirname(__FILE__).'/');
require PUN_ROOT.'include/common.php';

if ($pun_user['g_read_board'] == '0')
	message($lang_common['No view'], false, '403 Forbidden');


// Load the userlist.php language file
require PUN_ROOT.'lang/'.$pun_user['language'].'/online.php';

// Load the search.php language file
require PUN_ROOT.'lang/'.$pun_user['language'].'/search.php';


if ($pun_user['g_view_users'] == '0')
	message($lang_common['No permission'], false, '403 Forbidden');

$page_title = array(pun_htmlspecialchars($pun_config['o_board_title']), $lang_online['viewing online']);
	
define('PUN_ACTIVE_PAGE', 'online');
define('PUN_ALLOW_INDEX', 1);
require PUN_ROOT.'header.php';
?>
<div class="linkst">
	<div class="inbox">
		<p class="pagelink"><?php echo $paging_links ?></p>
		<div class="clearer"></div>
	</div>
</div>

<div id="users1" class="blocktable">
	<h2><span><?php echo $lang_online['users online'] ?></span></h2>
	<div class="box">
		<div class="inbox">
			<table cellspacing="0">
			<thead>
				<tr>
					<th class="tcl" scope="col"><?php echo $lang_common['Username'] ?></th>
					<th class="tcl" scope="col"><?php echo $lang_online['user currently'] ?></th>
					<th class="tcl" scope="col"><?php echo $lang_online['last active'] ?></th>					
				</tr>
			</thead>
			<tbody>
<?php
$bots = array();

$result = $db->query('SELECT user_id, ident, currently, logged FROM '.$db->prefix.'online WHERE idle = 0', true) or error('Unable to fetch online list', __FILE__, __LINE__, $db->error());
while ($pun_user_online = $db->fetch_assoc($result))
{

	if (strpos($pun_user_online['ident'], '[Bot]') !== false)
	{
		$arr_o_name = explode('[Bot]', $pun_user_online['ident']);
		if (empty($bots[$arr_o_name[1]])) $bots[$arr_o_name[1]] = 1;
			else ++$bots[$arr_o_name[1]];
	
		foreach ($bots as $online_name => $online_id)
		   $ident = "\n\t\t\t\t".pun_htmlspecialchars($online_name.' [Bot]');
	}
	else
	{
		if ($pun_user_online['user_id'] == 1)
			$ident = $lang_common['Guest'];
		else
			$ident = $pun_user_online['ident'];
	}	
?>
		<tr>
					<td class="tcl"><?php if ($pun_user_online['user_id'] != '1')
						echo '<a href="profile.php?id='.$pun_user_online['user_id'].'">'.pun_htmlspecialchars($ident).'</a>';
					else 
						echo pun_htmlspecialchars($ident); ?></td>
					<td class="tcr"><?php echo generate_user_location($pun_user_online['currently']); ?></td>
					<td class="tcr"><?php echo format_time_difference($pun_user_online['logged'], $lang_online); ?></td>
				</tr>

<?php
}
?>
			</tbody>
			</table>
		</div>
	</div>
</div>

<div class="linksb">
	<div class="inbox">
		<p class="pagelink"><?php echo $paging_links ?></p>
		<div class="clearer"></div>
	</div>
</div>
<?php
require PUN_ROOT.'footer.php';