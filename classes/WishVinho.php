<?php
namespace classes;

class WishVinho implements TemplateClasses
{

    private $id_wishvinho;
    private $id_wishlist;
    private $id_vinho;

    public function __construct($id_wishlist, $id_vinho)
    {
        $this->id_wishlist = $id_wishlist;
        $this->id_vinho = $id_vinho;

        $this->id_wishvinho = $this->idWishVinho();
    }

    public function __destruct()
    {

    }

    public function idWishVinho()
    {
        $query = <<<SQL
        SELECT id_wishvinho
        FROM wishlist_vinho
        WHERE id_wishlist = :id_wishlist
        AND id_vinho = :id_vinho
SQL;
        $con = new Connection();
        $con->beginTransaction();

        try {
            $con->query($query);
            $con->bind(':id_wishlist', $this->id_wishlist);
            $con->bind(':id_vinho', $this->id_vinho);
            $result = $con->single();
            $this->id_wishvinho = $result['id_wishvinho'];

            $con->endTransaction();
            return (!empty($this->id_wishvinho)) ? $this->id_wishvinho : false ;
        } catch (PDOException $e) {
            $con->cancelTransaction();

        }
    }

    public function insert()
    {
        if (!$this->idWishVinho()) {
            $query =<<<SQL
            INSERT INTO wishlist_vinho (id_wishlist,id_vinho)
            VALUES (:id_wishlist, :id_vinho)
SQL;

            $con = new Connection();
            $con->beginTransaction();

            try {
                $con->query($query);
                $con->bind(':id_wishlist', $this->id_wishlist);
                $con->bind(':id_vinho', $this->id_vinho);
                $con->execute();
                $count = $con->rowCount();
                $this->id_wishvinho = $con->lastInsertId();

                $con->endTransaction();
                return ($count > 0) ? true : false ;
            } catch (PDOException $e) {
                $con->cancelTransaction();

            }
        } else {
            return 'wishlist_vinho já existe!';
        }
    }

    public function update()
    {
        $query = <<<SQL
        UPDATE wishlist_vinho
        SET id_wishlist = :id_wishlist, id_vinho = :id_vinho
        WHERE id_wishvinho = :id_wishvinho
SQL;

        $con = new Connection();
        $con->beginTransaction();

        try {
            $con->query($query);
            $con->bind(':id_wishlist', $this->id_wishlist);
            $con->bind(':id_vinho', $this->id_vinho);
            $con->bind(':id_wishvinho', $this->id_wishvinho);
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
        DELETE FROM wishlist_vinho
        WHERE id_wishvinho = :id_wishvinho
SQL;

        $con = new Connection();
        $con->beginTransaction();

        try {
            $con->query($query);
            $con->bind(':id_wishvinho', $this->id_wishvinho);

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
        $query .= ' FROM wishlist_vinho ';
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
                return $con->populateSelectBox($result, 'wishlist_vinho', 'id_wishvinho');
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

            $con->query("SHOW COLUMNS FROM wishlist_vinho");
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
            $con->query("SELECT * FROM wishlist_vinho");
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

    public function getIdWishVinho()
    {
        return $this->id_wishvinho;
    }

    public function setIdWishVinho($id_wishvinho)
    {
        $this->id_wishvinho = $id_wishvinho;
    }

    public function getIdWishList()
    {
        return $this->id_wishlist;
    }

    public function setIdWishList($id_wishlist)
    {
        $this->id_wishlist = $id_wishlist;
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
