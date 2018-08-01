<?php

class PwToken {

	private static $settings = array(
		'ttl' => 86400,
		'salt' => 'MyMagicPWTokenSaltThatIsSoRandom'
	);

	public static function createAccessToken($api_key, $api_secret, $api_appid)
	{
		$expires = time()+self::$settings['ttl'];

		$data = base64_encode(json_encode(array(
			"expires" => $expires,
			"app_id" => $api_appid
		)));

		$signature = self::createSignature($data, $api_key, $api_secret, $api_appid);

		return array(
			"token" => base64_encode($data.'.'.$signature),
			"expires" => $expires
		);
	}

	public static function createSignature($data, $api_key, $api_secret, $api_appid)
	{
		return base64_encode(json_encode(array(
			"signature" => hash_hmac("sha256", $data.$api_key.$api_secret.$api_appid.self::$settings['salt'], $api_key)
		)));
	}

	public static function parseAccessToken($token)
	{
		$output = array();
		$chunk = explode('.', base64_decode($token));

		if(isset($chunk[0]) && is_array(json_decode(base64_decode($chunk[0]), true)))
			$output += json_decode(base64_decode($chunk[0]), true);

		if(isset($chunk[1]) && is_array(json_decode(base64_decode($chunk[1]), true)))
			$output += json_decode(base64_decode($chunk[1]), true);

		return $output;
	}

	public static function validateAccessToken($token, $api_key, $api_secret, $api_appid)
	{
		if(substr_count($token, '.') !== 1)
			return false;
		
		$chunk = explode('.',base64_decode($token));
		$data = json_decode(base64_decode($chunk[0]), true);
		$auth = json_decode(base64_decode($chunk[1]), true);

		// check expiry
		if($data['expires'] < time())
			return 'expired';

		$checked_data = base64_encode(json_encode(array(
			"expires" => $data['expires'],
			"app_id" => $data['app_id']
		)));

		$signature = base64_encode(json_encode(array(
			"signature" => $auth['signature']
		)));

		if($signature === self::createSignature($checked_data, $api_key, $api_secret, $api_appid))
			return true;

		return false;
	}

}