<?php
namespace classes;

class Casta implements TemplateClasses
{

    protected $id_casta;
    protected $casta;
    protected $caracteristicas;
    protected $cor;
    protected $sinonimo;


    public function __construct($casta, $caracteristicas, $cor, $sinonimo)
    {
        $this->casta = $casta;
        $this->caracteristicas = $caracteristicas;
        $this->cor = $cor;
        $this->sinonimo = $sinonimo;
        $this->id_casta = $this->idCasta();
    }

    public function __destruct()
    {
    }

    public function idCasta()
    {
        $query = <<<SQL
        SELECT id_casta
        FROM casta
        WHERE casta = :casta
        AND cor = :cor
SQL;
        $con = new Connection();
        $con->beginTransaction();

        try {
            $con->query($query);
            $con->bind(':casta', $this->casta);
            $con->bind(':cor', $this->cor);
            $result = $con->single();
            $this->id_casta = $result['id_casta'];

            $con->endTransaction();
            return (!empty($this->id_casta)) ? $this->id_casta : false ;
        } catch (PDOException $e) {
            $con->cancelTransaction();

        }
    }

    public function insert()
    {
        if (!$this->idCasta()) {
            $query =<<<SQL
            INSERT INTO casta (casta,caracteristicas,cor,sinonimo)
            VALUES (:casta, :caracteristicas, :cor, :sinonimo)
SQL;

            $con = new Connection();
            $con->beginTransaction();

            try {
                $con->query($query);
                $con->bind(':casta', $this->casta);
                $con->bind(':caracteristicas', $this->caracteristicas);
                $con->bind(':cor', $this->cor);
                $con->bind(':sinonimo', $this->sinonimo);
                $con->execute();
                $count = $con->rowCount();
                $this->id_casta = $con->lastInsertId();

                $con->endTransaction();
                return ($count > 0) ? true : false ;
            } catch (PDOException $e) {
                $con->cancelTransaction();

            }
        } else {
            return 'Casta já existe!';
        }
    }

    public function update()
    {
        $query = <<<SQL
        UPDATE casta
        SET casta = :casta, caracteristicas = :caracteristicas, cor = :cor, sinonimo = :sinonimo
        WHERE id_casta = :id_casta
SQL;

        $con = new Connection();
        $con->beginTransaction();

        try {
            $con->query($query);
            $con->bind(':casta', $this->casta);
            $con->bind(':caracteristicas', $this->caracteristicas);
            $con->bind(':cor', $this->cor);
            $con->bind(':sinonimo', $this->sinonimo);
            $con->bind(':id_casta', $this->id_casta);
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
        DELETE FROM casta
        WHERE id_casta = :id_casta
SQL;

        $con = new Connection();
        $con->beginTransaction();

        try {
            $con->query($query);
            $con->bind(':id_casta', $this->id_casta);

            $con->endTransaction();
            return $con->execute();
        } catch (PDOException $e) {
            $con->cancelTransaction();

        }
    }

    public static function listar(array $fields, array $args, $type = 'list')
    {
        $query = 'SELECT ';
        foreach ($fields as $field_value) {
            $query .= $field_value .',';
        }
        $query = rtrim($query, ',');
        $query .= ' FROM casta ';
        $con = new Connection();
        $con->beginTransaction();
        try {
            if (!empty($args)) {
                foreach ($args as $bind => $bind_value) {
                    $options[] = " {$bind} LIKE {$bind_value}";
                }

                if (count($options) > 0) {
                    $query .= ' WHERE '. implode(' AND ', $options);
                }

                $con->query($query);

                foreach ($args as $bind => $bind_value) {
                     $con->bind($bind, $bind_value);
                }

            } else {
                $con->query($query);
            }

            $result = $con->resultset();
            $con->endTransaction();

            if (strcmp($type, 'list') == 0) {
                return $result;
            }

            if (strcmp($type, 'select') == 0) {
                return $con->populateSelectBox($result, 'casta', 'id_casta');
            }

        } catch (PDOException $e) {
            $con->cancelTransaction();

        }
    }

    public static function table()
    {
        $con = new Connection();
        $con->beginTransaction();

        try {
          // COLUNAS
            $table = <<<HTML
            <thead>
            <tr>
HTML;

            $con->query("SHOW COLUMNS FROM casta");
            $columns = $con->resultset();
            foreach ($columns as $column) {
                $table .= "<th class='text-left'>" .$column['Field']. "</th>";
            }

            $table .= <<<HTML
            </tr>
            </thead>
            <tbody>
HTML;

          // LINHAS
            $con->query("SELECT * FROM casta");
            $rows = $con->resultset();
            $rowsize = $con->rowCount();

            for ($i=0; $i<$rowsize; $i++) {
                $table .= "<tr>";
                foreach ($columns as $column) {
                    $table .= "<td>" .$rows[$i][$column['Field']]. "</td>";
                }
                $table .= "</tr>";
            }

            $table .= <<<HTML
            </tr>
            </thead>
            </tbody>
HTML;

            $con->endTransaction();
            return $table;
        } catch (PDOException $e) {
            $con->cancelTransaction();
        }
    }

    public function getIdCasta()
    {
        return $this->id_casta;
    }

    public function setIdCasta($id_casta)
    {
        $this->id_casta = $id_casta;
    }

    public function getCasta()
    {
        return $this->casta;
    }

    public function setCasta($casta)
    {
        $this->casta = $casta;
    }

    public function getCaracteristicas()
    {
        return $this->caracteristicas;
    }

    public function setCaracteristicas($caracteristicas)
    {
        $this->caracteristicas = $caracteristicas;
    }

    public function getCor()
    {
        return $this->cor;
    }

    public function setCor($cor)
    {
        $this->cor = $cor;
    }

    public function getSinonimo()
    {
        return $this->sinonimo;
    }

    public function setSinonimo($sinonimo)
    {
        $this->sinonimo = $sinonimo;
    }
}
