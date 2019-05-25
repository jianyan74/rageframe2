vCardFormat
-----------

vCard is a file format standard for electronic business cards. vCards are often attached to e-mail messages, but can be
exchanged in other ways, such as on the World Wide Web or instant messaging. They can contain name and address 
information, telephone numbers, e-mail addresses, URLs, logos, photographs, and audio clips.

Usage
-----

```php 

use Da\QrCode\QrCode;
use Da\QrCode\Format\vCardFormat; 

$format = new vCardFormat();
$format->name = "Antonio";
$format->fullName = "Antonio Ramirez";
$format->email = "hola@2amigos.us";

$qrCode = new QrCode($format);

header('Content-Type: ' . $qrCode->getContentType());

echo $qrCode->writeString();

```

© [2amigos](http://www.2amigos.us/) 2013-2017
