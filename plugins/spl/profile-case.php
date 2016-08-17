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

$spl_config = unserialize( $pun_config['o_social_profile_links'] );

//
// Include the arrays.php
//
require( PUN_ROOT.'/plugins/spl/arrays.php' );
$link_options = LinkOptions();

$spl_users = array();

foreach ( $link_options AS $key )
{
  if ( !empty( $_POST['form'][$key] ) AND !empty( $spl_config[$key] ) )
  {
    $spl_users[$key] = pun_trim( $_POST['form'][$key] );
  }
}

$preg_array = ProfileCase();

array_multisort( $preg_array );
// Here we check if the entered usernames or user id's are valid
foreach ( $preg_array AS $key )
{
  if ( $key['preg_match'] )
    message( $key['message'] );
}

$form = array( 'social_profile_links' => ( !empty( $spl_users ) ) ? serialize( $spl_users ) : NULL );