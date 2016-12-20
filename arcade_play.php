<?php

define('PUN_ROOT', './');
require PUN_ROOT.'include/common.php';
require PUN_ROOT.'lang/'.$pun_user['language'].'/arcade.php';

if (!function_exists('generate_config_cache'))
	require PUN_ROOT.'include/cache.php';
	
if ($pun_user['is_guest'] && $pun_config['arcade_allow_guests'] != '1')
	message($lang_common['No permission']);

$game_name = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($game_name < 1)
	message($lang_common['Bad request']);

$result = $db->query('SELECT * FROM '.$db->prefix.'arcade_games WHERE game_id = '.$game_name) or error('Unable to fetch games', __FILE__, __LINE__, $db->error());
// some servers return an error: http://de.php.net/mysql_num_rows , if so, try the following ...
// $result = $db->query('SELECT * FROM '.$db->prefix.'arcade_games WHERE game_id = '.$game_name) or die ("<p class=err>Error - Query failed: ".mysql_error()."</p>");

// if(mysql_num_rows($result) <= 0)
$line = $db->fetch_assoc($result);
if ($line['game_id'] < 1)
	message($lang_common['Bad request']);


$page_title = array(pun_htmlspecialchars($pun_config['o_board_title']), pun_htmlspecialchars($lang_arcade['Arcade']), pun_htmlspecialchars($line['game_name']));

require PUN_ROOT.'header.php';

?>

<div class="blockform">
	<h2><span><b><?php echo $line['game_name'],' - ' ?></b> <?php echo $lang_arcade['How to play'] ?></span></h2>
	<div class="box">
		<div class="inbox" style="padding:5px;">
			<ul>
			<li><?php echo $line['game_desc']?></li>
			</ul>
		</div>
	</div>
</div>
<?php

// Set games played +1$sql = 'UPDATE '.$db->prefix.'arcade_games SET game_played = game_played+1 WHERE game_id = '.$game_name;$query = $db->query($sql) or error("Impossible to update game_played", __FILE__, __LINE__, $db->error());
// Find Highscore of the game
$sql2 = 'SELECT rank_player, rank_score, username, id FROM '.$db->prefix.'arcade_ranking, '.$db->prefix.'users WHERE rank_game = \''.$db->escape($line['game_filename']).'\' AND '.$db->prefix.'users.id = '.$db->prefix.'arcade_ranking.rank_player ORDER BY rank_score DESC LIMIT 1';
$query = $db->query($sql2) or error("Impossible to find the topscore of each game", __FILE__, __LINE__, $db->error());
$result2 = $db->fetch_assoc($query);

// Find Best score of user
$result = $db->query('SELECT rank_score, game_id, game_name, game_width, game_height, game_played FROM '.$db->prefix.'arcade_ranking, '.$db->prefix.'arcade_games WHERE rank_game = \''.$db->escape($line['game_filename']).'\' AND rank_player = '.$pun_user['id']) or error('Unable to fetch scores info', __FILE__, __LINE__, $db->error());

// if(mysql_num_rows($result) <= 0)
// Fix for MySQL 4
$resultatt = $db->fetch_assoc($result);
if($resultatt <= 0)
{

// We have no highscore
?>
	<div class="blockform">
	<h2>
	<table cellspacing="0" style="padding: 0px 0px 0px 0px; margin-top: 0px; margin-left: 0px; margin-right: 0px; margin-bottom: 0px; border:none; ">
	<td width="33%" cellspacing="0" style="padding: 0px 0px 0px 0px; margin-top: 0px; margin-left: 0px; margin-right: 0px; margin-bottom: 0px; border:none; ">
		<span><?php echo $lang_arcade['Not played'] ?></span>
	</td>
	<td width="33%" align="middle" cellspacing="0" style="padding: 0px 0px 0px 0px; margin-top: 0px; margin-left: 0px; margin-right: 0px; margin-bottom: 0px; border:none; ">
		<span align="right"><?php echo $lang_arcade['Top highscore'] ?> <strong> <?php echo $result2['rank_score'] ?> </strong> <?php if($result2['rank_score'] > 0) echo $lang_arcade['by'], ' '?> <i> <?php echo pun_htmlspecialchars($result2['username'])?></i></span>
	</td>	
	<td width="33%" align="right" cellspacing="0" style="padding: 0px 0px 0px 0px; margin-top: 0px; margin-left: 0px; margin-right: 0px; margin-bottom: 0px; border:none; ">
		<span align="right"><?php echo $lang_arcade['played']?> <strong> <?php echo $line['game_played'] ?><strong></span>
	</td>
	</table>
	</h2>
	
<?php
}
else
{
	$line2 = $db->fetch_assoc($result);
	
// We have a highscore
?>

<div class="blockform">
	<h2>
	<table cellspacing="0" style="padding: 0px 0px 0px 0px; margin-top: 0px; margin-left: 0px; margin-right: 0px; margin-bottom: 0px; border:none; ">
	<td width="33%" cellspacing="0" style="padding: 0px 0px 0px 0px; margin-top: 0px; margin-left: 0px; margin-right: 0px; margin-bottom: 0px; border:none; ">
		<span><?php echo $lang_arcade['Your highscore'],': ' ?> <strong><?php echo $line2['rank_score'] ?></strong></span>
	</td>
	<td width="33%" align="middle" cellspacing="0" style="padding: 0px 0px 0px 0px; margin-top: 0px; margin-left: 0px; margin-right: 0px; margin-bottom: 0px; border:none; ">
		<span align="right"><?php echo $lang_arcade['Top highscore'] ?> <strong> <?php echo $result2['rank_score'] ?> </strong> <?php echo $lang_arcade['by'], ' '?> <i> <?php echo pun_htmlspecialchars($result2['username'])?></i></span>
	</td>	
	<td width="33%" align="right" cellspacing="0" style="padding: 0px 0px 0px 0px; margin-top: 0px; margin-left: 0px; margin-right: 0px; margin-bottom: 0px; border:none; ">
		<span align="right"><?php echo $lang_arcade['played']?> <strong> <?php echo $line['game_played'] ?><strong></span>
	</td>
	</table>
	</h2>

<?php
}
?>
	<div class="box">
		<div class="inbox" style="padding:5px;text-align:center;">
			<embed name="arcade_games_punbb" src="./games/<?php echo $line['game_filename'] ?>.swf" width=<?php echo $line['game_width']?> height=<?php echo $line['game_height']?> quality="high" menu="false" swliveconnect="true" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash"></embed>
			<noembed><?php echo str_replace('%version%','<a href="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash" target="_blank">Flash 5</a>',$lang_arcade['Need Plugin To Play']); ?></noembed>
		</div>
		<div class="inbox" style="padding:5px;text-align:center;">
			<a href="arcade.php" title="<?php echo $lang_arcade['Back to'] ?>"><?php echo $lang_arcade['Back to'] ?></a> - <a href="arcade_play.php?id=<?php echo $line['game_id'] ?>" title="<?php echo $lang_arcade['Restart game'] ?>"><?php echo $lang_arcade['Restart game'] ?></a> - <a href="arcade_ranking.php?id=<?php echo $line['game_id'] ?>" title="<?php echo $lang_arcade['View Highscore'] ?>"><?php echo $lang_arcade['View Highscore'] ?></a>
		</div>
	</div>
</div>

<?php
require PUN_ROOT.'footer.php';