# Vanilo Braintree Module Changelog

## 2.0.1
##### 2024-05-14

- Fixed the missing remote id getter on the payment request class

## 2.0.0
##### 2024-04-29

- Added Vanilo 4 support
- Added Laravel 11 support
- Dropped Vanilo 3 support
- Dropped Laravel 9 support
- Dropped PHP 8.0 & PHP 8.1 support

## 1.6.0
##### 2023-12-18

- Changed the Braintree `VOIDED` status to be mapped as `CANCELLED` instead of `DECLINED`
- Added PHP 8.3 support

## 1.5.1
##### 2023-05-04

- Fixed the voided status mapped as successful bug

## 1.5.0
##### 2023-04-19

- Added the `deletePaymentMethod()` method to the gateway
- Added an optional billing address parameter to the Gateway's `createTransaction()` method (gets forwarded to Braintree)

## 1.4.0
##### 2023-04-14

- Added the `was_successful` key to the response toArray method that deprecates the `wasSuccessfull` (typo/camelCase instead of snake_case) entry

## 1.3.0
##### 2023-04-13

- Added the proper mapping of refund transactions to payment statuses

## 1.2.4
##### 2023-04-13

- Fixed invalid return types in the refund method
- Fixed exception on credit operation types
- Fixed the getAmount() calculation on credit operation types (are negative from now on)

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
