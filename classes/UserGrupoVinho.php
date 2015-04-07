<?php
namespace classes;

class UserGrupoVinho implements TemplateClasses
{

    private $id_usergrupovinho;
    private $quantidade_stock;
    private $id_user;
    private $id_vinho;
    private $id_grupo;

    public function __construct($quantidade_stock, $id_user, $id_vinho, $id_grupo)
    {
        $this->quantidade_stock = $quantidade_stock;
        $this->id_user = $id_user;
        $this->id_vinho = $id_vinho;
        $this->id_grupo = $id_grupo;

        $this->id_usergrupovinho = $this->idUserGrupoVinho();
    }

    public function __destruct()
    {

    }

    public function idUserGrupoVinho()
    {
        $query = <<<SQL
        SELECT id_usergrupovinho
        FROM user_vinho
        WHERE id_vinho = :id_vinho
        AND id_grupo = :id_grupo
        AND id_user = :id_user
SQL;
        $con = new Connection();
        $con->beginTransaction();

        try {
            $con->query($query);
            $con->bind(':id_vinho', $this->id_vinho);
            $con->bind(':id_grupo', $this->id_grupo);
            $con->bind(':id_user', $this->id_user);
            $result = $con->single();
            $this->id_usergrupovinho = $result['id_usergrupovinho'];

            $con->endTransaction();
            return (!empty($this->id_usergrupovinho)) ? $this->id_usergrupovinho : false ;
        } catch (PDOException $e) {
            $con->cancelTransaction();

        }
    }

    public function insert()
    {
        if (!$this->idUserGrupoVinho()) {
            $query =<<<SQL
            INSERT INTO user_vinho  (quantidade_stock, id_user, id_vinho, id_grupo)
            VALUES (:quantidade_stock, :id_user, :id_vinho, :id_grupo)
SQL;

            $con = new Connection();
            $con->beginTransaction();

            try {
                $con->query($query);
                $con->bind(':quantidade_stock', $this->quantidade_stock);
                $con->bind(':id_vinho', $this->id_vinho);
                $con->bind(':id_grupo', $this->id_grupo);
                $con->bind(':id_user', $this->id_user);
                $con->execute();
                $count = $con->rowCount();
                $this->id_usergrupovinho = $con->lastInsertId();

                $con->endTransaction();
                return ($count > 0) ? true : false ;
            } catch (PDOException $e) {
                $con->cancelTransaction();

            }
        } else {
            return 'user_vinho  já existe!';
        }
    }

    public function update()
    {
        $query = <<<SQL
        UPDATE user_vinho
        SET quantidade_stock = :quantidade_stock, id_user = :id_user, id_vinho = :id_vinho, id_grupo = :id_grupo
        WHERE id_usergrupovinho = :id_usergrupovinho
SQL;

        $con = new Connection();
        $con->beginTransaction();

        try {
            $con->query($query);
            $con->bind(':quantidade_stock', $this->quantidade_stock);
            $con->bind(':id_vinho', $this->id_vinho);
            $con->bind(':id_grupo', $this->id_grupo);
            $con->bind(':id_user', $this->id_user);
            $con->bind(':id_usergrupovinho', $this->id_usergrupovinho);
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
        DELETE FROM user_vinho
        WHERE id_usergrupovinho = :id_usergrupovinho
SQL;

        $con = new Connection();
        $con->beginTransaction();

        try {
            $con->query($query);
            $con->bind(':id_usergrupovinho', $this->id_usergrupovinho);

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
        $query .= ' FROM user_vinho ';
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
                return $con->populateSelectBox($result, 'quantidade_stock', 'id_uservinho');
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

            $con->query("SHOW COLUMNS FROM user_vinho");
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
            $con->query("SELECT * FROM user_vinho");
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

    public function getIdUserGrupoVinho()
    {
        return $this->id_usergrupovinho;
    }

    public function setIdUserGrupoVinho($id_usergrupovinho)
    {
        $this->id_usergrupovinho = $id_usergrupovinho;
    }

    public function getQuantidadeStock()
    {
        return $this->quantidade_stock;
    }

    public function setQuantidadeStock($quantidade_stock)
    {
        $this->quantidade_stock = $quantidade_stock;
    }

    public function getIdUser()
    {
        return $this->id_user;
    }

    public function setIdUser($id_user)
    {
        $this->id_user = $id_user;
    }

    public function getIdGrupo()
    {
        return $this->id_grupo;
    }

    public function setIdGrupo($id_grupo)
    {
        $this->id_grupo = $id_grupo;
    }

    public function getIdVinho()
    {
        return $this->id_vinho;
    }

    public function setIdVinho($id_vinho)
    {
        $this->id_vinho = $id_vinho;
    }
}
