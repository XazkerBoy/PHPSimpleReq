# PHPSimpleReq
PHP Class for Python 'requests'-Library lovers

## Description
This PHP tiny class allows you to create a 'Session' and use GET and POST Request. Then you can get response text, headers, cookies or decoded JSON.

**Everything, like in Python =>**

## Usage
1. Import
```php
require_once 'requests.php';
```
2. Create new session
```php
$sess = new Session();
```
3. Make any GET or POST
```php
$resp = $sess->Get('https://google.com');
```
OR
```php
$resp = $sess->Post('https://google.com');
```
4. Extract everything you need from the response
```php
$headers = $resp->headers;
$cookies = $resp->cookies;
$text = $resp->text;
//$json_decoded = $resp->json();
```

## Methods
Name|Parameters|Description
--- | --- | ---
**Get()**|$url, $headers = null|Make a GET Request
**Post()**|$url, $data = null, $headers = null|POST Request
**Reset()**||Reset Session
