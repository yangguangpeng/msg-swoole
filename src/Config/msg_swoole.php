<?php
return [
    'listen'=>[
        'host'=>env('SWOOLE_LISTEN_HOST','0.0.0.0'),
        'port'=>env('SWOOLE_LISTEN_PORT',9501),
    ],
    'channel'=>[
        'channel_prefix'=>'msg-swoole',
        //是否验证客户端的信息，默认开启
        'is_verification'=>true,
        'jwt_key'=>env('APP_KEY')
    ]
];

