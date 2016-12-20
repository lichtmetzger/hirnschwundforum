<?php

define('PUN_ROOT', './');
require PUN_ROOT.'include/common.php';
require PUN_ROOT.'lang/'.$pun_user['language'].'/arcade.php';

$page_title = array(pun_htmlspecialchars($pun_config['o_board_title']), pun_htmlspecialchars($lang_arcade['Arcade Userstats']));

if ($pun_user['g_read_board'] == '0')
	message($lang_common['No view']);
	
if ($pun_user['is_guest'] && $pun_config['arcade_allow_guests'] != '1')
	message($lang_common['No permission']);

require PUN_ROOT.'header.php';

if(isset($_GET['id']) && !empty($_GET['id']))
{
	$id = intval($_GET['id']);
	$sql = 'SELECT username FROM '.$db->prefix.'users WHERE id = '.intval($id).'';
	$query = $db->query($sql) or error("Impossible to select player ", __FILE__, __LINE__, $db->error());
	$line = $db->fetch_assoc($query);

?>

<div class="blockform">
	<h2><?php echo $lang_arcade['top_highscores']?><b><?php echo pun_htmlspecialchars($line['username']) ?></b></h2>
	<div class="box">
			<div class="inbox">
			<table cellspacing="0" style="padding: 0px 0px 0px 0px; margin-top: 0px; margin-left: 0px; margin-right: 0px; margin-bottom: 0px; border:none; ">
			<tr>
			<th><b></b></th>
			<th><b><?php echo $lang_arcade['Games']?></b></th>
			<th><b><?php echo $lang_arcade['highscores']?></b></th>
			<th><b><?php echo $lang_arcade['Date']?></b></th>
			</tr>
			
<?php	// Added ORDER BY to solve the MySQL4 Problem
		$sql = 'SELECT * FROM '.$db->prefix.'arcade_ranking, '.$db->prefix.'arcade_games WHERE rank_topscore = 1 and rank_player = '.intval($id).' and game_filename = rank_game Group BY rank_game ORDER BY rank_date DESC';
		$query = $db->query($sql) or error("Impossible to select player topscores", __FILE__, __LINE__, $db->error());
		$top_scores = '';
		$i = 1;
		while($line = $db->fetch_assoc($query))
		{
			$top_scores .= '
			<tr>
				<td class="tcr" style="text-align:center; width:10%;">'.$i.'</td>
				<td class="tcr" style="text-align:center;"><a href="arcade_play.php?id='.$line['game_id'].'">'.$line['game_name'].'</a></td>
				<td class="tcr" style="text-align:center;">'.$line['rank_score'].'</td>						
				<td class="tcr" style="text-align:center;">'.format_time($line['rank_date']).'</td>	
			</tr>';
			$i++;
		}
			if($i > 1)
				echo $top_scores;
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
		<ul><a href="arcade.php" title="<?php echo $lang_arcade['Back to'] ?>"><?php echo $lang_arcade['Back to'] ?></a></ul>
		<div class="clearer"></div>
	</div>
</div>
<?php
}
else
	message($lang_common['Bad request']);
	
require PUN_ROOT.'footer.php';