<?php

/**
************************************************************************
*  Author: kierownik
*  Date: 2013-06-15
*  Description: Adds Social links to the profile and viewtopic pages
*               where users can add their usernames.
*  Copyright (C) Daniel Rokven ( rokven@gmail.com )
*  License: http://www.gnu.org/licenses/gpl.html GPL version 2 or higher
*
************************************************************************
**/

// Make sure no one attempts to run this script "directly"
if ( !defined( 'PUN' ) )
{
  exit;
}

$page_title = array( pun_htmlspecialchars( $pun_config['o_board_title'] ), $lang_common['Profile'], $lang_spl['social profile links'] );
define( 'PUN_ACTIVE_PAGE', 'profile' );
require PUN_ROOT.'header.php';

generate_profile_menu( 'spl' );

$spl_user   = unserialize( $user['social_profile_links'] );
$spl_config = unserialize( $pun_config['o_social_profile_links'] );

//
// Include the arrays.php
//
require( PUN_ROOT.'/plugins/spl/arrays.php' );

// $link_options is used to build the input boxes
$link_options = MaxLengthUsernameOrId();
?>

  <div class="blockform">
    <h2><span><?php echo pun_htmlspecialchars( $user['username'] ).' - '. $lang_spl['social profile links'] ?></span></h2>
    <div class="box">
      <form id="profile3a" method="post" action="profile.php?section=spl&amp;id=<?php echo $id ?>">
        <div class="inform">
          <fieldset>
            <legend><?php echo $lang_spl['username of user id']; ?></legend>
            <div class="infldset">
              <input type="hidden" name="form_sent" value="1" />

              <?php
              array_multisort( $link_options );
              foreach ( $link_options AS $key => $value )
              {
                $spl_user[$key] = isset( $spl_user[$key] ) ? pun_htmlspecialchars( $spl_user[$key] ) : '';

                if ( $key == 'google+' OR $key == 'care2')
                {
                  echo '<label>'.$lang_spl[$key].'<br /><input id="'.$key.'" type="text" name="form['.$key.']" value="'.$spl_user[$key].'" size="40" maxlength="'.$value['maxlength'].'" placeholder="'.$lang_spl['user id'].'" /><br /></label>';
                }
                else
                {
                  echo '<label>'.$lang_spl[$key].'<br /><input id="'.$key.'" type="text" name="form['.$key.']" value="'.$spl_user[$key].'" size="40" maxlength="'.$value['maxlength'].'" placeholder="'.$lang_spl['username'].'" /><br /></label>';
                }
              }
              ?>

            </div>
          </fieldset>
        </div>
        <p class="buttons">
          <input type="submit" name="update" value="<?php echo $lang_common['Submit'] ?>" /> <?php echo $lang_profile['Instructions'] ?>
        </p>
      </form>
    </div>
  </div>