# PHP-Curl-Wrapper

Created by Weidi Zhang

## About

Easily make HTTP requests and get response information with cURL.

## Installation

```
composer require weidizhang/php-curl-wrapper:dev-master
```

## Usage

First, require the autoloader and use the Curl class.

```
require "vendor/autoload.php";
use weidizhang\PHPCurlWrapper\Curl;
```

Create a new Curl object

```
$curl = new Curl();
```

### Changing default behavior

```
use weidizhang\PHPCurlWrapper\Behavior;

$curl->setBehavior( ... );
```

Options: KEEP_HEADERS, CLEAR_HEADERS.

See src/Behavior.php to see what these do. 

Default: CLEAR_HEADERS

### Setting various cURL options

```
$curl->setReferer( ... );
$curl->setUserAgent( ... );
$curl->setHeader( name, value );
$curl->setHeaders( array(
	"Header1: value1",
	"Header2: value2"
) );
$curl->unsetHeader( name );
$curl->setCookieFile( filename or path );
$curl->enableSSLVerify();
$curl->disableSSLVerify();
```

### Setting custom cURL options
You can pass in the option either as a constant or string.

All these do the same thing:
```
$curl->setOption(CURLOPT_FRESH_CONNECT, true);
$curl->setOption("CURLOPT_FRESH_CONNECT", true);
$curl->setOption("FRESH_CONNECT", true);
```

### Making a request

```
$response = $curl->request( type, url, query [optional], options [optional] );
```

type = GET, POST, HEAD, PUT, etc. Custom request types are supported.

url = URL.

query = Query to send, it can be an array or string.

options = An array of curl options to set. It calls ```$curl->setOption( ... );``` for them.

### Getting cURL handle

If you need access to the cURL handle for whatever reason:
```
$curl->getHandle();
```

### Handling the response

Getting body data: (Both work)
```
$body = $response;
$body = $response->getBody();
```

Getting cURL request information:
```
$info = $response->getInfo();
```

Getting all headers:
```
$headers = $response->getHeaders();
```

Getting a specific header:
```
$header = $response->getHeader( name );
```

Checking for errors (Usually not necessary):
```
if ($response->hasError()) {
	$error = $response->getError();
}
```

## License

Please read LICENSE.md to learn about what you can and cannot do with this source code.