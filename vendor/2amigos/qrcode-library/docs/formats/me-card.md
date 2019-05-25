MeCardFormat
------------

NTT DoCoMo popularized this compacted MECARD format for encoding contact information. For example, to encode the name 
Sean Owen, address "76 9th Avenue, 4th Floor, New York, NY 10011", phone number "212 555 1212", 
e-mail srowen@example.com, one would encode this in a barcode: 

```
MECARD:N:Owen,Sean;ADR:76 9th Avenue, 4th Floor, New York, NY 10011;TEL:12125551212;EMAIL:srowen@example.com;;
```

Usage
-----

```php 

use Da\QrCode\QrCode;
use Da\QrCode\Format\MeCardFormat; 

$format = new MeCardFormat();
$format->firstName = 'Antonio';
$format->lastName = 'Ramirez';
$format->sound = 'docomotaro';
$format->phone = '657657XXX';
$format->videoPhone = '657657XXX';
$format->email = 'hola@2amigos.us';
$format->note = 'test-note';
$format->birthday = '19791201';
$format->address = 'test-address';
$format->url = 'http://2amigos.us';
$format->nickName = 'tonydspaniard';

$qrCode = new QrCode($format);

header('Content-Type: ' . $qrCode->getContentType());

echo $qrCode->writeString();

```


© [2amigos](http://www.2amigos.us/) 2013-2017
