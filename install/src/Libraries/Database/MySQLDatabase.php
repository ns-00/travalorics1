<?php

namespace Travalorics\Install\Libraries\Database;

use PDO;
use PDOException;

class MySQLDatabase
{
    private ?PDO $pdo = null;

    public function setPdo(?PDO $pdo): void
    {
        $this->pdo = $pdo;
    }

    public function checkConnection(array $data): array
    {
        $result = ['db_success' => false];

        try {
            if ($this->pdo === null) {
                $dsn = sprintf(
                    'mysql:host=%s;port=%s;dbname=%s',
                    $data['db_hostname'],
                    $data['db_port'],
                    $data['db_name']
                );

                $password = $data['db_password'] ?? '';

                $this->pdo = new PDO(
                    $dsn,
                    $data['db_username'],
                    $password,
                    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
                );
            }

            $version = $this->pdo->getAttribute(PDO::ATTR_SERVER_VERSION);
            if (version_compare($version, '5.7.0', '<')) {
                $result['db_version'] = trans('install/common.invalid_version');

                return $result;
            }

            $result['db_success'] = true;
        } catch (PDOException $e) {
            switch ($e->getCode()) {
                case 2002:
                    $result['db_hostname'] = trans('install/common.failed_host_port');
                    $result['db_port']     = trans('install/common.failed_host_port');
                    break;
                case 1045:
                    $result['db_username'] = trans('install/common.failed_user_password');
                    $result['db_password'] = trans('install/common.failed_user_password');
                    break;
                case 1049:
                    $result['db_name'] = trans('install/common.failed_db_name');
                    break;
                default:
                    $result['db_error'] = trans('install/common.db_connect_error');
            }
        }

        return $result;
    }

    public function getConfig(array $data): array
    {
        return [
            'DB_CONNECTION' => 'mysql',
            'DB_HOST'       => $data['db_hostname'],
            'DB_PORT'       => $data['db_port'],
            'DB_DATABASE'   => $data['db_name'],
            'DB_USERNAME'   => $data['db_username'],
            'DB_PASSWORD'   => $data['db_password'] ?? '',
        ];
    }
}
