#WorldPay
**A PHP 5.3+ wrapper for the [WorldPay](http://worldpay.com) payment gateway**

WorldPay is an easy to use payment gateway that is widely recognised and trusted. However, just about everything about the service is out of date. One of the most frustrating things about using WorldPay is the woeful lack of good documentation or official libraries. The result of this is, WorldPay's documentation is fragmented or incomplete and code examples are completely inadequate.

WorldPay also seems to make everything a lot more complicated than it needs to be.

I decided to make this package to abstract a lot of these complications away and instead provide a clear and easy to use API for creating WorldPay requests and listening for responses. This package will also be well unit tested and available through PHP Composer as a framework agnostic package.

**Note:** If you are looking to integrate many payment gateways into your application, and you aren't going to be using all of WorldPay's features (e.g FuturePay or custom parameters) you should probably use [Omnipay](https://github.com/adrianmacneil/omnipay) instead.

##Installation
Add `philipbrown/worldpay` as a requirement to `composer.json`:

```json
{
  "require": {
    "philipbrown/worldpay": "dev-master"
  }
}
```
Update your packages with `composer update`.

##How does WorldPay work?
Creating a new payment using the WorldPay gateway basically follows these three steps:

1. You create a **Request** with information about the transaction. This could be as little as the amount of the transaction all the way up to a complete profile of your customer.
2. The customer is redirected to WorldPay's secure servers to enter their payment details. No customer details are ever stored on your server.
3. WorldPay will then send an optional **Response** back to your server as a callback. You can use this callback to update your database or set any processes you need to run post transaction.

This WorldPay package allows you to easily create a new **Request** and capture the resulting **Response**

###Creating a Request
Creating a new request is as simple as instantiating a new ```Request``` object and passing it your transaction details.
```php
// Get a new WorldPay object
$wp = new Worldpay\Worldpay;

// Create a Request
$request = $wp->request(array(
  'instId' => '123456',
  'cartId' => 'my_shop',
  'currency' => 'GBP',
  'amount' => 9.99,
));

// Send it to WorldPay
$request->send();
```

###Accepting a Response
WorldPay will send a ```POST``` request to your server with details of the transaction. You simply need to capture this request and pass it to the ```Response``` object.
```php
// Get a new WorldPay object
$wp = new Worldpay\Worldpay;

// Pass the $_POST to the Response object
$response = $wp->response($_POST);

// You now have an easy to work with object
$response->isSuccess(); // Returns TRUE / FALSE
```

##Environments
By default, WorldPay has development and production environments. This allows you to test your application using the test environment without having to process real payments.

In order to receive a WorldPay response, you must provide a callback URL in your WorldPay account.

For example, your ```production``` URL might be ```example.com``` and your dev URL might be ```dev.example.com```.

You can set your environment by simply using the ```setConfig``` method on the ```Worldpay``` object:
```php
$wp->setConfig(array('env' => 'development'));
```

If you are using Laravel 4, you could set the environment automatically like this:
```php
$wp->setConfig(array('env' => App::environment()));
```

However, it is often the case that you need to have multiple environments beyond just ```dev``` and ```production```.

For example, you might want to have a ```local``` environment or a ```test``` environment that do not actually hit the WorldPay servers.

In this case, you can pass any environment names that you want as configuration items and an override URL that will be used instead of the default WorldPay URLs:
```php
$wp->setConfig(array(
  'env' => 'local'
  'local' => 'example.local/callbacks/worldpay'
));
```

##Setting a Secret
To prevent unauthorised tampering of transaction requests, WorldPay allows you to set a secret key. This key is then used as part of the encryption of the transaction signature that you must send to WorldPay for each request.

To set a secret, go into your WorldPay Account and choose **Installations** from the menu.

Next choose your installation and complete the field marked **MD5 secret for transactions**.

When using the WorldPay package, simply set your secret using the ```setSecret``` method:
```php
$request->setSecret('my_secret');
```

##Setting the Signature Fields
The Signature Fields are a colon separated string that is used by WorldPay to authorise requests.

The signature follows this following format: ```secret:field_1:field_2:field_3```

The string is then hashed using the **MD5 hashing algorithm**.

When WorldPay recieves your transaction request, the transaction parameters are checked against the signature to ensure that nothing has been tampered with. **It is therefore important** that you ensure that all sensitive fields are included in your signature.

To specify which fields will be included in your signature, go into your WorldPay account, choose **Installations** from the menu and then choose the installation you are working. Complete the field marked **SignatureFields** with a colon seperated list.

By default, this WorldPay package will force you to use the default parameters of ```instId```, ```cartId```, ```currency```, ```amount```.

You can add your own fields by using the ```setSignatureFields``` method:
```php
$request->setSignatureFields(array('email'));
```

