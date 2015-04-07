<?php
namespace classes;

class Grupo implements TemplateClasses
{

    protected $id_grupo;
    protected $grupo;
    protected $descricao;
    protected $id_user;

    public function __construct($grupo, $descricao, $id_user)
    {
        $this->grupo = $grupo;
        $this->descricao = $descricao;
        $this->id_user = $id_user;

        $this->id_grupo = $this->idGrupo();
    }

    public function __destruct()
    {
    }

    public function idGrupo()
    {
        $query = <<<SQL
        SELECT id_grupo
        FROM casta
        WHERE grupo = :grupo
        AND id_user = :id_user
SQL;

        $con = new Connection();
        $con->beginTransaction();

        try {
            $con->query($query);
            $con->bind(':grupo', $this->grupo);
            $con->bind(':id_user', $this->id_user);
            $result = $con->single();
            $this->id_grupo = $result['id_grupo'];

            $con->endTransaction();
            return (!empty($this->id_grupo)) ? $this->id_grupo : false ;
        } catch (PDOException $e) {
            $con->cancelTransaction();

        }
    }

    public function insert()
    {
        if (!$this->idGrupo()) {
            $query = <<<SQL
            INSERT INTO grupo (grupo,descricao,id_user)
            VALUES (:grupo, :descricao, :id_user)
SQL;

            $con = new Connection();
            $con->beginTransaction();

            try {
                $con->query($query);
                $con->bind(':grupo', $this->grupo);
                $con->bind(':descricao', $this->descricao);
                $con->bind(':id_user', $this->id_user);
                $con->execute();
                $count = $con->rowCount();
                $this->id_grupo = $con->lastInsertId();

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
        UPDATE grupo
        SET grupo = :grupo, descricao = :descricao
        WHERE id_grupo = :id_grupo
SQL;

        $con = new Connection();
        $con->beginTransaction();

        try {
            $con->query($query);
            $con->bind(':grupo', $this->grupo);
            $con->bind(':descricao', $this->descricao);
            $con->bind(':id_grupo', $this->id_grupo);
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
        DELETE FROM grupo
        WHERE id_grupo = :id_grupo
SQL;

        $con = new Connection();
        $con->beginTransaction();

        try {
            $con->query($query);
            $con->bind(':id_grupo', $this->id_grupo);

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
        $query .= ' FROM grupo ';
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
                return $con->populateSelectBox($result, 'grupo', 'id_grupo');
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

            $con->query("SHOW COLUMNS FROM grupo");
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
            $con->query("SELECT * FROM grupo");
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

    public function getIdGrupo()
    {
        return $this->id_grupo;
    }

    public function setIdGrupo($id_grupo)
    {
        $this->id_grupo = $id_grupo;
    }

    public function getGrupo()
    {
        return $this->grupo;
    }
    public function setGrupo($grupo)
    {
        $this->grupo = $grupo;
    }

    public function getDescricao()
    {
        return $this->descricao;
    }
    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
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
