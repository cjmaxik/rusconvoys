<?php

return [
    'meta'      => [
        /*
         * https://github.com/artesaos/seotools
         */
        'defaults'       => [
            'title'       => 'Конвои по-русски [TruckersMP]', // set false to total remove
            'description' => 'Наилучший способ собрать конвой в TruckersMP', // set false to total remove
            'separator'   => ' | ',
            'keywords'    => ['truckersmp', 'truckers mp', 'convoy', 'ets2', 'euro truck simulator 2', 'ats', 'american truck simulator', 'scs', 'scs software', 'конвой', 'создать конвой', 'бб', 'большой брат', 'truckmp', 'ets2mp ru', 'truckmp ru'],
            'canonical'   => null, // Set null for using Url::current(), set false to total remove
        ],

        /*
         * Webmaster tags are always added.
         */
        'webmaster_tags' => [
            'google'    => null,
            'bing'      => null,
            'alexa'     => null,
            'pinterest' => null,
            'yandex'    => null,
        ],
    ],
    'opengraph' => [
        'defaults' => [
            'title'       => 'Конвои по-русски [TruckersMP]', // set false to total remove
            'description' => 'Наилучший способ собрать конвой в TruckersMP', // set false to total remove
            'url'         => null, // Set null for using Url::current(), set false to total remove
            'type'        => 'website',
            'site_name'   => 'Конвои по-русски [TruckersMP]',
            'images'      => [env('APP_URL') . '/pics/opg.jpg', env('APP_URL') . '/favicon-194x194.png'],
        ],
    ],
    'twitter'   => [
        'defaults' => [
            // 'card'        => 'summary',
            //'site'        => '@LuizVinicius73',
        ],
    ],
];
