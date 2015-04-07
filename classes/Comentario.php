<?php
namespace classes;

class Comentario implements TemplateClasses
{

    protected $id_comentario;
    protected $comentario;
    protected $rating;
    protected $id_vinho;
    protected $id_user;

    public function __construct($comentario, $rating, $id_vinho, $id_user)
    {
        $this->comentario = $comentario;
        $this->rating = $rating;
        $this->id_vinho = $id_vinho;
        $this->id_user = $id_user;

        $this->id_comentario = $this->idComentario();
    }

    public function __destruct()
    {
    }

    public function idComentario()
    {
        $query = <<<SQL
        SELECT id_comentario
        FROM comentario
        WHERE comentario = :comentario
        AND id_vinho = :id_vinho
        AND id_user = :id_user
SQL;
        $con = new Connection();
        $con->beginTransaction();

        try {
            $con->query($query);
            $con->bind(':comentario', $this->comentario);
            $con->bind(':id_vinho', $this->id_vinho);
            $con->bind(':id_user', $this->id_user);
            $result = $con->single();
            $this->id_comentario = $result['id_comentario'];

            $con->endTransaction();
            return (!empty($this->id_comentario)) ? $this->id_comentario : false ;
        } catch (PDOException $e) {
            $con->cancelTransaction();

        }
    }

    public function insert()
    {
        if (!$this->idComentario()) {
            $query = <<<SQL
        INSERT INTO comentario (comentario,rating,id_vinho,id_user)
        VALUES (:comentario, :rating, :id_vinho, :id_user)
SQL;

            $con = new Connection();
            $con->beginTransaction();

            try {
                $con->query($query);
                $con->bind(':comentario', $this->comentario);
                $con->bind(':rating', $this->rating);
                $con->bind(':id_vinho', $this->id_vinho);
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
      UPDATE comentario
      SET comentario = :comentario, rating = :rating
      WHERE id_comentario = :id_comentario
SQL;

        $con = new Connection();
        $con->beginTransaction();

        try {
            $con->query($query);
            $con->bind(':comentario', $this->comentario);
            $con->bind(':rating', $this->rating);
            $con->bind(':id_comentario', $this->id_comentario);
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
      DELETE FROM comentario
      WHERE id_comentario = :id_comentario
SQL;

        $con = new Connection();
        $con->beginTransaction();

        try {
            $con->query($query);
            $con->bind(':id_comentario', $this->id_comentario);

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
        $query .= ' FROM comentario ';
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
                return $con->populateSelectBox($result, 'comentario', 'id_comentario');
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

            $con->query("SHOW COLUMNS FROM comentario");
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
            $con->query("SELECT * FROM comentario");
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

    public function getIdComentario()
    {
        return $this->id_comentario;
    }

    public function setIdComentario($id_comentario)
    {
        $this->id_comentario = $id_comentario;
    }

    public function getComentario()
    {
        return $this->comentario;
    }
    public function setComentario($comentario)
    {
        $this->comentario = $comentario;
    }

    public function getRating()
    {
        return $this->rating;
    }
    public function setRating($rating)
    {
        $this->rating = $rating;
    }

    public function getIdVinho()
    {
        return $this->id_vinho;
    }
    public function setIdVinho($id_vinho)
    {
        $this->id_vinho = $id_vinho;
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
