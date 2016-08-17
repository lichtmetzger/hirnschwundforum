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

$spl_cur_user = unserialize( $cur_post['social_profile_links'] );
$spl_config   = isset( $spl_config ) ? $spl_config : unserialize( $pun_config['o_social_profile_links'] );

// Are there cache links to display, we display them instead of going through the array
if ( isset( $spl_cache_links[$cur_post['poster_id']] ) )
{
  $user_contacts[] = implode(' ', $spl_cache_links[$cur_post['poster_id']]);
}
elseif ( $spl_config['show_in_viewtopic'] == '1' AND ( $spl_config['show_guest'] == '1' OR !$pun_user['is_guest'] ) )
{
  $target = ( $spl_config['link_target'] ) ? ' target="_blank"' : '';

  //
  // Include the arrays.php
  //
  require_once( PUN_ROOT.'/plugins/spl/arrays.php' );

  // spl_links holds all the social links
  $spl_links  = SplLinks();

  // Set the cache link
  $spl_cache_links[$cur_post['poster_id']] = array();

  // If the user_contacts is not empty we need two new lines
  if ( !empty( $user_contacts ) )
  {
    $user_contacts[] = $spl_cache_links[$cur_post['poster_id']][] = '<br /><br />';
  }

  // Here is where the magic is
  array_multisort( $spl_links );
  foreach ( $spl_links as $key =>$value )
  {
    if ( $spl_config['use_icon'] == '1' )
    {
      $user_contacts[] = $spl_cache_links[$cur_post['poster_id']][] = '<span><a href="'.$value['url'].'" rel="nofollow" title="'.$value['lang'].'"'.$target.'><img src="'.pun_htmlspecialchars( get_base_url( TRUE ) ).'/plugins/spl/images/'.$value['image'].'" width="16" height="16" alt="'.$value['lang'].'" /></a></span>';
    }
    else
    {
      $user_contacts[] = $spl_cache_links[$cur_post['poster_id']][] = '<span class="website"><a href="'.$value['url'].'" rel="nofollow" title="'.$value['lang'].'"'.$target.'>'.$value['lang'].'</a></span>';
    }
  }
}
?>