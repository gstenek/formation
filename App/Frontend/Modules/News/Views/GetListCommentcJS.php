<?php
/**
 * Created by PhpStorm.
 * User: gstenek
 * Date: 30/03/2017
 * Time: 10:37
 */

$json = [];

if ( isset( $Comment_a ) ) {
	$json[ 'Comment_a' ] = $Comment_a;
	
	if ( isset( $url_delete_a ) && isset( $url_update_a ) ) {
		$json[ 'url_update_a' ] = $url_update_a;
		$json[ 'url_delete_a' ] = $url_delete_a;
	}
}

return $json;