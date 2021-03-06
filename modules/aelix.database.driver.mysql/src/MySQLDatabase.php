<?php
/**
 * @author    aelix framework <info@aelix framework.org>
 * @copyright Copyright (c) 2015 aelix framework
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU General Public License, version 3
 */
declare(strict_types = 1);

namespace aelix\framework\database\driver;


use aelix\framework\database\Database;
use aelix\framework\exception\CoreException;
use PDO;
use PDOException;

class MySQLDatabase extends Database
{

    /**
     * Is this database driver supported on this platform?
     * @return bool
     */
    public static function isSupported(): bool
    {
        return (extension_loaded('PDO') && extension_loaded('pdo_mysql'));
    }

    /**
     * Get name of database driver currently in use
     * @return string
     */
    public function getDriverName(): string
    {
        return 'MySQL';
    }

    /**
     * @param string $host SQL server's hostname/IP or file name
     * @param string $username username for login
     * @param string $password password for login
     * @param string $database database to use
     * @param int $port port number if necessary
     * @return PDO
     * @throws CoreException
     */
    protected function connect(
        string $host = '',
        string $username = '',
        string $password = '',
        string $database = '',
        int $port = 0
    ): PDO {
        // Set default port
        if ($port == 0) {
            $port = 3306;
        }

        $options = [
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ];

        $dsn = 'mysql:host=' . $host . ';port=' . $port . ';dbname=' . $database . ';charset=utf8';

        try {
            $pdo = new PDO($dsn, $username, $password, $options);
        } catch (PDOException $e) {
            // DatabaseException depends on the PDO object, but PDO is not initialized yet. Fall back to CoreException
            throw new CoreException('Failed to connect to database: ' . $e->getMessage(), $e->getCode(), $e->errorInfo,
                $e);
        }

        return $pdo;
    }
}