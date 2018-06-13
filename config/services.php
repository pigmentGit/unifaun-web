<?php

return [
    'unifaun' => [
        'user_name' => env('UNIFAUN_USER'),
        'group_name' => env('UNIFAUN_GROUP_NAME'),
        'password' => env('UNIFAUN_PASSWORD'),
        'wsdl' => env('UNIFAUN_WSDL', 'https://service.apport.net:443/ws/services/ConsignmentWS?wsdl')
    ]
];