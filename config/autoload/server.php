<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
use Hyperf\Server\Event;
use Hyperf\Server\Server;
use Swoole\Constant;
return [
    'mode' => SWOOLE_BASE,
    'servers' => [
        [
            'name' => 'jsonrpc',
            'type' => Server::SERVER_BASE,
            'host' => '0.0.0.0',
            'port' => 9601,
            'sock_type' => SWOOLE_SOCK_TCP,
            'callbacks' => [
                Event::ON_RECEIVE => [Hyperf\RpcMultiplex\TcpServer::class, 'onReceive'],
            ],
            // open this setting when you run on server.
            // 'custom_ip' => env('RPC_CUSTOM_IP','host.docker.internal'),
            // 'custom_port' => env('RPC_CUSTOM_PORT','9601')
            'settings' => [
                'open_length_check' => true,
                'package_length_type' => 'N',
                'package_length_offset' => 0,
                'package_body_offset' => 4,
                'package_max_length' => 1024 * 1024 * 1024 * 2,
            ],
            'options' => [
                // 多路复用下，避免跨协程 Socket 跨协程多写报错
                'send_channel_capacity' => 65535,
            ],
        ],
    ],
];
// return [
//     'mode' => SWOOLE_PROCESS,
//     'servers' => [
//         // [
//         //     'name' => 'http',
//         //     'type' => Server::SERVER_HTTP,
//         //     'host' => '0.0.0.0',
//         //     'port' => 9501,
//         //     'sock_type' => SWOOLE_SOCK_TCP,
//         //     'callbacks' => [
//         //         Event::ON_REQUEST => [Hyperf\HttpServer\Server::class, 'onRequest'],
//         //     ],
//         //     'options' => [
//         //         // Whether to enable request lifecycle event
//         //         'enable_request_lifecycle' => false,
//         //     ],
//         // ],
//         [
//             'name' => 'jsonrpc-http',
//             'type' => Server::SERVER_HTTP,
//             'host' => '0.0.0.0',
//             'port' => 9601,
//             'sock_type' => SWOOLE_SOCK_TCP,
//             'callbacks' => [
//                 Event::ON_REQUEST => [\Hyperf\JsonRpc\HttpServer::class, 'onRequest'],
//             ],
//             'custom_ip' => env('RPC_CUSTOM_IP','host.docker.internal'),
//             'custom_port' => env('RPC_CUSTOM_PORT','9601'),
//         ],

//     ],
//     'settings' => [
//         Constant::OPTION_ENABLE_COROUTINE => true,
//         Constant::OPTION_WORKER_NUM => swoole_cpu_num(),
//         Constant::OPTION_PID_FILE => BASE_PATH . '/runtime/hyperf.pid',
//         Constant::OPTION_OPEN_TCP_NODELAY => true,
//         Constant::OPTION_MAX_COROUTINE => 100000,
//         Constant::OPTION_OPEN_HTTP2_PROTOCOL => true,
//         Constant::OPTION_MAX_REQUEST => 100000,
//         Constant::OPTION_SOCKET_BUFFER_SIZE => 2 * 1024 * 1024,
//         Constant::OPTION_BUFFER_OUTPUT_SIZE => 2 * 1024 * 1024,
//     ],
//     'callbacks' => [
//         Event::ON_WORKER_START => [Hyperf\Framework\Bootstrap\WorkerStartCallback::class, 'onWorkerStart'],
//         Event::ON_PIPE_MESSAGE => [Hyperf\Framework\Bootstrap\PipeMessageCallback::class, 'onPipeMessage'],
//         Event::ON_WORKER_EXIT => [Hyperf\Framework\Bootstrap\WorkerExitCallback::class, 'onWorkerExit'],
//     ],
// ];
