<?php
namespace classes;

class VinhoCasta implements TemplateClasses
{

    protected $id_vinhocasta;
    protected $percentagem;
    protected $id_vinho;
    protected $id_casta;

    public function __construct($percentagem, $id_vinho, $id_casta)
    {
        $this->percentagem = $percentagem;
        $this->id_vinho = $id_vinho;
        $this->id_casta = $id_casta;

        $this->id_vinhocasta = $this->idVinhoCasta();

    }

    public function __destruct()
    {

    }

    public function idVinhoCasta()
    {
        $query = <<<SQL
        SELECT id_vinhocasta
        FROM vinho_casta
        WHERE id_vinho = :id_vinho
        AND id_casta = :id_casta
SQL;
        $con = new Connection();
        $con->beginTransaction();

        try {
            $con->query($query);
            $con->bind(':id_vinho', $this->id_vinho);
            $con->bind(':id_casta', $this->id_casta);
            $result = $con->single();
            $this->id_vinhocasta = $result['id_vinhocasta'];

            $con->endTransaction();
            return (!empty($this->id_vinhocasta)) ? $this->id_vinhocasta : false ;
        } catch (PDOException $e) {
            $con->cancelTransaction();

        }
    }

    public function insert()
    {
        if (!$this->idVinhoCasta()) {
            $query =<<<SQL
            INSERT INTO vinho_casta (percentagem,id_vinho,id_casta)
            VALUES (:percentagem, :id_vinho, :id_casta)
SQL;

            $con = new Connection();
            $con->beginTransaction();

            try {
                $con->query($query);
                $con->bind(':percentagem', $this->percentagem);
                $con->bind(':id_vinho', $this->id_vinho);
                $con->bind(':id_casta', $this->id_casta);
                $con->execute();
                $count = $con->rowCount();
                $this->id_vinhocasta = $con->lastInsertId();

                $con->endTransaction();
                return ($count > 0) ? true : false ;
            } catch (PDOException $e) {
                $con->cancelTransaction();

            }
        } else {
            return 'vinho_casta já existe!';
        }
    }

    public function update()
    {
        $query = <<<SQL
        UPDATE vinho_casta
        SET percentagem = :percentagem, id_vinho = :id_vinho, id_casta = :id_casta
        WHERE id_vinhocasta = :id_vinhocasta
SQL;

        $con = new Connection();
        $con->beginTransaction();

        try {
            $con->query($query);
            $con->bind(':percentagem', $this->percentagem);
            $con->bind(':id_vinho', $this->id_vinho);
            $con->bind(':id_casta', $this->id_casta);
            $con->bind(':id_vinhocasta', $this->id_vinhocasta);
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
        DELETE FROM vinho_casta
        WHERE id_vinhocasta = :id_vinhocasta
SQL;

        $con = new Connection();
        $con->beginTransaction();

        try {
            $con->query($query);
            $con->bind(':id_vinhocasta', $this->id_vinhocasta);

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
        $query .= ' FROM vinho_casta ';
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
                return $con->populateSelectBox($result, 'percentagem', 'id_vinhocasta');
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

            $con->query("SHOW COLUMNS FROM vinho_casta");
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
            $con->query("SELECT * FROM vinho_casta");
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

    public static function selectBox()
    {

    }

    public function getIdVinhoCasta()
    {
        return $this->id_vinhocasta;
    }

    public function setIdVinhoCasta($id_vinhocasta)
    {
        $this->id_vinhocasta = $id_vinhocasta;
    }

    public function getPercentagem()
    {
        return $this->percentagem;
    }

    public function setPercentagem($percentagem)
    {
        $this->percentagem = $percentagem;
    }

    public function getIdVinho()
    {
        return $this->id_vinho;
    }

    public function setIdVinho($id_vinho)
    {
        $this->id_vinho = $id_vinho;
    }

    public function getIdCasta()
    {
        return $this->id_casta;
    }

    public function setIdCasta($id_casta)
    {
        $this->id_casta = $id_casta;
    }
}
