<?php

define('PUN_ROOT', './');
require PUN_ROOT.'include/common.php';
require PUN_ROOT.'lang/'.$pun_user['language'].'/arcade.php';
if ($pun_user['is_guest'])
	message($lang_arcade['register']);

// Recover the game name and the score
$game_name = (isset($_POST['game_name'])) ? $_POST['game_name'] : $_POST['gname'];

// str_replace strips all spaces present in the score string
$score = (isset($_POST['score'])) ? str_replace(" ", "", $_POST['score']) : $_POST['gscore'];

// This is a fix for var "score" which is sent as "Score" and not as "score" like in game "Easter Egg Catch"
if (empty($score))
{
	// str_replace strips all spaces present in the Score string
	$score = (isset($_POST['Score'])) ? str_replace(" ", "", $_POST['Score']) : $_POST['gscore'];

	if (!is_numeric($score))
		message($lang_common['Bad request']);
}

if (!is_numeric($score))
	message($lang_common['Bad request']);

$topscore = 0;
$now = time();

if (!empty($game_name) && !empty($score))
{
	// Find Topscore	
	$query = $db->query('SELECT rank_topscore, rank_score FROM '.$db->prefix.'arcade_ranking, '.$db->prefix.'users WHERE rank_player = '.$db->prefix.'users.id AND rank_game = \''.$db->escape($game_name).'\' ORDER BY rank_score DESC LIMIT 1') or error("Impossible to select topscore.", __FILE__, __LINE__, $db->error());
	$line = $db->fetch_assoc($query);
	if ($line['rank_topscore'] == 1 && $line['rank_score'] < $score)
	{
		$db->query('UPDATE '.$db->prefix.'arcade_ranking SET rank_topscore = '.$topscore.' WHERE rank_game = \''.$db->escape($game_name).'\'') or error("Impossible to update the topscore", __FILE__, __LINE__, $db->error());
		$topscore = 1;
	}
	else if ($line['rank_topscore'] >= 0 && $line['rank_score'] <= $score)
	{
		$topscore = 1;
	}
	else
	{
		$topscore = 0;
	}

	$query = $db->query('SELECT * FROM '.$db->prefix.'arcade_ranking WHERE rank_player = '.$pun_user['id'].' AND rank_game = \''.$db->escape($game_name).'\'') or error("Impossible to select the user and game", __FILE__, __LINE__, $db->error());

	if ($db->num_rows($query))
	{
		$line = $db->fetch_assoc($query);
		if ($line['rank_score'] <= $score)
		{
			// Update new highscore
			$db->query('UPDATE '.$db->prefix.'arcade_ranking SET rank_score = '.$score.', rank_date = '.$now.', rank_topscore = '.$topscore.' WHERE rank_player = '.$pun_user['id'].' AND rank_game = \''.$db->escape($game_name).'\'') or error("Impossible to update new highscore", __FILE__, __LINE__, $db->error());
			
			// End the transaction
			$db->end_transaction();

			$query = $db->query('SELECT game_id FROM '.$db->prefix.'arcade_games WHERE game_filename = \''.$db->escape($game_name).'\'') or error("Impossible to select the game", __FILE__, __LINE__, $db->error());
			$gameid = $db->fetch_assoc($query);
			echo '<script type="text/javascript">window.location=\'arcade_ranking.php?id='.$gameid['game_id'].'\'</script>';
		}
		else
		{
			// No new highscore
			$sql = 'SELECT game_id FROM '.$db->prefix.'arcade_games WHERE game_filename = \''.$db->escape($game_name).'\'';
			$query = $db->query($sql) or error("Impossible to select the game", __FILE__, __LINE__, $db->error());
			$gameid = $db->fetch_assoc($query);
			echo '<script type="text/javascript">window.location=\'arcade_play.php?id='.$gameid['game_id'].'\'</script>';
		}
	}
	else
	{
		// Is there a score?
		$query = $db->query('SELECT rank_score, rank_topscore FROM '.$db->prefix.'arcade_ranking WHERE rank_game = \''.$db->escape($game_name).'\' ORDER BY rank_score DESC, rank_topscore DESC') or error("Impossible to select the topscore", __FILE__, __LINE__, $db->error());
		$line = $db->fetch_assoc($query);
		if ($line['rank_score'] <= 0 && $line['rank_topscore'] <= 0)
		{
			$topscore = 1;
		}

		// Add new Highscore
		$sql='INSERT INTO '.$db->prefix.'arcade_ranking (rank_game, rank_player, rank_score, rank_topscore, rank_date) VALUES (\''.$db->escape($game_name).'\', \''.$pun_user['id'].'\', \''.$score.'\', \''.$topscore.'\', \''.$now.'\')';
		$db->query($sql) or error("Impossible to insert the new score", __FILE__, __LINE__, $db->error());

		// End the transaction
		$db->end_transaction();
		
		$sql = 'SELECT game_id FROM '.$db->prefix.'arcade_games WHERE game_filename = \''.$db->escape($game_name).'\'';
		$query = $db->query($sql) or error("Impossible to select the game", __FILE__, __LINE__, $db->error());
		$gameid = $db->fetch_assoc($query);
		echo '<script type="text/javascript">window.location=\'arcade_ranking.php?id='.$gameid['game_id'].'\'</script>';
	}
}

else
{
	message($lang_common['No permission']);
}

?>
