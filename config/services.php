<?php

return [
    'unifaun' => [
        'user_name' => env('UNIFAUN_USER', 'Your username'),
        'group_name' => env('UNIFAUN_GROUP_NAME', 'Your group name'),
        'password' => env('UNIFAUN_PASSWORD', 'Your password'),
        'wsdl' => env('UNIFAUN_WSDL', 'https://service.apport.net:443/ws/services/ConsignmentWS?wsdl')
    ]
];