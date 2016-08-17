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

function LinkOptions()
{
  $link_options = array(
    'care2',
    'delicious',
    'deviantart',
    'facebook',
    'github',
    'google+',
    'hyves',
    'instagram',
    'myspace',
    'pinterest',
    'stumbleupon',
    'tumblr',
    'twitter',
    'youtube',
  );

  return $link_options;
}

function MaxLengthUsernameOrId()
{
  global $spl_config;

  $link_options = array();

  if ( !empty( $spl_config['care2'] ) AND $spl_config['care2'] != '0' )
  {
    $link_options['care2'] = array(
      'position'  =>  $spl_config['care2'],
      'maxlength' =>  '9'
    );
  }

  if ( !empty( $spl_config['delicious'] ) AND $spl_config['delicious'] != '0' )
  {
    $link_options['delicious'] = array(
      'position'  =>  $spl_config['delicious'],
      'maxlength' =>  '20'
    );
  }

  if ( !empty( $spl_config['deviantart'] ) AND $spl_config['deviantart'] != '0' )
  {
    $link_options['deviantart'] = array(
      'position'  =>  $spl_config['deviantart'],
      'maxlength' =>  '20'
    );
  }

  if ( !empty( $spl_config['facebook'] ) AND $spl_config['facebook'] != '0' )
  {
    $link_options['facebook'] = array(
      'position'  =>  $spl_config['facebook'],
      'maxlength' =>  '50'
    );
  }

  if ( !empty( $spl_config['github'] ) AND $spl_config['github'] != '0' )
  {
    $link_options['github'] = array(
      'position'  =>  $spl_config['github'],
      'maxlength' =>  '50'
    );
  }

  if ( !empty( $spl_config['google+'] ) AND $spl_config['google+'] != '0' )
  {
    $link_options['google+'] = array(
      'position'  =>  $spl_config['google+'],
      'maxlength' =>  '21'
    );
  }

  if ( !empty( $spl_config['hyves'] ) AND $spl_config['hyves'] != '0' )
  {
    $link_options['hyves'] = array(
      'position'  =>  $spl_config['hyves'],
      'maxlength' =>  '20'
    );
  }

  if ( !empty( $spl_config['instagram'] ) AND $spl_config['instagram'] != '0' )
  {
    $link_options['instagram'] = array(
      'position'  =>  $spl_config['instagram'],
      'maxlength' =>  '30'
    );
  }

  if ( !empty( $spl_config['myspace'] ) AND $spl_config['myspace'] != '0' )
  {
    $link_options['myspace'] = array(
      'position'  =>  $spl_config['myspace'],
      'maxlength' =>  '25'
    );
  }

  if ( !empty( $spl_config['pinterest'] ) AND $spl_config['pinterest'] != '0' )
  {
    $link_options['pinterest'] = array(
      'position'  =>  $spl_config['pinterest'],
      'maxlength' =>  '15'
    );
  }

  if ( !empty( $spl_config['stumbleupon'] ) AND $spl_config['stumbleupon'] != '0' )
  {
    $link_options['stumbleupon'] = array(
      'position'  =>  $spl_config['stumbleupon'],
      'maxlength' =>  '15'
    );
  }

  if ( !empty( $spl_config['tumblr'] ) AND $spl_config['tumblr'] != '0' )
  {
    $link_options['tumblr'] = array(
      'position'  =>  $spl_config['tumblr'],
      'maxlength' =>  '32'
    );
  }

  if ( !empty( $spl_config['twitter'] ) AND $spl_config['twitter'] != '0' )
  {
    $link_options['twitter'] = array(
      'position'  =>  $spl_config['twitter'],
      'maxlength' =>  '15'
    );
  }

  if ( !empty( $spl_config['youtube'] ) AND $spl_config['youtube'] != '0' )
  {
    $link_options['youtube'] = array(
      'position'  =>  $spl_config['youtube'],
      'maxlength' =>  '20'
    );
  }

  return $link_options;
}

//
// Returns all the link options we use in the plugin
// 
function AdminLinkOptions()
{
  global $pun_config;

  $spl_config = unserialize( $pun_config['o_social_profile_links'] );

  $link_options = array(
    'care2'       =>  !isset( $spl_config['care2'] )        ? '0' : $spl_config['care2'],
    'delicious'   =>  !isset( $spl_config['delicious'] )    ? '0' : $spl_config['delicious'],
    'deviantart'  =>  !isset( $spl_config['deviantart'] )   ? '0' : $spl_config['deviantart'],
    'facebook'    =>  !isset( $spl_config['facebook'] )     ? '0' : $spl_config['facebook'],
    'github'      =>  !isset( $spl_config['github'] )       ? '0' : $spl_config['github'],
    'google+'     =>  !isset( $spl_config['google+'] )      ? '0' : $spl_config['google+'],
    'hyves'       =>  !isset( $spl_config['hyves'] )        ? '0' : $spl_config['hyves'],
    'instagram'   =>  !isset( $spl_config['instagram'] )    ? '0' : $spl_config['instagram'],
    'myspace'     =>  !isset( $spl_config['myspace'] )      ? '0' : $spl_config['myspace'],
    'pinterest'   =>  !isset( $spl_config['pinterest'] )    ? '0' : $spl_config['pinterest'],
    'stumbleupon' =>  !isset( $spl_config['stumbleupon'] )  ? '0' : $spl_config['stumbleupon'],
    'tumblr'      =>  !isset( $spl_config['tumblr'] )       ? '0' : $spl_config['tumblr'],
    'twitter'     =>  !isset( $spl_config['twitter'] )      ? '0' : $spl_config['twitter'],
    'youtube'     =>  !isset( $spl_config['youtube'] )      ? '0' : $spl_config['youtube'],
  );

  return $link_options;
}

//
// Returns all the options that we have for this plugin
// 
function AdminSplOptions()
{
  $spl_options = array(
    'care2'       => !empty( $_POST['care2'] )        ? intval( $_POST['care2'] )       : '0',
    'delicious'   => !empty( $_POST['delicious'] )    ? intval( $_POST['delicious'] )   : '0',
    'deviantart'  => !empty( $_POST['deviantart'] )   ? intval( $_POST['deviantart'] )  : '0',
    'facebook'    => !empty( $_POST['facebook'] )     ? intval( $_POST['facebook'] )    : '0',
    'github'      => !empty( $_POST['github'] )       ? intval( $_POST['github'] )      : '0',
    'google+'     => !empty( $_POST['google+'] )      ? intval( $_POST['google+'] )     : '0',
    'hyves'       => !empty( $_POST['hyves'] )        ? intval( $_POST['hyves'] )       : '0',
    'instagram'   => !empty( $_POST['instagram'] )    ? intval( $_POST['instagram'] )   : '0',
    'myspace'     => !empty( $_POST['myspace'] )      ? intval( $_POST['myspace'] )     : '0',
    'pinterest'   => !empty( $_POST['pinterest'] )    ? intval( $_POST['pinterest'] )   : '0',
    'stumbleupon' => !empty( $_POST['stumbleupon'] )  ? intval( $_POST['stumbleupon'] ) : '0',
    'tumblr'      => !empty( $_POST['tumblr'] )       ? intval( $_POST['tumblr'] )      : '0',
    'twitter'     => !empty( $_POST['twitter'] )      ? intval( $_POST['twitter'] )     : '0',
    'youtube'     => !empty( $_POST['youtube'] )      ? intval( $_POST['youtube'] )     : '0',

    // The options
    'show_in_profile'   => isset( $_POST['show_in_profile'] ) ?   '1' : '0',
    'show_in_viewtopic' => isset( $_POST['show_in_viewtopic'] ) ? '1' : '0',
    'use_icon'          => isset( $_POST['use_icon'] ) ?          '1' : '0',
    'show_guest'        => isset( $_POST['show_guest'] ) ?        '1' : '0',
    'link_target'       => pun_htmlspecialchars( $_POST['link_target'] ),
  );

  return $spl_options;
}

function SplLinks()
{
  global $pun_config, $spl_cur_user, $spl_config, $lang_spl;

  // This is the array we are going to use to build our links
  $spl_links = array();

  if ( !empty( $spl_config['care2'] ) AND isset( $spl_cur_user['care2'] ) )
  {
    // Set the spl_username for care2
    $spl_username = ( $pun_config['o_censoring'] == '1' ? pun_htmlspecialchars( censor_words( $spl_cur_user['care2'] ) ) : pun_htmlspecialchars( $spl_cur_user['care2'] ) );

    // Fill the spl_links array for care2
    $spl_links['care2'] = array(
      'position'  =>  $spl_config['care2'],
      'username'  =>  $spl_username,
      'url'       =>  'http://www.care2.com/c2c/people/profile.html?pid='.$spl_username,
      'lang'      =>  $lang_spl['care2'],
      'image'     => 'Care2.png',
    );
  }

  if ( !empty( $spl_config['delicious'] ) AND isset( $spl_cur_user['delicious'] ) )
  {
    // Set the spl_username for delicious
    $spl_username = ( $pun_config['o_censoring'] == '1' ? pun_htmlspecialchars( censor_words( $spl_cur_user['delicious'] ) ) : pun_htmlspecialchars( $spl_cur_user['delicious'] ) );

    // Fill the spl_links array for deviantart
    $spl_links['delicious'] = array(
      'position'  =>  $spl_config['delicious'],
      'username'  =>  $spl_username,
      'url'       =>  'http://delicious.com/'.$spl_username,
      'lang'      =>  $lang_spl['delicious'],
      'image'     => 'Delicious.png',
    );
  }

  if ( !empty( $spl_config['deviantart'] ) AND isset( $spl_cur_user['deviantart'] ) )
  {
    // Set the spl_username for deviantart
    $spl_username = ( $pun_config['o_censoring'] == '1' ? pun_htmlspecialchars( censor_words( $spl_cur_user['deviantart'] ) ) : pun_htmlspecialchars( $spl_cur_user['deviantart'] ) );

    // Fill the spl_links array for deviantart
    $spl_links['deviantart'] = array(
      'position'  =>  $spl_config['deviantart'],
      'username'  =>  $spl_username,
      'url'       =>  'http://'.$spl_username.'.deviantart.com',
      'lang'      =>  $lang_spl['deviantart'],
      'image'     => 'Deviantart.png',
    );
  }

  if ( !empty( $spl_config['facebook'] ) AND isset( $spl_cur_user['facebook'] ) )
  {
    // Set the spl_username for facebook
    $spl_username = ( $pun_config['o_censoring'] == '1' ? pun_htmlspecialchars( censor_words( $spl_cur_user['facebook'] ) ) : pun_htmlspecialchars( $spl_cur_user['facebook'] ) );

    // Fill the spl_links array for facebook
    $spl_links['facebook'] = array(
      'position'  =>  $spl_config['facebook'],
      'username'  =>  $spl_username,
      'url'       =>  'https://facebook.com/'.$spl_username,
      'lang'      =>  $lang_spl['facebook'],
      'image'     => 'Facebook.png',
    );
  }

  if ( !empty( $spl_config['github'] ) AND isset( $spl_cur_user['github'] ) )
  {
    // Set the spl_username for github
    $spl_username = ( $pun_config['o_censoring'] == '1' ? pun_htmlspecialchars( censor_words( $spl_cur_user['github'] ) ) : pun_htmlspecialchars( $spl_cur_user['github'] ) );

    // Fill the spl_links array for github
    $spl_links['github'] = array(
      'position'  =>  $spl_config['github'],
      'username'  =>  $spl_username,
      'url'       =>  'https://github.com/'.$spl_username,
      'lang'      =>  $lang_spl['github'],
      'image'     => 'GitHub.png',
    );
  }

  if ( !empty( $spl_config['google+'] ) AND isset( $spl_cur_user['google+'] ) )
  {
    // Set the spl_username for google+
    $spl_username = ( $pun_config['o_censoring'] == '1' ? pun_htmlspecialchars( censor_words( $spl_cur_user['google+'] ) ) : pun_htmlspecialchars( $spl_cur_user['google+'] ) );

    // Fill the spl_links array for google+
    $spl_links['google+'] = array(
      'position'  =>  $spl_config['google+'],
      'username'  =>  $spl_username,
      'url'       =>  'https://profiles.google.com/'.$spl_username.'/posts',
      'lang'      =>  $lang_spl['google+'],
      'image'     => 'Google+.png',
    );
  }

  if ( !empty( $spl_config['hyves'] ) AND isset( $spl_cur_user['hyves'] ) )
  {
    // Set the spl_username for hyves
    $spl_username = ( $pun_config['o_censoring'] == '1' ? pun_htmlspecialchars( censor_words( $spl_cur_user['hyves'] ) ) : pun_htmlspecialchars( $spl_cur_user['hyves'] ) );

    // Fill the spl_links array for hyves
    $spl_links['hyves'] = array(
      'position'  =>  $spl_config['hyves'],
      'username'  =>  $spl_username,
      'url'       =>  'http://'.$spl_username.'.hyves.nl',
      'lang'      =>  $lang_spl['hyves'],
      'image'     => 'Hyves.png',
    );
  }

  if ( !empty( $spl_config['instagram'] ) AND isset( $spl_cur_user['instagram'] ) )
  {
    // Set the spl_username for instagram
    $spl_username = ( $pun_config['o_censoring'] == '1' ? pun_htmlspecialchars( censor_words( $spl_cur_user['instagram'] ) ) : pun_htmlspecialchars( $spl_cur_user['instagram'] ) );

    // Fill the spl_links array for instagram
    $spl_links['instagram'] = array(
      'position'  =>  $spl_config['instagram'],
      'username'  =>  $spl_username,
      'url'       =>  'http://instagram.com/'.$spl_username,
      'lang'      =>  $lang_spl['instagram'],
      'image'     => 'Instagram.png',
    );
  }

  if ( !empty( $spl_config['myspace'] ) AND isset( $spl_cur_user['myspace'] ) )
  {
    // Set the spl_username for myspace
    $spl_username = ( $pun_config['o_censoring'] == '1' ? pun_htmlspecialchars( censor_words( $spl_cur_user['myspace'] ) ) : pun_htmlspecialchars( $spl_cur_user['myspace'] ) );

    // Fill the spl_links array for myspace
    $spl_links['myspace'] = array(
      'position'  =>  $spl_config['myspace'],
      'username'  =>  $spl_username,
      'url'       =>  'https://myspace.com/'.$spl_username,
      'lang'      =>  $lang_spl['myspace'],
      'image'     => 'MySpace.png',
    );
  }

  if ( !empty( $spl_config['pinterest'] ) AND isset( $spl_cur_user['pinterest'] ) )
  {
    // Set the spl_username for pinterest
    $spl_username = ( $pun_config['o_censoring'] == '1' ? pun_htmlspecialchars( censor_words( $spl_cur_user['pinterest'] ) ) : pun_htmlspecialchars( $spl_cur_user['pinterest'] ) );

    // Fill the spl_links array for pinterest
    $spl_links['pinterest'] = array(
      'position'  =>  $spl_config['pinterest'],
      'username'  =>  $spl_username,
      'url'       =>  'http://pinterest.com/'.$spl_username,
      'lang'      =>  $lang_spl['pinterest'],
      'image'     => 'Pinterest.png',
    );
  }

  if ( !empty( $spl_config['stumbleupon'] ) AND isset( $spl_cur_user['stumbleupon'] ) )
  {
    // Set the spl_username for stumbleupon
    $spl_username = ( $pun_config['o_censoring'] == '1' ? pun_htmlspecialchars( censor_words( $spl_cur_user['stumbleupon'] ) ) : pun_htmlspecialchars( $spl_cur_user['stumbleupon'] ) );

    // Fill the spl_links array for stumbleupon
    $spl_links['stumbleupon'] = array(
      'position'  =>  $spl_config['stumbleupon'],
      'username'  =>  $spl_username,
      'url'       =>  'http://www.stumbleupon.com/stumbler/'.$spl_username,
      'lang'      =>  $lang_spl['stumbleupon'],
      'image'     => 'Stumbleupon.png',
    );
  }

  if ( !empty( $spl_config['tumblr'] ) AND isset( $spl_cur_user['tumblr'] ) )
  {
    // Set the spl_username for tumblr
    $spl_username = ( $pun_config['o_censoring'] == '1' ? pun_htmlspecialchars( censor_words( $spl_cur_user['tumblr'] ) ) : pun_htmlspecialchars( $spl_cur_user['tumblr'] ) );

    // Fill the spl_links array for tumblr
    $spl_links['tumblr'] = array(
      'position'  =>  $spl_config['tumblr'],
      'username'  =>  $spl_username,
      'url'       =>  'http://'.$spl_username.'.tumblr.com',
      'lang'      =>  $lang_spl['tumblr'],
      'image'     => 'Tumblr.png',
    );
  }

  if ( !empty( $spl_config['twitter'] ) AND isset( $spl_cur_user['twitter'] ) )
  {
    // Set the spl_username for twitter
    $spl_username = ( $pun_config['o_censoring'] == '1' ? pun_htmlspecialchars( censor_words( $spl_cur_user['twitter'] ) ) : pun_htmlspecialchars( $spl_cur_user['twitter'] ) );

    // Fill the spl_links array for twitter
    $spl_links['twitter'] = array(
      'position'  =>  $spl_config['twitter'],
      'username'  =>  $spl_username,
      'url'       =>  'https://twitter.com/'.$spl_username,
      'lang'      =>  $lang_spl['twitter'],
      'image'     => 'Twitter.png',
    );
  }

  if ( !empty( $spl_config['youtube'] ) AND isset( $spl_cur_user['youtube'] ) )
  {
    // Set the spl_username for youtube
    $spl_username = ( $pun_config['o_censoring'] == '1' ? pun_htmlspecialchars( censor_words( $spl_cur_user['youtube'] ) ) : pun_htmlspecialchars( $spl_cur_user['youtube'] ) );

    // Fill the spl_links array for youtube
    $spl_links['youtube'] = array(
      'position'  =>  $spl_config['youtube'],
      'username'  =>  $spl_username,
      'url'       =>  'https://youtube.com/user/'.$spl_username,
      'lang'      =>  $lang_spl['youtube'],
      'image'     => 'YouTube.png',
    );
  }

  return $spl_links;
}

//
// Returns the array that holds alle the images we use
// 
function ImageArray()
{
  $image_array = array(
    'care2'       => 'Care2.png',
    'delicious'   => 'Delicious.png',
    'deviantart'  => 'Deviantart.png',
    'facebook'    => 'Facebook.png',
    'github'      => 'GitHub.png',
    'google+'     => 'Google+.png',
    'hyves'       => 'Hyves.png',
    'instagram'   => 'Instagram.png',
    'myspace'     => 'MySpace.png',
    'pinterest'   => 'Pinterest.png',
    'stumbleupon' => 'Stumbleupon.png',
    'tumblr'      => 'Tumblr.png',
    'twitter'     => 'Twitter.png',
    'youtube'     => 'YouTube.png',
  );

  return $image_array;
}

function ProfileCase()
{
  global $spl_config, $spl_users, $lang_spl;

  $preg_array = array();

  // Check if input box of Care2 is not empty and spl_config is set higher than 0 before doing adding regex
  if ( !empty( $spl_config['care2'] ) AND isset( $spl_users['care2'] ) )
  {
    $preg_array['care2'] = array(
      'position'    => $spl_config['care2'],
      'preg_match'  => !preg_match( '/[0-9]{3,9}$/', $spl_users['care2'] ),
      'message'     => $lang_spl['bad care2'],
    );
  }

  // Check if input box of Delicious is not empty and spl_config is set higher than 0 before doing adding regex
  if ( !empty( $spl_config['delicious'] ) AND isset( $spl_users['delicious'] ) )
  {
    $preg_array['delicious'] = array(
      'position'    => $spl_config['delicious'],
      'preg_match'  => !preg_match( '/[A-Za-z0-9]{3,20}$/', $spl_users['delicious'] ),
      'message'     => $lang_spl['bad delicious'],
    );
  }

  // Check if input box of Deviantart is not empty and spl_config is set higher than 0 before doing adding regex
  if ( !empty( $spl_config['deviantart'] ) AND isset( $spl_users['deviantart'] ) )
  {
    $preg_array['deviantart'] = array(
      'position'    => $spl_config['deviantart'],
      'preg_match'  => !preg_match( '/[A-Za-z0-9-]{3,20}$/', $spl_users['deviantart'] ),
      'message'     => $lang_spl['bad deviantart'],
    );
  }

  // Check if input box of Facebook is not empty and spl_config is set higher than 0 before doing adding regex
  if ( !empty( $spl_config['facebook'] ) AND isset( $spl_users['facebook'] ) )
  {
    $preg_array['facebook'] = array(
      'position'    => $spl_config['facebook'],
      'preg_match'  => !preg_match( '/[A-Za-z0-9\.]{5,50}$/', $spl_users['facebook'] ),
      'message'     => $lang_spl['bad facebook'],
    );
  }

  // Check if input box of GitHub is not empty and spl_config is set higher than 0 before doing adding regex
  if ( !empty( $spl_config['github'] ) AND isset( $spl_users['github'] ) )
  {
    $preg_array['github'] = array(
      'position'    => $spl_config['github'],
      'preg_match'  => !preg_match( '/[A-Za-z0-9\.]{3,50}$/', $spl_users['github'] ),
      'message'     => $lang_spl['bad github'],
    );
  }

  // Check if input box of Google+ is not empty and spl_config is set higher than 0 before doing adding regex
  if ( !empty( $spl_config['google+'] ) AND isset( $spl_users['google+'] ) )
  {
    $preg_array['google+'] = array(
      'position'    => $spl_config['google+'],
      'preg_match'  => preg_match( '%[^0-9]%', $spl_users['google+'] ),
      'message'     => $lang_spl['bad google+'],
    );
  }

  // Check if input box of Hyves is not empty and spl_config is set higher than 0 before doing adding regex
  if ( !empty( $spl_config['hyves'] ) AND isset( $spl_users['hyves'] ) )
  {
    $preg_array['hyves'] = array(
      'position'    => $spl_config['hyves'],
      'preg_match'  => !preg_match( '/[A-Za-z0-9-]{3,20}$/', $spl_users['hyves'] ),
      'message'     => $lang_spl['bad hyves'],
    );
  }

  // Check if input box of Instagram is not empty and spl_config is set higher than 0 before doing adding regex
  if ( !empty( $spl_config['instagram'] ) AND isset( $spl_users['instagram'] ) )
  {
    $preg_array['instagram'] = array(
      'position'    => $spl_config['instagram'],
      'preg_match'  => !preg_match( '/[A-Za-z0-9_]{5,30}$/', $spl_users['instagram'] ),
      'message'     => $lang_spl['bad instagram'],
    );
  }

  // Check if input box of MySpace is not empty and spl_config is set higher than 0 before doing adding regex
  if ( !empty( $spl_config['myspace'] ) AND isset( $spl_users['myspace'] ) )
  {
    $preg_array['myspace'] = array(
      'position'    => $spl_config['myspace'],
      'preg_match'  => !preg_match( '/[A-Za-z0-9\.-]{3,25}$/', $spl_users['myspace'] ),
      'message'     => $lang_spl['bad myspace'],
    );
  }

  // Check if input box of Pinterest is not empty and spl_config is set higher than 0 before doing adding regex
  if ( !empty( $spl_config['pinterest'] ) AND isset( $spl_users['pinterest'] ) )
  {
    $preg_array['pinterest'] = array(
      'position'    => $spl_config['pinterest'],
      'preg_match'  => !preg_match( '/[A-Za-z0-9]{3,15}$/', $spl_users['pinterest'] ),
      'message'     => $lang_spl['bad pinterest'],
    );
  }

  // Check if input box of Stumbleupon is not empty and spl_config is set higher than 0 before doing adding regex
  if ( !empty( $spl_config['stumbleupon'] ) AND isset( $spl_users['stumbleupon'] ) )
  {
    $preg_array['stumbleupon'] = array(
      'position'    => $spl_config['stumbleupon'],
      'preg_match'  => !preg_match( '/[A-Za-z0-9-]{1,15}$/', $spl_users['stumbleupon'] ),
      'message'     => $lang_spl['bad stumbleupon'],
    );
  }

  // Check if input box of Tumblr is not empty and spl_config is set higher than 0 before doing adding regex
  if ( !empty( $spl_config['tumblr'] ) AND isset( $spl_users['tumblr'] ) )
  {
    $preg_array['tumblr'] = array(
      'position'    => $spl_config['tumblr'],
      'preg_match'  => !preg_match( '/[A-Za-z0-9_]{3,32}$/', $spl_users['tumblr'] ),
      'message'     => $lang_spl['bad tumblr'],
    );
  }

  // Check if input box of Twitter is not empty and spl_config is set higher than 0 before doing adding regex
  if ( !empty( $spl_config['twitter'] ) AND isset( $spl_users['twitter'] ) )
  {
    $preg_array['twitter'] = array(
      'position'    => $spl_config['twitter'],
      'preg_match'  => !preg_match( '/[A-Za-z0-9_]{1,15}$/', $spl_users['twitter'] ),
      'message'     => $lang_spl['bad twitter'],
    );
  }

  // Check if input box of YouTube is not empty and spl_config is set higher than 0 before doing adding regex
  if ( !empty( $spl_config['youtube'] ) AND isset( $spl_users['youtube'] ) )
  {
    $preg_array['youtube'] = array(
      'position'    => $spl_config['youtube'],
      'preg_match'  => !preg_match( '/[A-Za-z0-9_\-.]{6,20}$/', $spl_users['youtube'] ),
      'message'     => $lang_spl['bad youtube'],
    );
  }
  return $preg_array;
}

?>