<?php

return [
    'filter' => [
        'title' => 'Datumfilter voor Umami',
        'description' => 'De ingestelde filters worden gebruikt voor de analyse hieronder.',
        'select_range' => 'Kies filterbereik',
        'date_format' => 'DD-MM-YYYY',
    ],
    'widget' => [
        /* ALGEMENE VERTALINGEN */
        'global' => [
            'time_range_days' => ' (:value dag)| (:value dagen)',
            'headers' => [
                'count' => 'Bezoekers',
            ],
            'description_postfix' => ' in het geselecteerde bereik',
            'description_prefix' => '',
            'limit' => 'top :count resultaten',
            'limit_show_all' => 'alle resultaten',
            'date_format' => 'd-m-Y',
        ],

        /* STATISTIEKEN */
        'live_visitors' => [
            'label' => 'Live Bezoekers',
            'description' => 'Live bezoekers op de pagina',
            'description_postfix' => '',
        ],
        'pageviews' => [
            'label' => 'Paginaweergaven',
            'description' => 'Pagina hits',
        ],
        'visitors' => [
            'label' => 'Unieke bezoekers',
            'description' => 'Aantal unieke bezoekers',
        ],
        'visits' => [
            'label' => 'Bezoeken',
            'description' => 'Aantal sessies',
        ],
        'bounces' => [
            'label' => 'Bounces',
            'description' => 'Aantal bezoekers die slechts één pagina bezoeken',
        ],
        'total_time' => [
            'label' => 'Tijd doorgebracht op de website',
            'description' => 'Tijd doorgebracht op de website',
        ],

        /* METRICS */
        'metrics_referrer' => [
            'heading' => '',
            'headers' => [
                'metric' => 'Verwijzers',
                'count' => 'Weergaven',
            ],
            'description' => 'Verwijzers',
            'empty_metric' => 'onbekend',
        ],
        'metrics_url' => [
            'heading' => '',
            'headers' => [
                'metric' => 'Url\'s van de bezochte pagina\'s',
                'count' => 'Weergaven',
            ],
            'description' => 'Url\'s van de bezochte pagina\'s',
        ],
        'metrics_title' => [
            'heading' => '',
            'headers' => [
                'metric' => 'Titels van de bezochte pagina\'s',
            ],
            'description' => 'Titels van de bezochte pagina\'s',
        ],
        'metrics_browser' => [
            'heading' => '',
            'headers' => [
                'metric' => 'Gebruikte browsers',
            ],
            'description' => 'Gebruikte browsers',
        ],
        'metrics_os' => [
            'heading' => '',
            'headers' => [
                'metric' => 'Gebruikte besturingssystemen',
            ],
            'description' => 'Gebruikte besturingssystemen',
        ],
        'metrics_device' => [
            'heading' => '',
            'headers' => [
                'metric' => 'Gebruikte apparaten',
            ],
            'description' => 'Gebruikte apparaten',
        ],
        'metrics_country' => [
            'heading' => '',
            'headers' => [
                'metric' => 'Landen',
            ],
            'description' => 'Landen',
        ],
        'metrics_region' => [
            'heading' => '',
            'headers' => [
                'metric' => 'Regio',
                'country' => 'Land',
            ],
            'description' => 'Regio\'s',
            'empty_metric' => 'onbekend',
        ],
        'metrics_city' => [
            'heading' => '',
            'headers' => [
                'metric' => 'Stad',
                'country' => 'Land',
            ],
            'description' => 'Steden',
            'empty_metric' => 'onbekend',
        ],
        'metrics_language' => [
            'heading' => '',
            'headers' => [
                'metric' => 'Taal',
            ],
            'description' => 'Talen',
        ],
        'metrics_screen' => [
            'heading' => '',
            'headers' => [
                'metric' => 'Schermresolutie',
            ],
            'description' => 'Schermresolutie',
        ],
        'metrics_event' => [
            'heading' => '',
            'headers' => [
                'metric' => 'Gebeurtenissen',
            ],
            'description' => 'Gebeurtenissen',
        ],
        'metrics_query' => [
            'heading' => '',
            'headers' => [
                'metric' => 'Query string',
            ],
            'description' => 'Query string',
        ],

        // METRIEKGROEPEN
        'metrics_geo' => [
            'heading' => 'Geo',
            'description' => 'Geografische informatie',
            'options' => [
                'country' => 'Landen',
                'region' => 'Regio\'s',
                'city' => 'Steden',
            ],
        ],
        'metrics_pages' => [
            'heading' => 'Pagina\'s',
            'description' => 'Bezochte pagina\'s',
            'options' => [
                'url' => 'URL',
                'title' => 'Titel',
            ],
        ],
        'metrics_client_info' => [
            'heading' => 'Client info',
            'description' => 'Client informatie',
            'options' => [
                'browser' => 'Browsers',
                'os' => 'OS',
                'device' => 'Apparaten',
                'screen' => 'Schermen',
                'language' => 'Talen',
            ],
        ],

        // CHARTS
        'chart_pageviews' => [
            'heading' => 'Pagina views (laatste 7 dagen)',
            'description' => '',
            'dataset_label' => 'Pagina views',
        ],
        'chart_sessions' => [
            'heading' => 'Sessies (laatste 7 dagen)',
            'description' => '',
            'dataset_label' => 'Sessies',
        ],
        'chart_events' => [
            'heading' => 'Events',
            'description' => '',
            'dataset_label' => 'Events',
        ],
    ],
];
