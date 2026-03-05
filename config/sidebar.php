<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cache TTL
    |--------------------------------------------------------------------------
    | Tempo em segundos que os widgets renderizados ficam em cache.
    | Use 0 para desabilitar o cache (útil em desenvolvimento).
    */
    'cache_ttl' => env('SIDEBAR_CACHE_TTL', 0),

    /*
    |--------------------------------------------------------------------------
    | Cache Driver
    |--------------------------------------------------------------------------
    | Driver específico para a sidebar. Deixe null para usar o driver padrão
    | configurado em config/cache.php (CACHE_DRIVER).
    */
    'cache_store' => env('SIDEBAR_CACHE_STORE', null),

    /*
    |--------------------------------------------------------------------------
    | Storage Disk
    |--------------------------------------------------------------------------
    | Disco do Storage onde as imagens dos widgets são salvas.
    */
    'storage_disk' => env('SIDEBAR_STORAGE_DISK', 'public'),

];