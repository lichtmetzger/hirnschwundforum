<?php


session_name("arcade_games");
session_start();

// Session first run?
if (!isset($_SESSION['firsttime']))
{
	// Set defaults
	$_SESSION['firsttime'] = true;
    $_SESSION['cat']=0;
    $_SESSION['nump']=25;
    $_SESSION['search']='';
    $_SESSION['rsearch']='game_name';
    $_SESSION['page']=0;
	$_SESSION['sorto']='DESC';
	$_SESSION['sortby']='game_id';
	//$sqlquery = '';
}
else
{
	// no first run, use post or request
	if (isset($_POST['nump'])) $_SESSION['nump']=$_POST['nump'];
	if (isset($_POST['cat'])) $_SESSION['cat']=$_POST['cat'];
	if (isset($_POST['search'])) $_SESSION['search']=$_POST['search'];
	if (isset($_POST['rsearch'])) $_SESSION['rsearch']=$_POST['rsearch'];
	if (isset($_REQUEST['page'])) $_SESSION['page']=$_REQUEST['page'];
	if (isset($_POST['sorto'])) $_SESSION['sorto']=$_POST['sorto'];
	if (isset($_POST['sortby'])) $_SESSION['sortby']=$_POST['sortby'];
}

// Define local vars
$s_nump = $_SESSION['nump'];
$s_cat = $_SESSION['cat'];
$s_search = $_SESSION['search'];
$s_rsearch = $_SESSION['rsearch'];
$s_page = $_SESSION['page'];
$s_sorto = $_SESSION['sorto'];
$s_sortby = $_SESSION['sortby'];
$sqlquery = '';

if (!defined('PUN_ROOT')) define('PUN_ROOT','./');

require PUN_ROOT.'include/common.php';
require PUN_ROOT.'lang/'.$pun_user['language'].'/arcade.php';
$page_title = array(pun_htmlspecialchars($pun_config['o_board_title']), pun_htmlspecialchars($lang_arcade['Arcade Games']));
require PUN_ROOT.'header.php';

if (!function_exists('generate_config_cache'))
	require PUN_ROOT.'include/cache.php';
	
if ($pun_config['arcade_live'] == '0')
    message($lang_arcade['arcade disabled']);
    
    
if (!$pun_user['is_guest'] || $pun_config['arcade_allow_guests'] == '1')
{
	// Fetch total game count
	$result = $db->query('SELECT COUNT(game_id) FROM '.$db->prefix.'arcade_games') or error('Unable to fetch total game count', __FILE__, __LINE__, $db->error());
	$num_games = $db->result($result);

	
	// Arcade Statistic Block?>
	<div class="blocktable">
	<h2><span><?php echo pun_htmlspecialchars($lang_arcade['Arcade Games']).' ('.$lang_arcade['number games'].' '.$num_games.')'?></span></h2>
		<div class="box">
		<?php // Newest games?>
		<table cellspacing="0" style="padding: 0px 0px 0px 0px; margin-top: 0px; margin-left: 0px; margin-right: 0px; margin-bottom: 0px; border:none;">			
		<tr style="padding: 0px 0px 0px 0px; margin-top: 0px; margin-left: 0px; margin-right: 0px; margin-bottom: 0px; border:none;">
			<td valign="top" width="20%" class="arcadeleft">
			<fieldset>
			<legend align="left"> <?php echo ''.$lang_arcade['new_games'].''?> </legend><p></p>
			
			<?php // Get latest games
			$result2 = $db->query('SELECT game_id, game_name, game_image FROM '.$db->prefix.'arcade_games ORDER BY game_id DESC LIMIT '.$pun_config['arcade_numnew']) or error('Unable to get latest games', __FILE__, __LINE__, $db->error());
			$i = 1;
			while($line = $db->fetch_assoc($result2))
			{
				echo '<img align="top" src="games/images/_'.$line['game_image'].'" alt="" />&nbsp;<a href="arcade_play.php?id='.$line['game_id'].'">'.$line['game_name'].'</a><p>'."\n";
				$i++;
			}
			?><p></p>
			</td></fieldset>


			<?php // King of Highscore images?>
			<td valign="top" width="60%" class="arcademiddle">
			<fieldset>
			<legend align="left"> <?php echo ''.$lang_arcade['highscore_champs'].''?> </legend><p></p>
			<div class="box" style="padding:10px;">
			<table cellspacing="0">
			<tr>
			<td class="alt2" width="33%" style="border:none;text-align:center;"><b><img src="./img/arcade/king1.gif" alt="1" title="" /></b></td>
			<td class="alt2" width="33%" style="border:none;text-align:center;"><b><img src="./img/arcade/king2.gif" alt="2" title="" /></b></td>
			<td class="alt2" width="33%" style="border:none;text-align:center;"><b><img src="./img/arcade/king3.gif" alt="3" title="" /></b></td>
			</tr>
	
			<?php // Count all Highscores per user, display the king of the highscores
			$sql = 'SELECT id,username, COUNT(*) AS count_top FROM '.$db->prefix.'arcade_ranking INNER JOIN '.$db->prefix.'users ON ('.$db->prefix.'users.id = '.$db->prefix.'arcade_ranking.rank_player) WHERE '.$db->prefix.'arcade_ranking.rank_topscore = 1 GROUP BY '.$db->prefix.'arcade_ranking.rank_player ORDER BY count_top DESC LIMIT 3';
			$query = $db->query($sql) or error("Impossible to collect highscores per user.", __FILE__, __LINE__, $db->error());
			$i = 1;
			while($line = $db->fetch_assoc($query))
			{
				echo '<td width="33%" style="border:none;text-align:center;"><strong><span style="text-decoration:blink"><a href="arcade_userstats.php?id='.$line['id'].'" title="'.$lang_arcade['view_stats'].'">'.pun_htmlspecialchars($line['username']).'</a></strong></span><br/>'.$lang_arcade['with'].' <b>'.$line['count_top'].'</b> '.$lang_arcade['highscores'].'</td>';
				$i++;
			}?>
	
			</table>
			</div>
			<p></p><p></p>
			<div class="box" style="padding: 0px 7px 7px 7px;">
	
			<?php // Find the latest Highscores	
			$sql = 'SELECT game_name, game_id, rank_topscore, username, id, rank_date, rank_score FROM '.$db->prefix.'arcade_ranking, '.$db->prefix.'arcade_games, '.$db->prefix.'users WHERE rank_topscore = 1 AND rank_game = game_filename AND '.$db->prefix.'users.id = rank_player GROUP BY game_name ORDER BY rank_date DESC LIMIT '.$pun_config['arcade_numchamps'].'';
			$query = $db->query($sql) or error("Impossible to select the latest highscores.", __FILE__, __LINE__, $db->error());
			$i = 1;
			while($line = $db->fetch_assoc($query))
			{
				// Display the latest Highscores	
				echo '<table cellspacing="0"><td width="75%" style="border:none;">   <p><a href="arcade_userstats.php?id='.$line['id'].'" title="'.$lang_arcade['view_stats'].'">'.pun_htmlspecialchars($line['username']).'</a> '.$lang_arcade['is_the_new'].' <i><a href="arcade_play.php?id='.$line['game_id'].'">'.$line['game_name'].'</a></i>'.$lang_arcade['champion'].'.</td><td width="25%" style="border:none;"> ('.format_time($line['rank_date']).')</p>   </td></table>';
				$i++;
			}?>
			</div>
			<p></p>
			</fieldset>
			
			<p></p>
			<div class="box" align="center" style="padding:7px;">
			<?php // Find last score
			$sql = 'SELECT game_name, username, rank_date, rank_score, id, game_id FROM '.$db->prefix.'arcade_ranking, '.$db->prefix.'arcade_games, '.$db->prefix.'users WHERE rank_game = game_filename AND '.$db->prefix.'users.id = rank_player GROUP BY rank_date ORDER BY rank_date DESC LIMIT 1';
			$query = $db->query($sql) or error("Impossible to select the latest score.", __FILE__, __LINE__, $db->error());
			$line = $db->fetch_assoc($query)?>
			
			<?php echo ''.$lang_arcade['newest_score'].''?> <a href="arcade_userstats.php?id=<?php echo ''.$line['id'].''?>" title="<?php echo ''.$lang_arcade['view_stats'].''?>"><i><?php echo ''.pun_htmlspecialchars($line['username']).''?></i></a> <?php echo ''.$lang_arcade['makes'].''?> <i><?php echo ''.$line['rank_score'].''?></i> <?php echo ''.$lang_arcade['points_at'].''?> <i><a href="arcade_play.php?id=<?php echo ''.$line['game_id'].''?>"><?php echo ''.$line['game_name'].''?></a></i>
			</div>

			
			<?php // Most played games ?>
			</td>
			<td nowrap="nowrap" valign="top" width="20%" class="arcaderight">
			<fieldset>
			<legend align="left"> <?php echo ''.$lang_arcade['most_played'].''?> </legend><p></p>
			
			<?php // Find most played games
			$result3 = $db->query('SELECT game_id, game_name, game_played, game_image FROM '.$db->prefix.'arcade_games ORDER BY game_played DESC LIMIT '.$pun_config['arcade_mostplayed'].'') or error('Unable to get most played games', __FILE__, __LINE__, $db->error());
			$i = 1;
			while($line = $db->fetch_assoc($result3))
			{
				// Display most played games
echo '<span><a href="arcade_play.php?id='.$line['game_id'].'" title="'.$lang_arcade['played'].' '.$line['game_played'].'"><img align="top" src="games/images/_'.$line['game_image'].'" alt="" /></a>&nbsp;<a href="arcade_play.php?id='.$line['game_id'].'" title="'.$lang_arcade['played'].' '.$line['game_played'].'">'.$line['game_name'].' ('.$line['game_played'].')</a><p></span>';				$i++;
			}?>
			<p></p>
			</fieldset>
			
			<?php // Get random game and statistic
			$result6 = $db->query('SELECT game_id, game_name,game_image FROM '.$db->prefix.'arcade_games GROUP BY game_name order by RAND() LIMIT 1') or error('Unable to fetch total game count', __FILE__, __LINE__, $db->error());
			$randg = $db->fetch_assoc($result6);?>
			<p><fieldset>
			<legend align="left"><?php echo $lang_arcade['randomg']?></legend><p></p>
			<table cellspacing="0" style="border:none;">
			<tr>
			<td width="30%" valign="top" cellspacing="0" style="border:none;">
			<a href="arcade_play.php?id=<?php echo $randg['game_id']?>" title="<?php echo $lang_arcade['Pic Click']?>"><img src="games/images/<?php echo $randg['game_image']?>" alt="" /></a><p></td>
			<td align="left" cellspacing="0" style="border:none;"><a href="arcade_play.php?id=<?php echo $randg['game_id']?>"><?php echo $randg['game_name']?></a></td>
			</tr>
			</table>
			</fieldset>
			</td>
		</tr>
		</table>
		</div>
	</div>	
			
	<?php // Define search query
	if (strlen($s_search)>0) $sqlquery .= " WHERE {$s_rsearch} LIKE '%{$s_search}%'";
	// Did we use a category or the search box?
	if ($s_cat>0)
	{
		if (strlen($s_search)>0)
		{
			$sqlquery .= " AND game_cat = {$s_cat} ORDER BY game_name {$s_sorto}";
		}
		else
		{
			$sqlquery .= " WHERE game_cat = {$s_cat} ORDER BY game_name {$s_sorto}";
		}
	}
	else
	{
		$sqlquery .= " ORDER BY {$s_sortby} {$s_sorto}";
	}?>
	
	
	<?php // Show/hide Filter Block?>	
	<script language="javascript">
	<!--
		var state = 'none';
		function showhide(layer_ref) 
		{
			if (state == 'block') 
			{
				state = 'none';
			}
			else 
			{
				state = 'block';
			}
		if (document.all) 
			{ //IS IE 4 or 5 (or 6 beta)
				eval( "document.all." + layer_ref + ".style.display = state");
			}
		if (document.layers) 
			{ //IS NETSCAPE 4 or below
				document.layers[layer_ref].display = state;
			}
		if (document.getElementById &&!document.all) 
			{
				hza = document.getElementById(layer_ref);
				hza.style.display = state;
			}
		}
	//-->
	</script>

	<?php // Filter Block.?>	

	<div style="padding: 5px 5px 5px 5px; margin-bottom:5px; border:none;" class="box">
		<fieldset>
			<legend><a style="text-decoration:none; border:none;" href="javascript:void(0)" onclick="showhide('div3');"><?php echo $lang_arcade['filter']?></a></legend>
			<div class="infldset" id="div3" style="display:none;">
				<form method="post" action="<?php echo $_SERVER['PHP_SELF']?>" enctype="multipart/form-data">
					<table cellspacing="0" style="padding: 0px 0px 0px 0px; margin-top: 0px; margin-left: 0px; margin-right: 0px; margin-bottom: 0px; border:none;">
					<tr>
						<td valign="top" align="left" width="20%" style="padding: 0px 0px 0px 0px; margin-top: 0px; margin-left: 0px; margin-right: 0px; margin-bottom: 0px; border:none;"><?php echo $lang_arcade['numgames']?><p>
						<select id="nump" name="nump">
						<option value="10" <?php if ($s_nump == 10) echo ' selected="selected"'?>>10</option>
						<option value="25" <?php if ($s_nump == 25) echo ' selected="selected"'?>>25</option>
						<option value="50" <?php if ($s_nump == 50) echo ' selected="selected"'?>>50</option>
						<option value="100" <?php if ($s_nump == 100) echo ' selected="selected"'?>>100</option>
						</select><p>
						<input type="radio" name="sorto" value="ASC" <?php if ($s_sorto == 'ASC') { echo ' checked'; }?> />
						&nbsp;<?php echo $lang_arcade['asc']?>&nbsp;
						<input type="radio" name="sorto" value="DESC" <?php if ($s_sorto == 'DESC') { echo ' checked'; }?> />
						&nbsp;<?php echo $lang_arcade['desc']?>
						</td>
						<td valign="top" align="left" style="padding: 0px 0px 0px 0px; margin-top: 0px; margin-left: 0px; margin-right: 0px; margin-bottom: 0px; border:none;"><?php echo $lang_arcade['sortby']?><p>
						<select id="sortby" name="sortby">
						<option value="game_name" <?php if ($s_sortby == 'game_name') echo ' selected="selected"'?>><?php echo $lang_arcade['Game name']?></option>
						<option value="game_id" <?php if ($s_sortby == 'game_id') echo ' selected="selected"'?>><?php echo $lang_arcade['Date']?></option>
						</select><p>
						</td>
						<td valign="top" align="left" style="padding: 0px 0px 0px 0px; margin-top: 0px; margin-left: 0px; margin-right: 0px; margin-bottom: 0px; border:none;"><?php echo $lang_arcade['category']?><p>
						<select id="cat" name="cat">
						<option value="0" <?php if ($s_cat == 0) echo ' selected="selected"'?>><?php echo $lang_arcade['all cat']?></option>
						<option value="1" <?php if ($s_cat == 1) echo ' selected="selected"'?>><?php echo $lang_arcade['cat1']?></option>
						<option value="2" <?php if ($s_cat == 2) echo ' selected="selected"'?>><?php echo $lang_arcade['cat2']?></option>
						<option value="3" <?php if ($s_cat == 3) echo ' selected="selected"'?>><?php echo $lang_arcade['cat3']?></option>
						<option value="4" <?php if ($s_cat == 4) echo ' selected="selected"'?>><?php echo $lang_arcade['cat4']?></option>
						<option value="5" <?php if ($s_cat == 5) echo ' selected="selected"'?>><?php echo $lang_arcade['cat5']?></option>
						<option value="6" <?php if ($s_cat == 6) echo ' selected="selected"'?>><?php echo $lang_arcade['cat6']?></option>
						<option value="7" <?php if ($s_cat == 7) echo ' selected="selected"'?>><?php echo $lang_arcade['cat7']?></option>
						<option value="8" <?php if ($s_cat == 8) echo ' selected="selected"'?>><?php echo $lang_arcade['cat8']?></option>
						<option value="9" <?php if ($s_cat == 9) echo ' selected="selected"'?>><?php echo $lang_arcade['cat9']?></option>
						</select>
						</td>
						<td valign="top" align="left" style="padding: 0px 0px 0px 0px; margin-top: 0px; margin-left: 0px; margin-right: 0px; margin-bottom: 0px; border:none;"><?php echo $lang_arcade['search']?><p>
						<input type="text" id="search" name="search" size="20" maxlength="100" value="<?php echo pun_htmlspecialchars($s_search);?>" />
						&nbsp;&nbsp;
						<p><input type="radio" name="rsearch" value="game_name" <?php if ($s_rsearch == 'game_name') { echo ' checked'; }?> />
						&nbsp;<?php echo $lang_arcade['gname']?>&nbsp;
						<input type="radio" name="rsearch" value="game_desc" <?php if ($s_rsearch == 'game_desc') { echo ' checked'; }?> />
						&nbsp;<?php echo $lang_arcade['gdesc']?>
						</td>
					</tr>
					<tr>
						<td valign="bottom" colspan="5" style="padding: 0px 0px 0px 0px; margin-top: 0px; margin-left: 0px; margin-right: 0px; margin-bottom: 0px; border:none;">
						<input type="hidden" name="page" value="0" />
						<br /><p><input type="submit" name="filter" value="<?php echo $lang_arcade['start']?>" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input name="filter" type="submit" onClick="nump.value='25';cat.value='0';search.value='';rsearch.value='game_name';elements[5].checked = true;elements[2].checked = true;sortby.value='game_id';window.location='<?php echo $_SERVER['PHP_SELF']?>';" value="<?php echo $lang_arcade['reset']?>" />
						</td>
					</tr>
					</table>
				</form>
			</div>
		</fieldset>
	</div>	

	<?php // Fetch game count and generate pages, after using filter
	$result = $db->query('SELECT COUNT(game_id) FROM '.$db->prefix.'arcade_games '.$sqlquery) or error('Unable to fetch filter count', __FILE__, __LINE__, $db->error());
	$num_games2 = $db->result($result);
	$currec = $s_page * $s_nump;
	$kolvop = ceil($num_games2 / $s_nump);
	$cp = ($kolvop == 0? 1 : $kolvop);
	$nump = $s_nump;

	//Anchor for browsing through the pages
	echo '<a id="pages"></a>';

	// Generate page links
	 if ($cp>1)
		{
			echo '<div class="pagepost"><p class="pagelink conl"><span class="pages-label" style="margin-right:5px;">', $lang_common['Pages'].'</span>';
			for ($i=1;$i<=$cp;$i++)
			if (($i-1)==$s_page) echo "<strong> $i </strong>";
			else echo ' <a href="'.$_SERVER['PHP_SELF'].'?page='.($i-1).'#pages">'.$i.'</a> ';
		}
		echo '</p></div>';
		

	// Old themes need this
		echo '<div class="clearer"></div>';

	// Output Games, highscores, description ?>
	<div class="blocktable">
		<div class="box">
			<div class="inbox">
				<table>
				<thead>
					<tr>
						<th class="tcl" scope="col"><?php echo $lang_arcade['Games']?></th>
						<th class="tc2" scope="col"><?php echo $lang_arcade['highscores']?></th>
						<th class="tc2" scope="col"><?php echo $lang_arcade['Your highscore']?></th>
						<th class="tcr" scope="col"><?php echo $lang_arcade['How to play']?></th>
					</tr>
				</thead>
				<tbody>

		<?php // Filter query
		$result = $db->query('SELECT * FROM '.$db->prefix.'arcade_games '.$sqlquery." LIMIT $currec,$nump") or error("Impossible to filter games", __FILE__, __LINE__, $db->error());
		while($line = $db->fetch_assoc($result))
		{
			// Find Top Highscore of each game		
			$sql2 = 'SELECT rank_player, rank_score, username, id FROM '.$db->prefix.'arcade_ranking, '.$db->prefix.'users WHERE rank_game = \''.$db->escape($line['game_filename']).'\' AND '.$db->prefix.'users.id = '.$db->prefix.'arcade_ranking.rank_player ORDER BY rank_score DESC LIMIT 1';
			$query = $db->query($sql2) or error("Impossible to find the topscore of each game", __FILE__, __LINE__, $db->error());
			$resultat = $db->fetch_assoc($query);
			if (($resultat['rank_score']) && ($pun_config['arcade_showtop'] > 0))
				$h_score = ''.$lang_arcade['Top highscore'].'<strong>'.$resultat['rank_score'].'</strong> '.$lang_arcade['by'].' <i><a href="profile.php?id='.$resultat['id'].'"> '.pun_htmlspecialchars($resultat['username']).'</a></i><p> '.$lang_arcade['Your highscore'].': <strong>'.$resultat['rank_score'].'</strong><p>'.$lang_arcade['played'].' <strong>'.$line['game_played'].'</strong>';
			else
				$h_score = '&#160;';
				echo '<tr>
					<td class="tcl" width="25%">
					<div class="acimage" style="display:inline-block;float:left;padding-right:5px;"><a href="arcade_play.php?id='.$line['game_id'].'" title="'.$lang_arcade['Pic Click'].'"><img src="games/images/'.$line['game_image'].'" alt="'.$line['game_name'].'" /></a></div>
					<div class="acdouble"><div class="actitle" style="height:2.5em"><a href="arcade_play.php?id='.$line['game_id'].'" title="'.$lang_arcade['Pic Click'].'">'.$line['game_name'].'</a></div><div class="acplayed">
'.$lang_arcade['played'].' <strong>'.$line['game_played'].'</strong></div></div>
					</td>
					<td class="tc2" width="15%">';
                    if ($resultat['rank_score'] > 0) {
						echo''.$lang_arcade['Top highscore'].'<strong>'.$resultat['rank_score'].'</strong><br />
<i> '.$lang_arcade['by'].' <strong><a href="arcade_userstats.php?id='.$resultat['id'].'" title="'.$lang_arcade['view_stats'].'">'.pun_htmlspecialchars($resultat['username']).'</a></i></strong><p />
<a href="arcade_ranking.php?id='.$line['game_id'].'">'.$lang_arcade['View Highscore'].'</a>'; } else { echo ''.$lang_arcade['Top highscore'].'<strong> N/A'; } echo '</td>
						<td class="tc2" width="20%">';
						// Find best score of user
						$result21 = $db->query('SELECT rank_score, game_id, game_name, game_played FROM '.$db->prefix.'arcade_ranking, '.$db->prefix.'arcade_games WHERE rank_game = \''.$db->escape($line['game_filename']).'\' AND rank_player = "'.$pun_user['id'].'"') or error('Unable to fetch scores info', __FILE__, __LINE__, $db->error());

			// if(mysql_num_rows($result21) <= 0)
			// Fix for MySQL 4
			$resultatt = $db->fetch_assoc($result21);
			if($resultatt <= 0)
				{ 
					echo $lang_arcade['Not played yet'];
				}
			else
				{
					$line21 = $db->fetch_assoc($result21);
					echo $lang_arcade['Your highscore'],': ' ?><strong><?php echo $line21['rank_score'] ?></strong>
			<?php
				}
			echo '</td>
			<td class="tcr" scope="col"><i>'.$line['game_desc'].'</i></td>
			</tr>';
		}?>
				</tbody>
				</table>
			</div>
		</div>
    </div>


	<?php 	// Generate page links
	 if ($cp>1)
		{
			echo '<div class="pagepost"><p class="pagelink conl"><span class="pages-label" style="margin-right:5px;">', $lang_common['Pages'].'</span>';
			for ($i=1;$i<=$cp;$i++)
			if (($i-1)==$s_page) echo "<strong> $i </strong>";
			else echo ' <a href="'.$_SERVER['PHP_SELF'].'?page='.($i-1).'#pages">'.$i.'</a> ';
		}
		echo '</p></div>';
		
		// Old themes need this
		echo '<div class="clearer"></div>';
		
	require PUN_ROOT.'footer.php';
}

else
	message($lang_common['Not logged in']);
