<?php

/**
 * HybridAuth
 * http://hybridauth.sourceforge.net | http://github.com/hybridauth/hybridauth
 * (c) 2009-2015, HybridAuth authors | http://hybridauth.sourceforge.net/licenses.html
 */
// ----------------------------------------------------------------------------------------
//	HybridAuth Config file: http://hybridauth.sourceforge.net/userguide/Configuration.html
// ----------------------------------------------------------------------------------------

$config =array(
		"base_url" => "http://4me302.a3.linneas.net/library/index.php", 
		"providers" => array ( 

			"Google" => array ( 
				"enabled" => true,
				"keys"    => array ( "id" => "1048844616535-beuaf8ou3n3shndnte5u9pinrrqo9dtg.apps.googleusercontent.com", "secret" => "MB_EnFoTInsQqZ1oQ6ryA-ky" ), 
			),

			"Facebook" => array ( 
                    		"enabled" => true,
                    		"trustForwarded" => true, 
                    		"keys"    => array ( "id" => "859777630796558", "secret" => "e165b2ecdc8563e52a6d340e75ab362c" ),
                    		"scope"   => "email, user_about_me, user_birthday, user_hometown", // optional
                    		"display" => "popup" // optional
			),

			"Twitter" => array ( 
				"enabled" => true,
				"keys"    => array ( "key" => "xY27ltraOYsZupm72mDcRTFMK", "secret" => "6BBcarro42q13Chy37XDUxqAflyoEpGO0PvDAYn9wmlxR2PEHT" ) 
			),
		),
		
		"debug_mode" => false,
		"debug_file" => "",
	);