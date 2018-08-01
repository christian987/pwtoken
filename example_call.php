<?php

// this is what your user does to authenticate with your api

$my_api_key = 'fecfe217d294e4533d87e90fe4a10031c9d83f10170ec80c7ac1e8fc825b3e21a40b6ace307b6cab';
$my_api_secret = "2a8b394bddbffca92a7af8fa7b0bef1c0d0ecec1";
$my_api_appid = "06f22ef73c9c12749fc61c1bf47d3614681a2212";

$post = [
	"method" => "auth.authenticate", // your method to perfom an auth whatever you wnat to call it
	"api_key" => $my_api_key, // could seen by public
	"api_auth" => hash('sha512', $my_api_key.$my_api_secret.$my_api_appid), // auth signature of keys from user
];

$options = [
  	CURLOPT_URL        => 'localhost/my_project/my_api',
	CURLOPT_POST       => true,
	CURLOPT_POSTFIELDS => $post,
	CURLOPT_RETURNTRANSFER => true,
];

$curl = curl_init();
curl_setopt_array($curl, $options);
$results = curl_exec($curl);
curl_close($curl);

echo $results; // should return the correct pwtoken