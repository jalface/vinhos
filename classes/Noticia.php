<?php
namespace classes;

class Noticia implements TemplateClasses
{

    protected $id_noticia;
    protected $noticia;
    protected $descricao;
    protected $stamptime;
    protected $id_user;

    public function __construct($noticia, $descricao, $stamptime, $id_user)
    {
        $this->noticia = $noticia;
        $this->descricao = $descricao;
        $this->stamptime = $stamptime;
        $this->id_user = $id_user;

        $this->id_noticia = $this->idNoticia();
    }

    public function __destruct()
    {
    }

    public function idNoticia()
    {
        $query = <<<SQL
        SELECT id_noticia
        FROM noticia
        WHERE noticia = :noticia
        AND id_user = :id_user
SQL;

        $con = new Connection();
        $con->beginTransaction();

        try {
            $con->query($query);
            $con->bind(':noticia', $this->noticia);
            $con->bind(':id_user', $this->id_user);
            $result = $con->single();
            $this->id_noticia = $result['id_noticia'];

            $con->endTransaction();
            return (!empty($this->id_noticia)) ? $this->id_noticia : false ;
        } catch (PDOException $e) {
            $con->cancelTransaction();

        }
    }

    public function insert()
    {
        if (!$this->idNoticia()) {
            $query = <<<SQL
        INSERT INTO noticia (noticia,descricao,stamptime,id_user)
        VALUES (:noticia, :descricao, :stamptime, :id_user)
SQL;
            $con = new Connection();
            $con->beginTransaction();

            try {
                $con->query($query);
                $con->bind(':noticia', $this->noticia);
                $con->bind(':descricao', $this->descricao);
                $con->bind(':stamptime', $this->stamptime);
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
            return 'noticia já existe!';
        }
    }

    public function update()
    {
        $query = <<<SQL
          UPDATE noticia
          SET noticia = :noticia, descricao = :descricao, stamptime = :stamptime
          WHERE id_noticia = :id_noticia
SQL;

        $con = new Connection();
        $con->beginTransaction();

        try {
            $con->query($query);
            $con->bind(':noticia', $this->noticia);
            $con->bind(':descricao', $this->descricao);
            $con->bind(':stamptime', $this->stamptime);
            $con->bind(':id_noticia', $this->id_noticia);
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
      DELETE FROM noticia
      WHERE id_noticia = :id_noticia
SQL;

        $con = new Connection();
        $con->beginTransaction();

        try {
            $con->query($query);
            $con->bind(':id_noticia', $this->id_noticia);

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
        $query .= ' FROM noticia ';
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
                return $con->populateSelectBox($result, 'noticia', 'id_noticia');
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

            $con->query("SHOW COLUMNS FROM noticia");
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
            $con->query("SELECT * FROM noticia");
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

    public function getIdNoticia()
    {
        return $this->id_noticia;
    }

    public function setIdNoticia($id_noticia)
    {
        $this->id_noticia = $id_noticia;
    }

    public function getNoticia()
    {
        return $this->noticia;
    }
    public function setNoticia($noticia)
    {
        $this->noticia = $noticia;
    }

    public function getDescricao()
    {
        return $this->descricao;
    }
    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
    }

    public function getStampTime()
    {
        return $this->stamptime;
    }
    public function setStampTime($stamptime)
    {
        $this->stamptime = $stamptime;
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
