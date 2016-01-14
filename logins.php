<?php
#
#  This is a sample Logins file - one in which you can put your logins.
#
#  The examples from the Apibot wiki rely on a structure like the one here.
#
# ---------------------------------------------------------------------------- #


require_once ( dirname ( __FILE__ ) . '/wikis.php' );


$logins = array (
  'My_Account@en.wikipedia.org' => array (
    'user'            => 'My Account',   // change as appropiate
    'password'        => 'My Password',  // change as appropiate
    'wiki'            => $wikipedia_en,
  ),
  'My_Bot_Account@en.wikipedia.org' => array (
    'user'            => 'My Bot Account',   // change as appropiate
    'password'        => 'My Bot Password',  // change as appropiate
    'wiki'            => $wikipedia_en,
  ),
);

