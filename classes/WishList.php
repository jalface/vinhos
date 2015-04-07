<?php
namespace classes;

class WishList implements TemplateClasses
{

    private $id_wishlist;
    private $wishlist;
    private $public;
    private $id_user;

    public function __construct($wishlist, $public, $id_user)
    {
        $this->wishlist = $wishlist;
        $this->public = $public;
        $this->id_user = $id_user;

        $this->id_wishlist = $this->idWishList();
    }

    public function __destruct()
    {

    }

    public function idWishList()
    {
        $query = <<<SQL
        SELECT id_wishlist
        FROM wishlist
        WHERE wishlist = :wishlist
        AND id_user = :id_user
SQL;
        $con = new Connection();
        $con->beginTransaction();

        try {
            $con->query($query);
            $con->bind(':wishlist', $this->wishlist);
            $con->bind(':id_user', $this->id_user);
            $result = $con->single();
            $this->id_wishlist = $result['id_wishlist'];

            $con->endTransaction();
            return (!empty($this->id_wishlist)) ? $this->id_wishlist : false ;
        } catch (PDOException $e) {
            $con->cancelTransaction();

        }
    }

    public function insert()
    {
        if (!$this->idWishList()) {
            $query =<<<SQL
            INSERT INTO wishlist (wishlist,public,id_user)
            VALUES (:wishlist, :public, :id_user)
SQL;

            $con = new Connection();
            $con->beginTransaction();

            try {
                $con->query($query);
                $con->bind(':wishlist', $this->wishlist);
                $con->bind(':public', $this->public);
                $con->bind(':id_user', $this->id_user);
                $con->execute();
                $count = $con->rowCount();
                $this->id_wishlist = $con->lastInsertId();

                $con->endTransaction();
                return ($count > 0) ? true : false ;
            } catch (PDOException $e) {
                $con->cancelTransaction();

            }
        } else {
            return 'wishlist já existe!';
        }
    }

    public function update()
    {
        $query = <<<SQL
        UPDATE wishlist
        SET wishlist = :wishlist, public = :public, id_user = :id_user
        WHERE id_wishlist = :id_wishlist
SQL;

        $con = new Connection();
        $con->beginTransaction();

        try {
            $con->query($query);
            $con->bind(':wishlist', $this->wishlist);
            $con->bind(':public', $this->public);
            $con->bind(':id_user', $this->id_user);
            $con->bind(':id_wishlist', $this->id_wishlist);
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
        DELETE FROM wishlist
        WHERE id_wishlist = :id_wishlist
SQL;

        $con = new Connection();
        $con->beginTransaction();

        try {
            $con->query($query);
            $con->bind(':id_wishlist', $this->id_wishlist);

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
        $query .= ' FROM wishlist ';
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
                return $con->populateSelectBox($result, 'wishlist', 'id_wishlist');
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

            $con->query("SHOW COLUMNS FROM wishlist");
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
            $con->query("SELECT * FROM wishlist");
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

    public function getIdWishList()
    {
        return $this->id_wishlist;
    }

    public function setIdWishList($id_wishlist)
    {
        $this->id_wishlist = $id_wishlist;
    }

    public function getWishList()
    {
        return $this->wishlist;
    }

    public function setWishList($wishlist)
    {
        $this->wishlist = $wishlist;
    }

    public function getPublic()
    {
        return $this->public;
    }

    public function setPublic($public)
    {
        $this->public = $public;
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
