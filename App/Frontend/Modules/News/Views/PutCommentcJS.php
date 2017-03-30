<?php
$json = [];
if (isset($error_a)) {
	$json['error_a'] = $error_a;
};

if(isset($Comment)){
	$json['Comment'] = $Comment;
};

if(isset($url_update)){
	$json['url_update'] = $url_update;
};

if(isset($url_delete)){
	$json['url_delete'] = $url_delete;
};

return $json;

