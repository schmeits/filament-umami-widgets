<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Umami type
    |--------------------------------------------------------------------------
    |
    | Which version of Umami are you using?
    | Option 1: self_hosted (https://umami.is/docs)
    | Option 2: cloud (https://umami.is/docs/cloud)
    |
    */
    'type' => env('UMAMI_TYPE', 'self-hosted'),

    /*
    |--------------------------------------------------------------------------
    | Umami API Endpoint URL
    |--------------------------------------------------------------------------
    |
    | For the self hosted version you should provide the API
    | endpoint e.g. https://your_url.com/api
    |
    | If you are using the Cloud type your values should be something
    | like: https://api.umami.is/v1
    |
    */
    'api_endpoint_url' => env('UMAMI_API_ENDPOINT', 'https://api.umami.is/v1'),

    /*
    |--------------------------------------------------------------------------
    | Umami Website ID
    |--------------------------------------------------------------------------
    |
    | This is the ID of the website stats you want to show on the website
    |
    | In Umami Cloud you can find the ID by going to Websites
    | Click edit and use the Website ID provided
    |
    | In the self-hosted version navigate to Settings and edit the website
    | use the Website ID provided
    |
    */
    'website_id' => env('UMAMI_WEBSITE_ID', null),

    /*
    |--------------------------------------------------------------------------
    | Umami Http options
    |--------------------------------------------------------------------------
    |
    | The timeout options defines the default timeout for the API requests
    | in seconds
    |
    */
    'timeout' => env('UMAMI_TIMEOUT', 5),

    /*
    |--------------------------------------------------------------------------
    | Self Hosted Options
    |--------------------------------------------------------------------------
    |
    | Add a user to your Umami installation
    | https://umami.is/docs/add-a-user
    |
    */
    'username' => env('UMAMI_USERNAME', null),
    'password' => env('UMAMI_PASSWORD', null),

    /*
    |--------------------------------------------------------------------------
    | Cloud Options
    |--------------------------------------------------------------------------
    |
    | Check the website how to obtain an API key
    | https://umami.is/docs/cloud/api-key
    |
    */
    'cloud_api_key' => env('UMAMI_API_KEY', null),

    /*
    |--------------------------------------------------------------------------
    | Caching options
    |--------------------------------------------------------------------------
    |
    | You can set the options for the caching here
    | cache_time is the time the values will be cached in seconds
    |
    */
    'cache_time' => 300,

];
