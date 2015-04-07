<?php
namespace classes;

class Produtor implements TemplateClasses
{

    protected $id_produtor;
    protected $produtor;
    protected $informacoes;
    protected $website;

    public function __construct($produtor, $informacoes, $website)
    {
        $this->produtor = $produtor;
        $this->informacoes = $informacoes;
        $this->website = $website;

        $this->id_produtor = $this->idProdutor();
    }

    public function __destruct()
    {

    }

    public function idProdutor()
    {
        $query = <<<SQL
        SELECT id_produtor
        FROM produtor
        WHERE produtor = :produtor
SQL;

        $con = new Connection();
        $con->beginTransaction();

        try {
            $con->query($query);
            $con->bind(':produtor', $this->produtor);
            $result = $con->single();
            $this->id_produtor = $result['id_produtor'];

            $con->endTransaction();
            return (!empty($this->id_produtor)) ? $this->id_produtor : false ;
        } catch (PDOException $e) {
            $con->cancelTransaction();

        }
    }

    public function insert()
    {
        if (!$this->idProdutor()) {
            $query =<<<SQL
            INSERT INTO produtor (produtor,informacoes,website)
            VALUES (:produtor, :informacoes, :website)
SQL;

            $con = new Connection();
            $con->beginTransaction();

            try {
                $con->query($query);
                $con->bind(':produtor', $this->produtor);
                $con->bind(':informacoes', $this->informacoes);
                $con->bind(':website', $this->website);
                $con->execute();
                $count = $con->rowCount();
                $this->id_produtor = $con->lastInsertId();

                $con->endTransaction();
                return ($count > 0) ? true : false ;
            } catch (PDOException $e) {
                $con->cancelTransaction();

            }
        } else {
            return 'produtor já existe!';
        }
    }

    public function update()
    {
        $query = <<<SQL
        UPDATE produtor
        SET produtor = :produtor, informacoes = :informacoes, website = :website
        WHERE id_produtor = :id_produtor
SQL;

        $con = new Connection();
        $con->beginTransaction();

        try {
            $con->query($query);
            $con->bind(':produtor', $this->produtor);
            $con->bind(':informacoes', $this->informacoes);
            $con->bind(':website', $this->website);
            $con->bind(':id_produtor', $this->id_produtor);
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
        DELETE FROM produtor
        WHERE id_produtor = :id_produtor
SQL;

        $con = new Connection();
        $con->beginTransaction();

        try {
            $con->query($query);
            $con->bind(':id_produtor', $this->id_produtor);

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
        $query .= ' FROM produtor ';
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
                return $con->populateSelectBox($result, 'produtor', 'id_produtor');
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

            $con->query("SHOW COLUMNS FROM produtor");
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
            $con->query("SELECT * FROM produtor");
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

    public function getIdProdutor()
    {
        return $this->id_produtor;
    }

    public function setIdProdutor($id_produtor)
    {
        $this->id_produtor = $id_produtor;
    }

    public function getProdutor()
    {
        return $this->produtor;
    }

    public function setProdutor($produtor)
    {
        $this->produtor = $produtor;
    }

    public function getInformacoes()
    {
        return $this->informacoes;
    }

    public function setInformacoes($informacoes)
    {
        $this->informacoes = $informacoes;
    }

    public function getWebsite()
    {
        return $this->website;
    }

    public function setWebsite($website)
    {
        $this->website = $website;
    }
}
