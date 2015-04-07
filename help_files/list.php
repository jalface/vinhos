<?php
    public static function selectBox($option, $option_value = null)
    {
        $con = new Connection();
        $con->beginTransaction();

        if (is_null($option_value)) {
            $query = <<<SQL
            SELECT DISTINCT id_casta,casta
            FROM casta
SQL;
            $con->query($query);
        } else {
            $query = <<<SQL
            SELECT DISTINCT id_casta,casta
            FROM casta
            WHERE id_ = ':option_value'
SQL;
            $con->query($query);
            $con->bind(':option_value', $option_value);
        }

        try {

            $result = $con->resultset();
            $value = 'id_casta';
            $con->endTransaction();
            return $con->populateSelectBox($result, $option, $value);
        } catch (PDOException $e) {
            $con->cancelTransaction();

        }
    }


    public static function listar($fields, $args)
    {
        $query = 'SELECT ';

        foreach ($feilds as $field_value) {
          $query .=' $field_value, ';
        }

        $query .= 'FROM regiao ';


        $con = new classes\Connection();
        $con->beginTransaction();

        try {
            $con->query($query);

            $i = 0;
            if (!empty($args)) {
                foreach ($args as $bind => $bind_value) {
                    if ($i = 0) {
                      $query .= 'WHERE $bind = $bind_value ';
                      $i = 1;
                    }
                    $query .= 'AND $bind = $bind_value';
                    $con->bind($bind, $bind_value);
                }
            }
            $result = $con->resultset();
            $con->endTransaction();
        } catch (PDOException $e) {
            $con->cancelTransaction();

        }
    }

?>