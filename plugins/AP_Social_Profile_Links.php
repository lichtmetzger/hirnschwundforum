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

// Load the social-profile-links.php language file
if ( file_exists( PUN_ROOT.'plugins/spl/lang/'.$pun_user['language'].'/social-profile-links.php' ) )
  require PUN_ROOT.'plugins/spl/lang/'.$pun_user['language'].'/social-profile-links.php';
else
  require PUN_ROOT.'plugins/spl/lang/English/social-profile-links.php';

// Tell admin_loader.php that this is indeed a plugin and that it is loaded
define( 'PUN_PLUGIN_LOADED', 1 );

// Plugin version
define( 'PLUGIN_VERSION', '1.3.7' );

//
// Include the arrays.php
//
require( PUN_ROOT.'/plugins/spl/arrays.php' );
$image_array  = ImageArray();
$link_options = AdminLinkOptions();

//
// The rest is up to you!
//
if ( isset( $_POST['set_options'] ) )
{
  $updated = FALSE;

  // get the spl_options from array.php
  $spl_options = AdminSplOptions();

  if ( serialize( $spl_options ) != $pun_config['o_social_profile_links'] )
  {
    $query = 'UPDATE `'.$db->prefix."config` SET `conf_value` = '".$db->escape( serialize( $spl_options ) )."' WHERE `conf_name` = 'o_social_profile_links'";

    $db->query( $query ) or error( 'Unable to update board config post '. print_r( $db->error() ),__FILE__, __LINE__, $db->error() );

    $updated = TRUE;
  }

  if ( $updated )
  {
    // Regenerate the config cache
    require_once PUN_ROOT.'include/cache.php';
    generate_config_cache();
    redirect( $_SERVER['REQUEST_URI'], $lang_spl['data saved'] );
  }
} // end set_options

  // Display the admin navigation menu
  generate_admin_menu( $plugin );

  // We need all the config unserialized
  $spl_config = unserialize( $pun_config['o_social_profile_links'] );

?>
<div id="exampleplugin" class="plugin blockform">
  <h2><span><?php echo $lang_spl['social profile links'] ?> - v<?php echo PLUGIN_VERSION ?></span></h2>
  <div class="box">
    <div class="inbox">
      <p><?php echo $lang_spl['social profile links info'] ?></p>
    </div>
  </div>
</div>
<div class="blockform">
  <h2 class="block2"><span><?php echo $lang_spl['options'] ?></span></h2>
  <div class="box">
    <form id="spl" method="post" action="<?php echo $_SERVER['REQUEST_URI'] ?>">
    <div class="inform">
      <p class="submittop">
        <input type="submit" name="set_options" value="<?php echo $lang_spl['save options'] ?>"/>
      </p>
      <fieldset>
      <legend><?php echo $lang_spl['link options'] ?></legend>

        <?php
        // Are there enabled link options to display
        $enabled = FALSE;
        foreach( $link_options AS $key => $value )
        {
          $enabled = $enabled || ( $value !== '0' );
        }

        if ( $enabled )
        {
          ?>

          <div class="infldset">
            <table class="aligntop" style="border-spacing:0;border-collapse:collapse;">
              <tr>
                <th scope="col"><strong><?php echo $lang_spl['enabled links'] ?>:</strong></th>
                <td><?php echo $lang_spl['enabled/disabled info'] ?></td>
              </tr>

              <?php
              array_multisort( $link_options );
              foreach ( $link_options AS $key => $value )
              {
                if ( $value != '0' )
                {
                  ?>

                  <tr>
                    <th scope="col"><label for="<?php echo $key ?>"><img src="<?php echo pun_htmlspecialchars( get_base_url( TRUE ) ).'/plugins/spl/images/'.$image_array[$key] ?>" /> <?php echo $lang_spl[$key] ?></label></th>
                    <td>
                      <input type="text" id="<?php echo $key ?>" name="<?php echo $key ?>" value="<?php echo $value ?>" />
                    </td>
                  </tr>

                  <?php
                }
              }
              ?>

            </table>
          </div>  <!-- end class="infldset" -->

          <?php
        }
        // End are there enabled link options to display

        // Are there disabled link options to display
        $disabled = FALSE;
        ksort( $link_options );
        foreach( $link_options AS $key => $value )
        {
          $disabled = $disabled || ( $value == '0' );
        }

        if ( $enabled AND $disabled )
          echo '<br />';

        if ( $disabled )
        {
          ?>

          <div class="infldset">
            <table class="aligntop" style="border-spacing:0;border-collapse:collapse;">
              <tr>
                <th scope="col"><strong><?php echo $lang_spl['disabled links'] ?>:</strong></th>
                <td><?php echo $lang_spl['enabled/disabled info'] ?></td>
              </tr>

            <?php
            foreach ( $link_options AS $key => $value )
            {
              if ( $value == '0' )
              {
                ?>

                <tr>
                  <th scope="col"><label for="<?php echo $key ?>"><img src="<?php echo pun_htmlspecialchars( get_base_url( TRUE ) ).'/plugins/spl/images/'.$image_array[$key] ?>" /> <?php echo $lang_spl[$key] ?></label></th>
                  <td>
                    <input type="text" id="<?php echo $key ?>" name="<?php echo $key ?>" value="<?php echo $value ?>" />
                  </td>
                </tr>

                <?php
              }
            }
            ?>

            </table>
          </div>  <!-- end class="infldset" -->

          <?php
        }
        // End are there disabled link options to display
        ?>

      </fieldset>
      <fieldset>
        <legend><?php echo $lang_spl['display options'] ?></legend>
        <div class="infldset">
          <table class="aligntop" style="border-spacing:0;border-collapse:collapse;">
            <tr>
              <th scope="col"><label for="show_in_profile"><?php echo $lang_spl['show in users profile'] ?></label></th>
              <td>
                <input type="checkbox" id="show_in_profile" name="show_in_profile" value="1" 
                <?php
                  if ( $spl_config['show_in_profile'] == '1' )
                  {
                    echo ' checked="checked"';
                  }
                ?> />
              </td>
            </tr>
            <tr>
              <th scope="col"><label for="show_in_viewtopic"><?php echo $lang_spl['show in viewtopic'] ?></label></th>
              <td>
                <input type="checkbox" id="show_in_viewtopic" name="show_in_viewtopic" value="1" 
                <?php
                  if ( $spl_config['show_in_viewtopic'] == '1' )
                  {
                    echo ' checked="checked"';
                  }
                ?> />
              </td>
            </tr>
            <tr>
              <th scope="col"><label for="use_icon"><?php echo $lang_spl['use icon'] ?></label></th>
              <td>
                <input type="checkbox" id="use_icon" name="use_icon" value="1" 
                <?php
                  if ( $spl_config['use_icon'] == '1' )
                  {
                    echo ' checked="checked"';
                  }
                ?> />
              </td>
            </tr>
            <tr>
              <th scope="col"><label for="show_guest"><?php echo $lang_spl['show guests'] ?></label></th>
              <td>
                <input type="checkbox" id="show_guest" name="show_guest" value="1" 
                <?php
                  if ( $spl_config['show_guest'] == '1' )
                  {
                    echo ' checked="checked"';
                  }
                ?> /> <?php echo $lang_spl['show guests info'] ?>
              </td>
            </tr>
            <tr>
              <th scope="col"><label for="link_target"><?php echo $lang_spl['link target'] ?></label></th>
              <td>
                <select id="link_target" name="link_target">

                <?php
                  if ( $spl_config['link_target'] == '1' )
                  {
                    echo '<option value="1" selected="selected">'.$lang_spl['link target external'].'</option>';
                  }
                  else
                  {
                    echo '<option value="1" >'.$lang_spl['link target external'].'</option>';
                  }
                  if ( $spl_config['link_target'] == '0' )
                  {
                    echo '<option value="0" selected="selected">'.$lang_spl['link target internal'].'</option>';
                  }
                  else
                  {
                    echo '<option value="0" >'.$lang_spl['link target internal'].'</option>';
                  }
                ?>

                </select>
              </td>
            </tr>
          </table>
        </div>	<!-- end class="infldset" -->
      </fieldset>
      <p class="submittop">
        <input type="submit" name="set_options" value="<?php echo $lang_spl['save options'] ?>"/>
      </p>
    </div>
    </form>
  </div>      <!-- end class="box" -->
</div>        <!-- end class="blockform" -->