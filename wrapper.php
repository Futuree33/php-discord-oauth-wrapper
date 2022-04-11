<?php 

namespace oWrap;

class oauthWrapper {


  private static $AUTH_URL = "https://discord.com/api/oauth2/authorize";
  private static $TOKEN_URL = "https://discord.com/api/oauth2/token";
  private static $REVOKE_URL = "https://discord.com/api/oauth2/token/revoke";
  private static $USER_URL = "https://discord.com/api/users/@me";
  private static $REDIRECT_URL = "http://localhost/oauthwrapper/base.php";

  /* application variables */ 

  public static $CLIENT_ID; 
  public static $CLIENT_SECRET;
  public static $SCOPES;

  public function __construct($CLIENT_ID, $CLIENT_SECRET, $SCOPES){
    self::$CLIENT_ID = $CLIENT_ID;
    self::$CLIENT_SECRET = $CLIENT_SECRET;
    self::$SCOPES = $SCOPES;
  }

  public function begin(){
    $parameters = array("client_id" => self::$CLIENT_ID, "redirect_uri" => self::$REDIRECT_URL, "response_type" => "code", "scope" => self::$SCOPES);
    return header("Location: ". self::$AUTH_URL ."?". http_build_query($parameters));
  }

  /* Get user access and refresh tokens (mainly use this for getting the refresh token) */
  public function getToken($code){
    $parameters = array("client_id" => self::$CLIENT_ID, "client_secret" => self::$CLIENT_SECRET, "grant_type" => "authorization_code", "code" => $code, "redirect_uri" => self::$REDIRECT_URL);
    $headers = array("Accept: application/json", "Content-Type: application/x-www-form-urlencoded");

    $curl = curl_init(self::$TOKEN_URL);
    curl_setopt($curl, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($parameters));
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

    $response = curl_exec($curl);
    return json_decode($response);
  } 

  /* get user object */ 
  public function getUser($code) {
    $token = $this->getToken($code)->access_token; // getting the access token for easier use

    $headers = array("Accept: application/json", "Authorization: Bearer ".$token, "Content-Type: application/x-www-form-urlencoded");
    $curl = curl_init(self::$USER_URL);
    curl_setopt($curl, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

    $response = curl_exec($curl);
    return json_decode($response);
  }

  /* refresh access token */ 
  public function refreshToken($token){
    $parameters = array("client_id" => self::$CLIENT_ID, "client_secret" => self::$CLIENT_SECRET, "grant_type" => "refresh_token", "refresh_token" => $token);
    $headers = array("Accept: application/json", "Content-Type: application/x-www-form-urlencoded");
    
    $curl = curl_init(self::$TOKEN_URL);
    curl_setopt($curl, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($parameters));
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

    $response = curl_exec($curl);
    return json_decode($response);
  }
  
  /* revoke access token */
  public function revokeToken($token){
    $parameters = array("client_id" => self::$CLIENT_ID, "client_secret" => self::$CLIENT_SECRET, "token" => $token);
    $headers = array("Accept: application/json", "Content-Type: application/x-www-form-urlencoded");
   
    $curl = curl_init(self::$REVOKE_URL);
    curl_setopt($curl, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($parameters));
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

    $response = curl_exec($curl);
    return json_decode($response);
  }
}

