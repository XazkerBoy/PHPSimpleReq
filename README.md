# PHPSimpleReq
PHP Class for Python 'requests'-Library lovers

# Description
This PHP tiny class allows you to create a 'Session' and use GET and POST Request. Then you can get response text, headers, cookies or decoded JSON.

**Everything, like in Python =>**

# Usage
1. Import
```
require_once 'requests.php';
```
2. Create new session
```
$sess = new Session();
```
3. Make any GET or POST
```
$resp = $sess->Get('https://google.com');
```
OR
```
$resp = $sess->Post('https://google.com')
```
4. Extract everything you need from the response
```
$headers = $resp->headers;
$cookies = $resp->cookies;
$text = $resp->text;
//$json_decoded = $resp->json();
```

# Methods
* **Get($url, $headers = null)** - Make a GET Request.
* **Post($url, $data = null, $headers = null)** - POST Request
* **Reset()** - Reset Session
