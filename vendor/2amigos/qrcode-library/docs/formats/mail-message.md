MailMessageFormat
-----------------

Here is another NTT DoCoMo format where it defines a format that would also allow you to include a subject and a 
message. For example, encoding the following into a barcode and upon scanning, a mobile reader will open email client 
with recipient email address, subject and message body filled out 
- `MATMSG:TO:support@morovia.com;SUB:QRCode Generator;BODY:you guys are doing a great job!;;`.

Usage
-----

```php 

use Da\QrCode\QrCode;
use Da\QrCode\Format\MailMessageFormat; 

$format = new MailMessageFormat(['email' => 'hola@2amigos.us', 'subject' => 'test', 'body' => 'test-body']);

$qrCode = new QrCode($format);

header('Content-Type: ' . $qrCode->getContentType());

echo $qrCode->writeString();

```

© [2amigos](http://www.2amigos.us/) 2013-2017
