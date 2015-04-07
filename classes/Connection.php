<?php
use \PDO;

namespace classes;

class Connection
{

    private $hostname = 'localhost';
    private $database = 'dbvinhos';
    private $username = 'root';
    private $password = 'alex9331';

    private $con;
    private $error;
    private $stmt;

    public function __construct()
    {
        $dsn = 'mysql:host=' . $this->hostname . ';dbname=' . $this->database;

        $options = array(
            \PDO::ATTR_PERSISTENT    => true,
            \PDO::ATTR_ERRMODE       => \PDO::ERRMODE_EXCEPTION,
            \PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
        );

        try {
            $this->con = new \PDO($dsn, $this->username, $this->password, $options);
        } catch (\PDOException $e) {
            $this->error = $e->getMessage();
        }
    }

    public function __destruct()
    {
        $this->con = null;
    }

    public function query($query)
    {
        $this->stmt = $this->con->prepare($query);
    }

    public function bind($param, $value, $type = null)
    {
        if (is_null($type)) {
            switch (true) {
                case is_int($value):
                    $type = \PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = \PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = \PDO::PARAM_NULL;
                    break;
                default:
                    $type = \PDO::PARAM_STR;
            }
        }
        $this->stmt->bindValue($param, $value, $type);
    }

    public function resultset()
    {
        $this->execute();
        return $this->stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function single()
    {
        $this->execute();
        return $this->stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function execute()
    {
        return $this->stmt->execute();
    }

    public function rowCount()
    {
        return $this->stmt->rowCount();
    }

    public function lastInsertId()
    {
        return $this->con->lastInsertId();
    }

    public function beginTransaction()
    {
        return $this->con->beginTransaction();
    }

    public function endTransaction()
    {
        return $this->con->commit();
    }

    public function cancelTransaction()
    {
        return $this->con->rollBack();
    }

    public function debugDumpParams()
    {
        return $this->stmt->debugDumpParams();
    }

    public function populateSelectBox($result, $option, $value)
    {
        $select = '';
        if (!empty($result)) {
            foreach ($result as $row) {
                $select .= '<option value='.$row[$value].'>' .$row[$option]. '</option>';
            }
            return $select;
        }
    }

    public function getThumbnailFromDB($query)
    {
        $this->query($query);
        $result = $this->resultset();

        unset($arr);
        $arr = array();
        $i = 0;

        if (!empty($result)) {
            foreach ($result as $row) {
                $arr[] = $this->createThumbnail($row['img'], $row['caption'], $row['description'], $row['img_alt']);
            }
        }
        return $arr;
    }

    public function createThumbnail($img, $caption, $description, $img_alt = "alternative img text")
    {
        $thumb = "<div class='col-sm-3 col-md-3'>\n";
        $thumb.= "<div class='thumbnail'>\n";
        $thumb.= "<img src=". $img ." alt=". $img_alt .">\n";
        $thumb.= "<div class='caption'>\n";
        $thumb.= "<h3>". $caption ."</h3>\n";
        $thumb.= "<p>". $description .".</p>\n";
        $thumb.= "<p><button type='submit' class='btn btn-default' name=btn_". $caption .">Ver...</button></p>\n";
        $thumb.= "</div>\n";
        $thumb.= "</div>\n";
        $thumb.= "</div>\n";

        return $thumb;
    }

    public function getHostname()
    {
        return $this->hostname;
    }
    public function setHostname($hostname)
    {
        $this->hostname = $hostname;
    }

    public function getDatabase()
    {
        return $this->database;
    }

    public function setDatabase($database)
    {
        $this->database = $database;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function getCon()
    {
        return $this->con;
    }

    public function setCon($con)
    {
        $this->con = $con;
    }

    public function getResult()
    {
        return $this->result;
    }

    public function setResult($result)
    {
        $this->result = $result;
    }

    public function getError()
    {
        return $this->error;
    }

    public function setError($error)
    {
        $this->error = $error;
    }

    public function getStmt()
    {
        return $this->stmt;
    }

    public function setStmt($stmt)
    {
        $this->stmt = $stmt;
    }
}
