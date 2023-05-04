<?php

namespace Lib;

use PDO;
use PDOException;
use SimpleORM\Model;
use FaaPz\PDO\Database as PDODatabase;
use Throwable;

class Database extends PDO
{

    private $dbname = (DB_NAME != null) ? DB_NAME : 'kpgydomy_wp801';
    private $user = (DB_USER != null) ? DB_USER : 'kpgydomy_wp801';
    private $pass = (DB_PASSWORD != null) ? DB_PASSWORD : 'V59!426Sp(';
    private $host = (DB_HOST != null) ? DB_HOST : 'localhost';

    public function __construct()
    {
        $this->dbname = (DB_NAME != null) ? DB_NAME : 'kpgydomy_wp801';
        $this->user = (DB_USER != null) ? DB_USER : 'kpgydomy_wp801';
        //validar string vacio
        $this->pass = (DB_PASSWORD != null) ? DB_PASSWORD : '';
        $this->host = (DB_HOST != null) ? DB_HOST : 'localhost';
    }

    public function connect()
    {
        try {
            $cdn = "mysql:host=" . $this->host . ";dbname=" . $this->dbname . ';charset=utf8';
            $cnx = new PDO($cdn, $this->user, $this->pass);
            $cnx->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            return $cnx;
        } catch (PDOException $e) {
            var_dump($e->getMessage());
        }
    }

    public function simplePdo()
    {
        $cdn = "mysql:host=" . $this->host . ";dbname=" . $this->dbname . ';charset=utf8';
        $database = new PDODatabase($cdn, $this->user, $this->pass);
        return $database;
    }
}
