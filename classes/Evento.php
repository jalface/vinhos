<?php
namespace classes;

class Evento implements TemplateClasses
{

    protected $id_evento;
    protected $evento;
    protected $descricao;
    protected $stamptime;
    protected $start_date;
    protected $end_date;
    protected $id_user;

    public function __construct($evento, $descricao, $stamptime, $start_date, $end_date, $id_user)
    {
        $this->event = $evento;
        $this->descricao = $descricao;
        $this->stamptime = $stamptime;
        $this->start_date = $start_date;
        $this->end_date = $end_date;
        $this->id_user = $id_user;

        $this->id_evento = $this->idEvento();
    }

    public function __destruct()
    {
    }

    public function idEvento()
    {
        $query = <<<SQL
        SELECT id_evento
        FROM evento
        WHERE evento = :evento
        AND id_user = :id_user
SQL;

        $con = new Connection();
        $con->beginTransaction();

        try {
            $con->query($query);
            $con->bind(':evento', $this->evento);
            $con->bind(':id_user', $this->id_user);
            $result = $con->single();
            $this->id_evento = $result['id_evento'];

            $con->endTransaction();
            return (!empty($this->id_evento)) ? $this->id_evento : false ;
        } catch (PDOException $e) {
            $con->cancelTransaction();

        }
    }

    public function insert()
    {
        if (!$this->idEvento()) {
            $query = <<<SQL
        INSERT INTO evento (evento,descricao,stamptime,start_date,end_date,id_user)
        VALUES (:evento, :descricao, :stamptime, :start_date, :end_date, :id_user)
SQL;
            $con = new Connection();
            $con->beginTransaction();

            try {
                $con->query($query);
                $con->bind(':evento', $this->evento);
                $con->bind(':descricao', $this->descricao);
                $con->bind(':stamptime', $this->stamptime);
                $con->bind(':start_date', $this->start_date);
                $con->bind(':end_date', $this->end_date);
                $con->bind(':id_user', $this->id_user);
                $con->execute();
                $count = $con->rowCount();
                $this->id_comentario = $con->lastInsertId();

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
          UPDATE evento
          SET evento = :evento, descricao = :descricao, stamptime = :stamptime, start_date = :start_date, end_date = :end_date
          WHERE id_evento = :id_evento
SQL;

        $con = new Connection();
        $con->beginTransaction();

        try {
            $con->query($query);
            $con->bind(':evento', $this->evento);
            $con->bind(':descricao', $this->descricao);
            $con->bind(':stamptime', $this->stamptime);
            $con->bind(':start_date', $this->start_date);
            $con->bind(':end_date', $this->end_date);
            $con->bind(':id_evento', $this->id_evento);
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
      DELETE FROM evento
      WHERE id_evento = :id_evento
SQL;

        $con = new Connection();
        $con->beginTransaction();

        try {
            $con->query($query);
            $con->bind(':id_evento', $this->id_evento);

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
        $query .= ' FROM evento ';
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
                return $con->populateSelectBox($result, 'evento', 'id_evento');
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

            $con->query("SHOW COLUMNS FROM evento");
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
            $con->query("SELECT * FROM evento");
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

    public function getIdEvento()
    {
        return $this->id_evento;
    }

    public function setIdEvento($id_evento)
    {
        $this->id_evento = $id_evento;
    }

    public function getEvento()
    {
        return $this->evento;
    }
    public function setEvento($evento)
    {
        $this->evento = $evento;
    }

    public function getDescricao()
    {
        return $this->descricao;
    }
    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
    }

    public function getstamptime()
    {
        return $this->stamptime;
    }
    public function setstamptime($stamptime)
    {
        $this->stamptime = $stamptime;
    }

    public function getStartDate()
    {
        return $this->start_date;
    }
    public function setStartDate($start_date)
    {
        $this->start_date = $start_date;
    }

    public function getEndDate()
    {
        return $this->end_date;
    }
    public function setEndDate($end_date)
    {
        $this->end_date = $end_date;
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
