<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Laravel CORS
    |--------------------------------------------------------------------------
    |
    | allowedOrigins, allowedHeaders and allowedMethods can be set to array('*')
    | to accept any value.
    |
    */
   
    'supportsCredentials' => false,
    'allowedOrigins' => ['*'],
    'allowedHeaders' => ['Authorization', 'token_key', 'authenticated_id', 'X-Auth-Token', 'X-XSRF-TOKEN', 'Access-Control-Allow-Headers', 'Origin', 'Accept', 'X-Requested-With', 'Content-Type', 'Access-Control-Request-Method', 'Access-Control-Request-Headers'],
    'allowedMethods' => ['GET', 'POST', 'PATCH', 'PUT', 'DELETE', 'OPTIONS', 'HEAD'],
    'exposedHeaders' => [],
    'maxAge' => 0,

];
