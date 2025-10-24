<?php

return [
    /*
    |--------------------------------------------------------------------------
    | CORS Configuration
    |--------------------------------------------------------------------------
    |
    | Bu, Cross-Origin Resource Sharing (CORS) üçün konfiqurasiya faylıdır.
    | Təhlükəsizlik baxımından, istehsal mühitində '*' istifadə etməkdən çəkinin.
    |
    */

    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    // Bütün domenlərdən (wildcard *) girişə icazə verir.
    'allowed_origins' => ['*'],

    // Bütün alt domenlərdən girişə icazə verir. Təhlükəsizlik səbəbi ilə false saxlayın.
    'allowed_origins_patterns' => [],

    // Bütün metodlara icazə verir (GET, POST, PUT, DELETE, və s.).
    'allowed_methods' => ['*'],

    // Bütün başlıqlara (headers) icazə verir.
    'allowed_headers' => ['*'],

    // Əlavə başlıqların brauzerə çıxarılmasına icazə verir.
    'exposed_headers' => [],

    // Əgər sorğu autentifikasiya məlumatı (cookies, auth headers) ehtiva edirsə.
    // 'allowed_origins' '*' olduqda true olmamalıdır.
    'supports_credentials' => false,

    // Preflight (OPTIONS) sorğuları üçün keş müddəti (saniyə ilə).
    'max_age' => 0,
];