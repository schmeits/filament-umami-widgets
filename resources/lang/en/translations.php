<?php

return [
    'filter' => [
        'title' => 'Date Filter for Umami',
        'description' => 'The set filters are used for the analytics shown below.',
        'select_range' => 'Choose Filter Range',
        'date_format' => 'DD-MM-YYYY',
    ],
    'widget' => [
        'global' => [
            'time_range_days' => ' (:value day)| (:value days)',
        ],
        'live_visitors' => [
            'label' => 'Live Visitors',
            'description' => 'Live visitors on the page',
        ],
        'pageviews' => [
            'label' => 'Pageviews',
            'description' => 'Page hits in the selected range',
        ],
        'visitors' => [
            'label' => 'Unique visitors',
            'description' => 'Number of unique visitors in the selected range',
        ],
        'visits' => [
            'label' => 'Visits',
            'description' => 'Number of sessions in the selected range',
        ],
        'bounces' => [
            'label' => 'Bounces',
            'description' => 'Number of visitors who only visit a single page in the selected range',
        ],
        'total_time' => [
            'label' => 'Time spent on the website',
            'description' => 'Time spent on the website in the selected range',
        ],
        'metrics_referrer' => [
            'heading' => '',
            'headers' => [
                'metric' => 'Referrers',
                'count' => 'Count',
            ],
            'description' => 'Referrers in the selected range',
            'empty_referrer' => 'unknown',
        ],
        'metrics_url' => [
            'heading' => '',
            'headers' => [
                'metric' => 'Visited URL\'s',
                'count' => 'Count',
            ],
            'description' => 'Visited URL\'s in the selected range',
        ],
    ],
];
