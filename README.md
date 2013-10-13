#WorldPay
**A PHP 5.3+ wrapper for the [WorldPay](http://worldpay.com) payment gateway**

WorldPay is an easy to use payment gateway that is widely recognised and trusted. However, just about everything about the service is out of date. One of the most frustrating things about using WorldPay is the woeful lack of good documentation or official libraries. The result of this is, WorldPay's documentation is fragmented or incomplete and code examples are completely inadequate.

WorldPay also seems to make everything a lot more complicated than it needs to be.

I decided to make this package to abstract a lot of these complications away and instead provide a clear and easy to use API for creating WorldPay requests and listening for responses. This package will also be well unit tested and available through PHP Composer as a framework agnostic package.

**Note** If you are looking to integrate many payment gateways into your application, and you aren't going to be using all of WorldPay's features (e.g FuturePay or custom parameters) you should probably use [Omnipay](https://github.com/adrianmacneil/omnipay) instead.

##How does WorldPay work?
Creating a new payment using the WorldPay gateway basically follows these three steps:

1. You create a **Request** with information about the transaction. This could be as little as simply the amount of the transaction all the way up to a complete profile of your customer.
2. The customer is redirected to WorldPay's secure servers to enter their payment details. No customer details are ever stored on your server.
3. WorldPay will then send an optional **Response** back to your server as a callback. You can use this callback to update your database or set any processes you need to run post transaction.

This WorldPay package allows you to easily create a new **Request** and capture the resulting **Response**

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

##Creating a Request
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
