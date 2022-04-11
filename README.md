# Discord Php Oauth Wrapper
Easy to use Discord OAuth wrapper 


## How to use (begin)

```php
require "wrapper.php";

use oWrap\oauthWrapper;

$oauth = new oauthWrapper(client_id, client_secret, scopes);

$oauth->begin(); // this will redirect to discord oauth
```

## How to get User object on redirect

```php

require "wrapper.php";

use oWrap\oauthWrapper;

$oauth = new oauthWrapper(client_id, client_secret, scopes);

if (isset($_GET['code'])){
  
  $user = $oauth->getUser($_GET['code']);
  
  print_r($user); // checking the response object
}
```

## How to refresh access token 

```php 
require "wrapper.php";

use oWrap\oauthWrapper;

$oauth = new oauthWrapper(client_id, client_secret, scopes);

$newToken = $oauth->refreshToken(access_token);
  
print_r($newToken); // checking the response object
```

## How to revoke access token 

```php 
require "wrapper.php";

use oWrap\oauthWrapper;

$oauth = new oauthWrapper(client_id, client_secret, scopes);

$response = $oauth->revokeToken(access_token); // this returns an empty object if successful 
  
print_r($newToken); // checking the response object
```

## Each function 

### Begin
```php
begin(void)
```
### Get token
```php
getToken(code)
```
### Get User Object
```php
getUser(access_token)
```
### Refresh token
```php
refreshToken(refresh_token)
```
### Revoke token 
```php
revokeToken(access_token)
```
