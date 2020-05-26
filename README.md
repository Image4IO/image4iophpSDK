![altText](https://cdn.image4.io/i4io/logo-dark-side.png "Logo")

![altText](https://img.shields.io/packagist/php-v/image4io/image4iophp-sdk?color=orange "Version") 
![altText](https://img.shields.io/github/license/Image4IO/image4iophpSDK "License") ![altText](https://img.shields.io/github/languages/top/Image4IO/image4iophpSDK "Lang")

# Image4ioPhpSDK 
image4io is a cloud service where your images are uploaded, moved, copied, fetched, deleted.

## Installation
Composer package can be installed via following command

```composer require image4io/image4iophp-sdk```

## Configuration
To send requests to API, APIKey and APISecret must be given as string first. Required keys can be retrieved from image4io console.
```php
use Image4IO\Image4ioApi;

$apiKey = 'apiKey';
$apiSecret = 'apiSecret';

$api = new Image4ioApi($apiKey, $apiSecret);
$result = $api->getImage('/example.png');
```

## Available Requests
This SDK currently supports 16 requests.

* GetSubscription
* GetImage
* UploadImage
* FetchImage
* CopyImage
* MoveImage
* DeleteImage
* CreateFolder
* DeleteFolder
* ListFolder
* StartUploadStream
* UploadStreamPart
* FinalizeStream
* GetStream
* FetchStream
* DeleteStream

Documentation is available at: [image4io API Documentation](https://image4.io/en/documentation)

## Contact Us
Image4io team is always ready to support you, feel free to 
[contact us.](https://image4.io/en/contact)

## Follow Us
* [Image4io Blog](https://image4.io/en/blog)

* [Twitter](https://twitter.com/image4io)

* [LinkedIn]( linkedin.com/company/image4io/)

## License
[MIT](https://choosealicense.com/licenses/mit/)
