<?php
namespace classes;

class ProdutorSubRegiao implements TemplateClasses
{

    private $id_produtor_subregiao;
    private $id_produtor;
    private $id_subregiao;

    public function __construct($id_produtor, $id_subregiao)
    {
        $this->id_produtor = $id_produtor;
        $this->id_subregiao = $id_subregiao;

        $this->id_produtor_subregiao = $this->idProdutorSubregiao();
    }

    public function __destruct()
    {

    }

    public function idProdutorSubregiao()
    {
        $query = <<<SQL
        SELECT id_produtor_subregiao
        FROM produtor_subregiao
        WHERE id_produtor = :id_produtor
        AND id_subregiao = :id_subregiao
SQL;
        $con = new Connection();
        $con->beginTransaction();

        try {
            $con->query($query);
            $con->bind(':id_produtor', $this->id_produtor);
            $con->bind(':id_subregiao', $this->id_subregiao);
            $result = $con->single();
            $this->id_produtor_subregiao = $result['id_produtor_subregiao'];

            $con->endTransaction();
            return (!empty($this->id_produtor_subregiao)) ? $this->id_produtor_subregiao : false ;
        } catch (PDOException $e) {
            $con->cancelTransaction();

        }
    }

    public function insert()
    {
        if (!$this->idProdutorSubregiao()) {
            $query =<<<SQL
            INSERT INTO produtor_subregiao (id_produtor,id_subregiao)
            VALUES (:id_produtor, :id_subregiao)
SQL;

            $con = new Connection();
            $con->beginTransaction();

            try {
                $con->query($query);
                $con->bind(':id_produtor', $this->id_produtor);
                $con->bind(':id_subregiao', $this->id_subregiao);
                $con->execute();
                $count = $con->rowCount();
                $this->id_produtor_subregiao = $con->lastInsertId();

                $con->endTransaction();
                return ($count > 0) ? true : false ;
            } catch (PDOException $e) {
                $con->cancelTransaction();

            }
        } else {
            return 'produtor_subregiao já existe!';
        }
    }

    public function update()
    {
        $query = <<<SQL
        UPDATE produtor_subregiao
        SET id_produtor = :id_produtor, id_subregiao = :id_subregiao
        WHERE id_produtor_subregiao = :id_produtor_subregiao
SQL;

        $con = new Connection();
        $con->beginTransaction();

        try {
            $con->query($query);
            $con->bind(':id_produtor', $this->id_produtor);
            $con->bind(':id_subregiao', $this->id_subregiao);
            $con->bind(':id_produtor_subregiao', $this->id_produtor_subregiao);
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
        DELETE FROM produtor_subregiao
        WHERE id_produtor_subregiao = :id_produtor_subregiao
SQL;

        $con = new Connection();
        $con->beginTransaction();

        try {
            $con->query($query);
            $con->bind(':id_produtor_subregiao', $this->id_produtor_subregiao);

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
        $query .= ' FROM produtor_subregiao ';
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
                return $con->populateSelectBox($result, 'id_produtor', 'id_produtor_subregiao');
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

            $con->query("SHOW COLUMNS FROM produtor_subregiao");
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
            $con->query("SELECT * FROM produtor_subregiao");
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

    public function getIdProdutorSubRegiao()
    {
        return $this->id_produtor_subregiao;
    }

    public function setIdProdutorSubRegiao($id_produtor_subregiao)
    {
        $this->id_produtor_subregiao = $id_produtor_subregiao;
    }

    public function getIdProdutor()
    {
        return $this->id_produtor;
    }

    public function setIdProdutor($id_produtor)
    {
        $this->id_produtor = $id_produtor;
    }

    public function getIdSubRegiao()
    {
        return $this->id_subregiao;
    }

    public function setIdSubRegiao($id_subregiao)
    {
        $this->id_subregiao = $id_subregiao;
    }
}
