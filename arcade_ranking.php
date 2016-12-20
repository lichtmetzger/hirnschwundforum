<?php

define('PUN_ROOT', './');
require PUN_ROOT.'include/common.php';
require PUN_ROOT.'lang/'.$pun_user['language'].'/arcade.php';

$page_title = array(pun_htmlspecialchars($pun_config['o_board_title']), pun_htmlspecialchars($lang_arcade['Arcade Scores']));

if ($pun_user['g_read_board'] == '0')
	message($lang_common['No view']);
	
if ($pun_user['is_guest'] && $pun_config['arcade_allow_guests'] != '1')
	message($lang_common['No permission']);

require PUN_ROOT.'header.php';

if(isset($_GET['id']) && !empty($_GET['id']))
{
	$id = intval($_GET['id']);
	$sql = 'SELECT * FROM '.$db->prefix.'arcade_games WHERE game_id = '.intval($id);
	$query = $db->query($sql) or error("Impossible to select the games ", __FILE__, __LINE__, $db->error());
	$line = $db->fetch_assoc($query);
	$game_name = $line['game_name'];
?>

<div class="blockform">
	<h2><?php echo ''.$lang_arcade['Highscore List'].' <b>'.$line['game_name'].'</b>' ?></h2>
	<div class="box">
			<div class="inbox">
			<table cellspacing="0" style="padding: 0px 0px 0px 0px; margin-top: 0px; margin-left: 0px; margin-right: 0px; margin-bottom: 0px; border:none; ">
			<tr>
			<th><b><?php echo $lang_arcade['Position']; ?></b></th>
			<th><b><?php echo $lang_arcade['Name']; ?></b></th>
			<th><b><?php echo $lang_arcade['Score']; ?></b></th>
			<th><b><?php echo $lang_arcade['Date']; ?></b></th>
			</tr>
			
<?php
		// Added "GROUP BY rank_player", fix for MySQL 4
		$sql = 'SELECT * FROM '.$db->prefix.'arcade_ranking, '.$db->prefix.'users WHERE rank_game = \''.$db->escape($line['game_filename']).'\' AND '.$db->prefix.'users.id = '.$db->prefix.'arcade_ranking.rank_player GROUP BY rank_player ORDER BY rank_score DESC';
		$query = $db->query($sql) or error("Impossible to select player and score ", __FILE__, __LINE__, $db->error());
		$arcade_ranking = '';
		$i = 1;
		while($line = $db->fetch_assoc($query))
		{
			$arcade_ranking .= '
			<tr>		
				<td class="tcr" style="text-align:center; width:10%;">'.$i.'</td>
				<td class="tcr" style="text-align:center;"><a href="arcade_userstats.php?id='.$line['id'].'" title="'.$lang_arcade['view_stats'].'">'.pun_htmlspecialchars($line['username']).'</a></td>
				<td class="tcr" style="text-align:center;">'.$line['rank_score'].'</td>						
				<td class="tcr" style="text-align:center;">'.format_time($line['rank_date']).'</td>	
			</tr>';
			$i++;
		}
			if($i > 1)
				echo $arcade_ranking;
			else	
				echo '<tr><td>'.$lang_arcade['No Highscore'].'</td></tr>';
?>
			
			</table>
			</div>
	</div>
</div>
<div class="linksb">
	<div class="inbox" style="text-align:center;>
	<p class="pagelink conl"></p>
		<ul><a href="arcade.php" title="<?php echo $lang_arcade['Back to'] ?>"><?php echo $lang_arcade['Back to'] ?></a>&#160;&raquo;&#160;<a href="arcade_play.php?id=<?php echo $id ?>" ><?php echo ''.$lang_arcade['Play'].' <i>"'.$game_name.'"</i>' ?></a></ul>
		<div class="clearer"></div>
	</div>
</div>
<?php
}
else
	message($lang_common['Bad request']);
	
require PUN_ROOT.'footer.php';