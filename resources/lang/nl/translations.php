<?php

return [
    'filter' => [
        'title' => 'Datumfilter voor Umami',
        'description' => 'De ingestelde filters worden gebruikt voor de onderstaande analytics.',
        'select_range' => 'Kies Filterbereik',
        'date_format' => 'DD-MM-YYYY',
    ],
    'widget' => [
        'global' => [
            'time_range_days' => ' (:value dag)| (:value dagen)',
        ],
        'live_visitors' => [
            'label' => 'Live Bezoekers',
            'description' => 'Live bezoekers op de pagina',
        ],
        'pageviews' => [
            'label' => 'Paginaweergaven',
            'description' => 'Paginaweergaven in het geselecteerde bereik',
        ],
        'visitors' => [
            'label' => 'Unieke bezoekers',
            'description' => 'Aantal unieke bezoekers in het geselecteerde bereik',
        ],
        'visits' => [
            'label' => 'Bezoeken',
            'description' => 'Aantal sessies in het geselecteerde bereik',
        ],
        'bounces' => [
            'label' => 'Bounces',
            'description' => 'Aantal bezoekers die slechts één pagina bezoeken in het geselecteerde bereik',
        ],
        'total_time' => [
            'label' => 'Tijd doorgebracht op de website',
            'description' => 'Tijd doorgebracht op de website in het geselecteerde bereik',
        ],
        'metrics_referrer' => [
            'heading' => '',
            'headers' => [
                'metric' => 'Verwijzers',
                'count' => 'Aantal',
            ],
            'description' => 'Verwijzers in het geselecteerde bereik',
            'empty_referrer' => 'onbekend',
        ],
        'metrics_url' => [
            'heading' => '',
            'headers' => [
                'metric' => 'Bezochte URL\'s',
                'count' => 'Aantal',
            ],
            'description' => 'Bezochte URL\'s in het geselecteerde bereik',
        ],
    ],
];
