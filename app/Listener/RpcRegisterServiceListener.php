<?php
declare(strict_types=1);

namespace App\Listener;


use Hyperf\ServiceGovernance\Listener\RegisterServiceListener;
use InvalidArgumentException;


/**
 * Rpc服务注册到第三方注册中心重写
 *
 * Class RpcRegisterServiceListener
 *
 * @package App\Listener
 */
class RpcRegisterServiceListener extends RegisterServiceListener
{

    /**
     * 重写获取服务与配置信息 -- 使用自定义ip:port注册服务
     *
     * @return array
     */
    protected function getServers(): array
    {
        $result = [];
        $servers = $this->config->get('server.servers', []);
        foreach ($servers as $server) {
            if (! isset($server['name'], $server['host'], $server['port'])) {
                continue;
            }
            if (! $server['name']) {
                throw new InvalidArgumentException('Invalid server name');
            }
            if ($server['name'] != 'jsonrpc') {
                var_dump($server);
                $result[] = $server;
                continue;
            }
            $host = $server['custom_ip'] ?? $server['host']; // 使用自定义ip
            if (in_array($host, ['0.0.0.0', 'localhost'])) {
                $host = $this->ipReader->read();
            }
            if (! filter_var($host, FILTER_VALIDATE_IP)) {
                throw new InvalidArgumentException(sprintf('Invalid host %s', $host));
            }
            $port = $server['custom_port'] ?? $server['port']; // 使用自定义port
            if (! is_numeric($port) || ($port < 0 || $port > 65535)) {
                throw new InvalidArgumentException(sprintf('Invalid port %s', $port));
            }
            $port = (int) $port;
            $result[$server['name']] = [$host, $port];
        }

        return $result;
    }
}