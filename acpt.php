<?php
/**
* Advanced Custom Post Types
*
* @global string $acpt_version
*/
$acpt_version = '0.0.1';
$wp_version = '3.3';

if($wp_version < '3.3' ): exit('You need the 3.3 version of WordPress.');
else: echo 'live!';
endif;

class acpt {
	
}