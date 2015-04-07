<?php
namespace classes;

class Pais implements TemplateClasses
{

    protected $id_pais;
    public $pais;
    protected $codigo;

    public function __construct($pais, $codigo)
    {
        $this->pais = $pais;
        $this->codigo = $codigo;
        $this->id_pais = $this->idPais();
    }

    public function __destruct()
    {
    }

    public function idPais()
    {
        $query = <<<SQL
        SELECT id_pais
        FROM pais
        WHERE pais LIKE :pais
SQL;
        $con = new Connection();
        $con->beginTransaction();
        try {
            $con->query($query);
            $con->bind(':pais', trim($this->pais));
            $result = $con->single();
            $this->id_pais = $result['id_pais'];

            $con->endTransaction();
            return (!empty($this->id_pais)) ? $this->id_pais : false ;
        } catch (PDOException $e) {
            $con->cancelTransaction();

        }
    }

    public function insert()
    {
        if (!$this->idPais()) {
            $query = <<<SQL
        INSERT INTO pais (pais,codigo)
        VALUES (:pais, :codigo)
SQL;

            $con = new Connection();
            $con->beginTransaction();

            try {
                $con->query($query);
                $con->bind(':pais', $this->pais);
                $con->bind(':codigo', $this->codigo);
                $con->execute();
                $count = $con->rowCount();
                $this->id_pais = $con->lastInsertId();

                $con->endTransaction();
                return ($count > 0) ? true : false ;
            } catch (PDOException $e) {
                $con->cancelTransaction();

            }
        } else {
            return 'pais já existe!';
        }
    }

    public function update()
    {
        $query = <<<SQL
      UPDATE pais
      SET pais = :pais, codigo = :codigo
      WHERE id_pais = :id_pais
SQL;

        $con = new Connection();
        $con->beginTransaction();

        try {
            $con->query($query);
            $con->bind(':pais', $this->pais);
            $con->bind(':codigo', $this->codigo);
            $con->bind(':id_pais', $this->id_pais);
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
      DELETE FROM pais
      WHERE id_pais = :id_pais
SQL;

        $con = new Connection();
        $con->beginTransaction();

        try {
            $con->query($query);
            $con->bind(':id_pais', $this->id_pais);

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
        $query .= ' FROM pais ';
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
                return $con->populateSelectBox($result, 'pais', 'id_pais');
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

            $con->query("SHOW COLUMNS FROM pais");
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
            $con->query("SELECT * FROM pais");
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

    public function getIdPais()
    {
        return $this->id_pais;
    }

    public function setIdPais($id_pais)
    {
        $this->id_pais = $id_pais;
    }

    public function getPais()
    {
        return $this->pais;
    }
    public function setPais($pais)
    {
        $this->pais = $pais;
    }

    public function getCodigo()
    {
        return $this->codigo;
    }
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
    }
}
