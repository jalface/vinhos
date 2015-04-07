<?php
namespace classes;

class ListaVinho implements TemplateClasses
{

    private $id_listavinho;
    private $id_lista;
    private $id_vinho;

    public function __construct($id_lista, $id_vinho)
    {
        $this->id_lista = $id_lista;
        $this->id_vinho = $id_vinho;
    }

    public function __destruct()
    {

    }

    public function getId()
    {
        $query = <<<SQL
        SELECT id_listavinho
        FROM lista_vinho
        WHERE id_lista = ':id_lista'
        AND id_vinho = ':id_vinho'
SQL;
        $con = new Connection();
        $con->beginTransaction();

        try {
            $con->query($query);
            $con->bind(':id_lista', $this->id_lista);
            $con->bind(':id_vinho', $this->id_vinho);
            $con->execute();
            $this->id_listavinho = $con->fetchColumn();

            $con->endTransaction();
            return (!empty($this->id_listavinho)) ? $this->id_listavinho : false ;
        } catch (PDOException $e) {
            $con->cancelTransaction();

        }
    }

    public function insert()
    {
        if (!$this->getId()) {
            $query =<<<SQL
            INSERT INTO lista_vinho (id_lista,id_vinho)
            VALUES (':id_lista', ':id_vinho')
SQL;

            $con = new Connection();
            $con->beginTransaction();

            try {
                $con->query($query);
                $con->bind(':id_lista', $this->id_lista);
                $con->bind(':id_vinho', $this->id_vinho);
                $con->execute();
                $count = $con->rowCount();
                $this->id_listavinho = $con->lastInsertId();

                $con->endTransaction();
                return ($count > 0) ? true : false ;
            } catch (PDOException $e) {
                $con->cancelTransaction();

            }
        } else {
            return 'lista_vinho já existe!';
        }
    }

    public function update()
    {
        $query = <<<SQL
        UPDATE lista_vinho
        SET id_lista = ':id_lista', id_vinho = ':id_vinho'
        WHERE id_listavinho = :id_listavinho
SQL;

        $con = new Connection();
        $con->beginTransaction();

        try {
            $con->query($query);
            $con->bind(':id_lista', $this->id_lista);
            $con->bind(':id_vinho', $this->id_vinho);
            $con->bind(':id_listavinho', $this->id_listavinho);
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
        DELETE FROM lista_vinho
        WHERE id_listavinho = :id_listavinho
SQL;

        $con = new Connection();
        $con->beginTransaction();

        try {
            $con->query($query);
            $con->bind(':id_listavinho', $this->id_listavinho);

            $con->endTransaction();
            return $con->execute();
        } catch (PDOException $e) {
            $con->cancelTransaction();

        }
    }

    public static function listar(array $fields, array $args)
    {
        $query = <<<SQL
        SELECT id_listavinho,id_lista,id_vinho
        FROM lista_vinho
        WHERE {$parameter} = ':value'
SQL;

        $con = new Connection();
        return $con->listarItemsFiltro($query, $parameter, $value);
    }

    public static function selectBox()
    {

    }

    public function getIdListaVinho()
    {
        return $this->id_listavinho;
    }

    public function setIdListaVinho($id_listavinho)
    {
        $this->id_listavinho = $id_listavinho;
    }

    public function getIdLista()
    {
        return $this->id_lista;
    }

    public function setIdLista($id_lista)
    {
        $this->id_lista = $id_lista;
    }

    public function getIdVinho()
    {
        return $this->id_vinho;
    }

    public function setIdVinho($id_vinho)
    {
        $this->id_vinho = $id_vinho;
    }
}
