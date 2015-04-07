<?php
namespace classes;

class Lista implements TemplateClasses
{

    private $id_lista;
    private $lista;
    private $id_user;

    public function __construct($lista, $id_user)
    {
        $this->lista = $lista;
        $this->id_user = $id_user;
    }

    public function __destruct()
    {

    }

    public function getId()
    {
        $query = <<<SQL
        SELECT id_lista
        FROM lista
        WHERE lista = ':lista'
        AND id_user = ':id_user'
SQL;
        $con = new Connection();
        $con->beginTransaction();

        try {
            $con->query($query);
            $con->bind(':lista', $this->lista);
            $con->bind(':id_user', $this->id_user);
            $con->execute();
            $this->id_lista = $con->fetchColumn();

            $con->endTransaction();
            return (!empty($this->id_lista)) ? $this->id_lista : false ;
        } catch (PDOException $e) {
            $con->cancelTransaction();

        }
    }

    public function insert()
    {
        if (!$this->getId()) {
            $query =<<<SQL
            INSERT INTO lista (lista,id_user)
            VALUES (':lista', ':id_user')
SQL;

            $con = new Connection();
            $con->beginTransaction();

            try {
                $con->query($query);
                $con->bind(':lista', $this->lista);
                $con->bind(':id_user', $this->id_user);
                $con->execute();
                $count = $con->rowCount();
                $this->id_lista = $con->lastInsertId();

                $con->endTransaction();
                return ($count > 0) ? true : false ;
            } catch (PDOException $e) {
                $con->cancelTransaction();

            }
        } else {
            return 'lista já existe!';
        }
    }

    public function update()
    {
        $query = <<<SQL
        UPDATE lista
        SET lista = ':lista', id_user = ':id_user'
        WHERE id_lista = :id_lista
SQL;

        $con = new Connection();
        $con->beginTransaction();

        try {
            $con->query($query);
            $con->bind(':lista', $this->lista);
            $con->bind(':id_user', $this->id_user);
            $con->bind(':id_lista', $this->id_lista);
            $result = $con->execute();
            $count = $con->rowCount();

            $con->endTransaction();
            return ($count > 0) ? true : false ;
        } catch (PDOException $e) {
            $con->cancelTransaction();

        }
    }

    public function delete()
    {
        $query = <<<SQL
        DELETE FROM lista
        WHERE id_lista = :id_lista
SQL;

        $con = new Connection();
        $con->beginTransaction();

        try {
            $con->query($query);
            $con->bind(':id_lista', $this->id_lista);

            $con->endTransaction();
            return $con->execute();
        } catch (PDOException $e) {
            $con->cancelTransaction();

        }
    }

    public static function listar(array $fields, array $args)
    {
        $query = <<<SQL
        SELECT id_lista,lista,id_user
        FROM lista
        WHERE {$parameter} = ':value'
SQL;

        $con = new Connection();
        return $con->listarItemsFiltro($query, $parameter, $value);
    }

    public static function selectBox($option, $option_value = null)
    {
        $con = new Connection();
        $con->beginTransaction();

        if (is_null($option_value)) {
            $query = <<<SQL
            SELECT DISTINCT id_lista,{$option}
            FROM lista
            INNER JOIN user ON lista.id_user = user.id_user
SQL;
        } else {
            $query = <<<SQL
            SELECT DISTINCT id_lista,{$option}
            FROM lista
            INNER JOIN user ON lista.id_user = user.id_user
            WHERE {$option} = ':option_value'
SQL;
            $con->bind(':option_value', $option_value);
        }

        try {
            $con->query($query);
            $result = $con->resultset();
            $value = 'id_lista';
            $con->endTransaction();
            return $con->populateSelectBox($result, $option, $value);
        } catch (PDOException $e) {
            $con->cancelTransaction();

        }
    }

    public function getIdLista()
    {
        return $this->id_lista;
    }

    public function setIdLista($id_lista)
    {
        $this->id_lista = $id_lista;
    }

    public function getLista()
    {
        return $this->lista;
    }

    public function setLista($lista)
    {
        $this->lista = $lista;
    }

    public function getIdUser()
    {
        return $this->id_user;
    }

    public function setIdUser($id_user)
    {
        $this->id_user = $id_user;
    }
}
