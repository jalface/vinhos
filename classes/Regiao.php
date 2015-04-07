<?php
namespace classes;

class Regiao extends Pais implements TemplateClasses
{

    protected $id_regiao;
    protected $regiao;
    protected $descricao;


    public function __construct($regiao, $descricao, $pais, $codigo = '')
    {
        $this->regiao = $regiao;
        $this->descricao = $descricao;
        $this->id_regiao = $this->idRegiao();

        parent::__construct($pais, $codigo);
    }

    public function __destruct()
    {
    }

    public function idRegiao()
    {
        $query = <<<SQL
        SELECT id_regiao
        FROM regiao
        WHERE regiao LIKE :regiao
SQL;

        $con = new Connection();
        $con->beginTransaction();

        try {
            $con->query($query);
            $con->bind(':regiao', $this->regiao);
            $result = $con->single();
            $this->id_regiao = $result['id_regiao'];

            $con->endTransaction();
            return (!empty($this->id_regiao)) ? $this->id_regiao : false ;
        } catch (PDOException $e) {
            $con->cancelTransaction();

        }
    }

    public function insert()
    {
        if (!$this->idRegiao()) {
            $query =<<<SQL
            INSERT INTO regiao (regiao,descricao,id_pais)
            VALUES (:regiao, :descricao, :id_pais)
SQL;

            $con = new Connection();
            $con->beginTransaction();

            try {
                $con->query($query);
                $con->bind(':regiao', $this->regiao);
                $con->bind(':descricao', $this->descricao);
                $con->bind(':id_pais', $this->id_pais);
                $con->execute();
                $count = $con->rowCount();
                $this->id_regiao = $con->lastInsertId();

                $con->endTransaction();
                return ($count > 0) ? true : false ;
            } catch (PDOException $e) {
                $con->cancelTransaction();

            }
        } else {
            return 'regiao já existe!';
        }
    }

    public function update()
    {
        $query = <<<SQL
        UPDATE regiao
        SET regiao = :regiao, descricao = :descricao, id_pais = :id_pais
        WHERE id_regiao = :id_regiao
SQL;

        $con = new Connection();
        $con->beginTransaction();

        try {
            $con->query($query);
            $con->bind(':regiao', $this->regiao);
            $con->bind(':descricao', $this->descricao);
            $con->bind(':id_pais', $this->id_pais);
            $con->bind(':id_regiao', $this->id_regiao);
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
        DELETE FROM regiao
        WHERE id_regiao = :id_regiao
SQL;

        $con = new Connection();
        $con->beginTransaction();

        try {
            $con->query($query);
            $con->bind(':id_regiao', $this->id_regiao);

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
        $query .= ' FROM regiao ';

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
                return $con->populateSelectBox($result, 'regiao', 'id_regiao');
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

            $con->query("SHOW COLUMNS FROM regiao");
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
            $con->query("SELECT * FROM regiao");
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

    public function getIdRegiao()
    {
        return $this->id_regiao;
    }

    public function setIdRegiao($id_regiao)
    {
        $this->id_regiao = $id_regiao;
    }

    public function getRegiao()
    {
        return $this->regiao;
    }

    public function setRegiao($regiao)
    {
        $this->regiao = $regiao;
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
