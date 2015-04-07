<?php
namespace classes;

class SubRegiao extends Regiao implements TemplateClasses
{

    protected $id_subregiao;
    protected $subregiao;
    protected $descricao;

    public function __construct($subregiao, $descricao, $regiao, $descricao_regiao, $pais, $codigo)
    {
        $this->subregiao = $subregiao;
        $this->descricao = $descricao;

        $this->id_subregiao = $this->idSubRegiao();

        parent::__construct($regiao, $descricao_regiao, $pais, $codigo);
    }

    public function __destruct()
    {
    }

    public function idSubRegiao()
    {
        $query = <<<SQL
        SELECT id_subregiao
        FROM subregiao
        WHERE subregiao = :subregiao
SQL;

        $con = new Connection();
        $con->beginTransaction();

        try {
            $con->query($query);
            $con->bind(':subregiao', $this->subregiao);
            $result = $con->single();
            $this->id_subregiao = $result['id_subregiao'];

            $con->endTransaction();
            return (!empty($this->id_subregiao)) ? $this->id_subregiao : false ;
        } catch (PDOException $e) {
            $con->cancelTransaction();

        }
    }


    public function insert()
    {
        if (!$this->idSubRegiao()) {
            $query =<<<SQL
            INSERT INTO subregiao (subregiao,descricao,id_regiao)
            VALUES (:subregiao, :descricao, :id_regiao)
SQL;

            $con = new Connection();
            $con->beginTransaction();

            try {
                $con->query($query);
                $con->bind(':subregiao', $this->subregiao);
                $con->bind(':descricao', $this->descricao);
                $con->bind(':id_regiao', $this->id_regiao);
                $con->execute();
                $count = $con->rowCount();
                $this->id_subregiao = $con->lastInsertId();

                $con->endTransaction();
                return ($count > 0) ? true : false ;
            } catch (PDOException $e) {
                $con->cancelTransaction();

            }
        } else {
            return 'subregiao já existe!';
        }
    }

    public function update()
    {
        $query = <<<SQL
        UPDATE subregiao
        SET subregiao = :subregiao, descricao = :descricao, id_regiao = :id_regiao
        WHERE id_subregiao = :id_subregiao
SQL;

        $con = new Connection();
        $con->beginTransaction();

        try {
            $con->query($query);
            $con->bind(':subregiao', $this->subregiao);
            $con->bind(':descricao', $this->descricao);
            $con->bind(':id_regiao', $this->id_regiao);
            $con->bind(':id_subregiao', $this->id_subregiao);
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
        DELETE FROM subregiao
        WHERE id_subregiao = :id_subregiao
SQL;

        $con = new Connection();
        $con->beginTransaction();

        try {
            $con->query($query);
            $con->bind(':id_subregiao', $this->id_subregiao);

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
        $query .= ' FROM subregiao ';
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
                return $con->populateSelectBox($result, 'subregiao', 'id_subregiao');
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

            $con->query("SHOW COLUMNS FROM subregiao");
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
            $con->query("SELECT * FROM subregiao");
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

    public function getIdsubregiao()
    {
        return $this->id_subregiao;
    }

    public function setIdsubregiao($id_subregiao)
    {
        $this->id_subregiao = $id_subregiao;
    }

    public function getsubregiao()
    {
        return $this->subregiao;
    }

    public function setsubregiao($subregiao)
    {
        $this->subregiao = $subregiao;
    }

    public function getDescricao()
    {
        return $this->descricao;
    }

    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
    }
}
