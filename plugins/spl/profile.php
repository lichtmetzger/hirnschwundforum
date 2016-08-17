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

$spl_cur_user = unserialize( $user['social_profile_links'] );
$spl_config   = unserialize( $pun_config['o_social_profile_links'] );

if ( $spl_config['show_in_profile'] == '1' AND ( $spl_config['show_guest'] == '1' OR !$pun_user['is_guest'] ) )
{
  $target = ( $spl_config['link_target'] ) ? ' target="_blank"' : '';

  //
  // Include the arrays.php
  //
  require( PUN_ROOT.'/plugins/spl/arrays.php' );
  //  spl_links holds all the social links
  $spl_links  = SplLinks();

  // Here is where the magic is
  array_multisort( $spl_links );
  foreach ( $spl_links as $key => $value )
  {
    $user_personal[] = '<dt>'.$value['lang'].'</dt>';

    if ( $spl_config['use_icon'] == '1' )
    {
      $user_personal[] = '<dd><span><a href="'.$value['url'].'" title="'.$value['lang'].'" rel="nofollow"'.$target.'><img src="'.pun_htmlspecialchars( get_base_url( TRUE ) ).'/plugins/spl/images/'.$value['image'] .'" width="16" height="16" alt="'.$value['lang'].'" /></a></span></dd>';
    }
    else
    {
      $user_personal[] = '<dd><span class="website"><a href="'.$value['url'].'" title="'.$value['lang'].'" rel="nofollow"'.$target.'>'.$value['username'].'</a></span></dd>';
    }
  }
}

?>