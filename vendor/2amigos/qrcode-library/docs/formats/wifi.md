WifiFormat
----------

Only works on Android devices and is used for wifi configuration. Scanning such a code would, after prompting the user, 
configure the device's wi-fi accordingly. 

- **authentication**: The authentication type; can be WEP, WPA, or 'nopass' for no password. Or, omit for no 
password.
- **ssid**: The network SSID. It is required. Enclosed in double quotes if its an ASCII name, but could be interpreted 
as hex (i.e. "ABCD").
- **password**: The password. This is ignored if `authentication` is set to 'nopass'. Enclose in double quotes if it is 
an ASCII name, but could be interpreted as hex (i.e. "ABCD")
- **hidden**: Optional. Set to true if the SSID is hidden.

Special characters "", ";", "," and ":" should be escaped with a backslash (""). For example, if an SSID was literally 
"foo;bar\baz" (with double quotes part of the SSID name itself) then it would be encoded 
like: \"foo\;bar\\baz\"

Usage
-----

```php 

use Da\QrCode\QrCode;
use Da\QrCode\Format\WifiFormat; 

$format = new WifiFormat(['authentication' => 'WPA', 'ssid' => 'testSSID', 'password' => 'HAKUNAMATATA']);

$qrCode = new QrCode($format);

header('Content-Type: ' . $qrCode->getContentType());

echo $qrCode->writeString();

```

© [2amigos](http://www.2amigos.us/) 2013-2017
