<?php
include "index.php";
define( 'CLEVER_BASE_URL', 'https://clever.com/' );
define( 'CLEVER_API_URL', 'https://api.clever.com' );

if ( $_REQUEST['func'] == 'clever' ) {
	if ( ! empty( $_REQUEST['code'] ) ) {
        
        $access_token = generateOauthToken( $_REQUEST['code'] );
        if ( ! empty( $access_token['access_token'] ) ) {
			$clever_user = getLoggedInCleverUser( $access_token['access_token'] );
            echo "<pre>";
            print_r($clever_user);
            echo "</pre>";
        
		}
	}
	// redirect_login( SITE_SECURE_URL . 'login.php?msg=Unable to login' );
}

// Generate Oauth Token.
function generateOauthToken( $code ) {
    global $clever_ids;
	if ( ! $code || ! $clever_ids['redirect_url'] ) {
		return false;
	}
	$oauth_data                 = array();
	$oauth_data['code']         = $code;
	$oauth_data['grant_type']   = 'authorization_code';
	$oauth_data['redirect_uri'] = $clever_ids['redirect_url'];

	return executeQuery( 'oauth/tokens', 'POST', 'basic', $oauth_data );
}

function executeQuery( $api, $method, $oauth = false, $post_data = array() ) {
	$base_url = ( $oauth == 'basic' ) ? CLEVER_BASE_URL : CLEVER_API_URL;

    $ch       = curl_init( $base_url . $api );
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
	curl_setopt( $ch, CURLOPT_ENCODING, '' );
	curl_setopt( $ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1 );
	curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, $method );
	if ( ! empty( $post_data ) ) {
		curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode( $post_data ) );
	}
	curl_setopt( $ch, CURLOPT_HTTPHEADER, ( $oauth == 'basic' ) ? createBasicAuthorization() : createBearerAuthorization( $oauth ) );
    $response = curl_exec( $ch );
    return json_decode( $response, true );
}

function createBasicAuthorization() {
    global $clever_ids;
	if ( ! $clever_ids['client_id'] || ! $clever_ids['client_secret'] ) {
		return false;
	}
	$oauth    = array();
	$oauth[0] = 'Content-type: application/json';
	$oauth[1] = 'Authorization: Basic ' . base64_encode( $clever_ids['client_id'] . ':' . $clever_ids['client_secret'] );
    return $oauth;
}

function createBearerAuthorization( $access_token ) {
	if ( ! $access_token ) {
		return false;
	}
	$oauth    = array();
	$oauth[0] = 'Content-type: application/json';
	$oauth[1] = 'Authorization: Bearer ' . $access_token;

	return $oauth;
}

// Get Logged User Details
function getLoggedInCleverUser( $access_token ) {
	if ( ! $access_token ) {
		return false;
	}
	$user     = array();
	$response = executeQuery( '/v3.0/me', 'GET', $access_token );
	if ( ! empty( $response['links'][1]['uri'] ) ) {
		$response = executeQuery( $response['links'][1]['uri'], 'GET', $access_token );
        print_r($response);
        exit;
		if ( ! empty( $response['data']['email'] ) ) {
			$user['email']      = $response['data']['email'];
			$user['first_name'] = $response['data']['name']['first'];
			$user['last_name']  = $response['data']['name']['last'];
		}
	}

	return $user;
}
?>
