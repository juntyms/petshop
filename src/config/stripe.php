<?php

return [
    'stripe_secret_key' => env('STRIPE_SEC_KEY', 'sk_test_51LGGlRIQW336zmd9fG58rF9fz1OF1KGJsnFxE5Q9jmhlyIcLXedLcm8gIC6NKlIvLJB4BWvcYwkAnHuP5iV1BW8t00Eo6oHwJm'),
    'stripe_public_key' => env('STRIPE_PUB_KEY', 'pk_test_51LGGlRIQW336zmd9NMKtGXAnQAcmGOok95LroTDk1FWKXtW2e57b4lnoLInRTtn9W2EaIWTeqZvL94YkJMy7vu1h00t4dkkER3'),
    'stripe_success_url' => env('STRIPE_SUCCESS_URL', 'http://localhost'),
];