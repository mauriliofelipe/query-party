<?php 

namespace QueryParty\Database;

use PDO;
use PDOException;

class Connection
{
    /**
     * Representa a instância única da conexão com o banco de dados.
     *
     * @var mixed
     */
    private static $instance;

    /**
     * Representa a mensagem de erro da última exceção PDO ocorrida durante a conexão.
     *
     * @var string
     */
    private static string $error = '';

    /**
     * Obtém a instância única da conexão com o banco de dados.
     *
     * @return PDO|null A instância da conexão com o banco de dados ou null em caso de falha.
     */
    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            $config = DB_CONFIG;
            $dsn = "{$config['driver']}:host={$config['host']};port={$config['port']};dbname={$config['dbname']};charset=utf8mb4";

            try {
                self::$instance = new PDO($dsn, $config['username'], $config['passwd'], $config['options']);
            } catch (PDOException $e) {
                self::$error = 'Falha ao conectar ao banco de dados: ' . $e->getMessage();
            }
        }

        return self::$instance;
    }

    /**
     * Obtém a mensagem de erro da última exceção PDO.
     *
     * @return string A mensagem de erro da exceção PDO.
     */
    public static function getError(): string
    {
        return self::$error;
    }

}
