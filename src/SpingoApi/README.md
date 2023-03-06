# SpingoApi module

The module provides public definitions for interfaces. All extensions or modifications should be implemented by defined interfaces.

* **SpingoApiClientInterface** - wrap methods in Spingo API
* **SpingoCartConfigProviderInterface** - cart configuration data provider
* **SpingoConnectionConfigProviderInterface** -  external connection to Spingo configuration data provider
* **SpingoNotifyOrderStatusProviderInterface** - provide order statuses based on Spingo API response
* **SpingoNotifyStatusMessageResolverInterface** - resolve order messages based on Spingo API response
* **SpingoOrderChangeStatusServiceInterface** - order status change service
* **SpingoPaymentCurrencyCheckServiceInterface** - service to check available currency for Spingo payment method
* **SpingoPaymentGrandTotalThresholdServiceInterface** - service to check the threshold of total payment for Spingo payment method
* **SpingoPaymentVatIdCheckServiceInterface** - service to check vat id for Spingo payment method
* **SpingoSubmitParameterResolverInterface** - resolve data for Spingo Api request
