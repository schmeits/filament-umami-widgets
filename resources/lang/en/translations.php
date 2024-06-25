<?php

return [
    'filter' => [
        'title' => 'Date Filter for Umami',
        'description' => 'The set filters are used for the analytics shown below.',
        'select_range' => 'Choose Filter Range',
        'date_format' => 'DD-MM-YYYY',
    ],
    'widget' => [
        /* GLOBAL TRANSLATIONS */
        'global' => [
            'time_range_days' => ' (:value day)| (:value days)',
            'headers' => [
                'count' => 'Visitors',
            ],
            'description_postfix' => ' in the selected range',
            'description_prefix' => '',
            'limit' => 'top :count results',
            'limit_show_all' => 'all results',
            'date_format' => 'm-d-Y',
        ],

        /* STATS */
        'live_visitors' => [
            'label' => 'Live Visitors',
            'description' => 'Live visitors on the page',
            'description_postfix' => '',
        ],
        'pageviews' => [
            'label' => 'Pageviews',
            'description' => 'Page hits',
        ],
        'visitors' => [
            'label' => 'Unique visitors',
            'description' => 'Number of unique visitors',
        ],
        'visits' => [
            'label' => 'Visits',
            'description' => 'Number of sessions',
        ],
        'bounces' => [
            'label' => 'Bounces',
            'description' => 'Number of visitors who only visit a single page',
        ],
        'total_time' => [
            'label' => 'Time spent on the website',
            'description' => 'Time spent on the website',
        ],

        /* METRICS */
        'metrics_referrer' => [
            'heading' => '',
            'headers' => [
                'metric' => 'Referrers',
                'count' => 'Views',
            ],
            'description' => 'Referrers',
            'empty_metric' => 'unknown',
        ],
        'metrics_url' => [
            'heading' => '',
            'headers' => [
                'metric' => 'Url\'s of the visited pages',
                'count' => 'Views',
            ],
            'description' => 'Url\'s of the visited pages',
        ],
        'metrics_title' => [
            'heading' => '',
            'headers' => [
                'metric' => 'Titles of the visited pages',
            ],
            'description' => 'Titles of the visited pages',
        ],
        'metrics_browser' => [
            'heading' => '',
            'headers' => [
                'metric' => 'Browsers used',
            ],
            'description' => 'Browsers used',
        ],
        'metrics_os' => [
            'heading' => '',
            'headers' => [
                'metric' => 'Operating Systems used',
            ],
            'description' => 'Operating Systems used',
        ],
        'metrics_device' => [
            'heading' => '',
            'headers' => [
                'metric' => 'Devices used',
            ],
            'description' => 'Devices used',
        ],
        'metrics_country' => [
            'heading' => '',
            'headers' => [
                'metric' => 'Countries',
            ],
            'description' => 'Countries',
        ],
        'metrics_region' => [
            'heading' => '',
            'headers' => [
                'metric' => 'Region',
                'country' => 'Country',
            ],
            'description' => 'Regions',
            'empty_metric' => 'unknown',
        ],
        'metrics_city' => [
            'heading' => '',
            'headers' => [
                'metric' => 'City',
                'country' => 'Country',
            ],
            'description' => 'Cities',
            'empty_metric' => 'unknown',
        ],
        'metrics_language' => [
            'heading' => '',
            'headers' => [
                'metric' => 'Language',
            ],
            'description' => 'Languages',
        ],
        'metrics_screen' => [
            'heading' => '',
            'headers' => [
                'metric' => 'Screen resolution',
            ],
            'description' => 'Screen resolution',
        ],
        'metrics_event' => [
            'heading' => '',
            'headers' => [
                'metric' => 'Events',
            ],
            'description' => 'Events',
        ],
        'metrics_query' => [
            'heading' => '',
            'headers' => [
                'metric' => 'Query string',
            ],
            'description' => 'Query string',
        ],

        // METRIC GROUPS
        'metrics_geo' => [
            'heading' => 'Geo',
            'description' => 'Geo information',
            'options' => [
                'country' => 'Countries',
                'region' => 'Regions',
                'city' => 'Cities',
            ],
        ],
        'metrics_pages' => [
            'heading' => 'Pages',
            'description' => 'Visited pages',
            'options' => [
                'url' => 'URL',
                'title' => 'Title',
            ],
        ],
        'metrics_client_info' => [
            'heading' => 'Client info',
            'description' => 'Client information',
            'options' => [
                'browser' => 'Browsers',
                'os' => 'OS',
                'device' => 'Devices',
                'screen' => 'Screens',
                'language' => 'Languages',
            ],
        ],

        // CHARTS
        'chart_pageviews' => [
            'heading' => 'Pageviews (last 7 days)',
            'description' => '',
            'dataset_label' => 'Pageviews',
        ],
        'chart_sessions' => [
            'heading' => 'Sessions (last 7 days)',
            'description' => '',
            'dataset_label' => 'Sessions',
        ],
        'chart_events' => [
            'heading' => 'Events',
            'description' => '',
            'dataset_label' => 'Events',
        ],
    ],
];
