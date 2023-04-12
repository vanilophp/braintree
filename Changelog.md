# Vanilo Braintree Module Changelog

## 1.2.3
##### 2023-04-12

- Fixed errors caused by `double` parameter in refund method (converted to float)

## 1.2.2
##### 2023-04-07

- Added the missing return type to the refund method

## 1.2.1
##### 2023-04-07

- Fixed double data type to float in the refund transaction method

## 1.2.0
##### 2023-04-07

- Added the `refundTransaction()` method to the gateway

## 1.1.0
##### 2023-04-06

- Added the `getTransaction()` method to the gateway
- Added processing the subtype (paymentInstrumentType) information to the payment response class
- Added Laravel 10 support

## 1.0.0
##### 2023-02-22

- Initial release
- Requires Vanilo 3.x
