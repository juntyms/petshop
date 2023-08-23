# Laravel Currency package
---
#### Installation
```BASH
composer update
```
#### configuration
```BASH
php artisan vendor:publish --provider="Juntyms\CurrencyExchange\CurrencyExchangeServiceProvider"
```

#### Usage
```PHP
<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use CurrencyExchange;


class CurrenciesExchangeController extends Controller
{

    public function convert()
    {
    
        $conversion = CurrencyExchange::currency($currency)
            ->amount($amount)
            ->getConversion();

        return $conversion;
    }

```

### Testing
```
phpunit test
```
