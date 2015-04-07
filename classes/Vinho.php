<?php
namespace classes;

class Vinho implements TemplateClasses
{

    protected $id_vinho;
    protected $vinho;
    protected $ano;
    protected $tipo;
    protected $alcool;
    protected $volume;
    protected $caracteristicas;
    protected $premios;
    protected $front_label;
    protected $back_label;
    protected $valor_aprox;
    protected $stamptime;
    protected $vinificacao;
    protected $servico_consumo;
    protected $gastronomia;
    protected $public;
    protected $id_produtor_regiao;

    public function __construct($vinho, $ano, $tipo, $alcool, $volume, $caracteristicas, $premios, $front_label, $back_label, $valor_aprox, $stamptime, $vinificacao, $servico_consumo, $gastronomia, $public, $id_produtor_regiao)
    {
        $this->vinho = $vinho;
        $this->ano = $ano;
        $this->tipo = $tipo;
        $this->alcool = $alcool;
        $this->volume = $volume;
        $this->caracteristicas = $caracteristicas;
        $this->premios = $premios;
        $this->front_label = $front_label;
        $this->back_label = $back_label;
        $this->valor_aprox = $valor_aprox;
        $this->stamptime = $stamptime;
        $this->vinificacao = $vinificacao;
        $this->servico_consumo = $servico_consumo;
        $this->gastronomia = $gastronomia;
        $this->public = $public;
        $this->id_produtor_regiao = $id_produtor_regiao;

        $this->id_vinho = $this->idVinho();
    }

    public function __destruct()
    {

    }

    public function idVinho()
    {
        $query = <<<SQL
        SELECT id_vinho
        FROM vinho
        WHERE vinho = :vinho
        AND tipo = :tipo
SQL;

        $con = new Connection();
        $con->beginTransaction();

        try {
            $con->query($query);
            $con->bind(':vinho', $this->vinho);
            $con->bind(':tipo', $this->tipo);
            $result = $con->single();
            $this->id_vinho = $result['id_vinho'];

            $con->endTransaction();
            return (!empty($this->id_vinho)) ? $this->id_vinho : false ;
        } catch (PDOException $e) {
            $con->cancelTransaction();

        }
    }

    public function insert()
    {
        if (!$this->idVinho()) {
            $query =<<<SQL
            INSERT INTO vinho (vinho, ano, tipo, alcool, volume, caracteristicas, premios, front_label, back_label, valor_aprox, stamptime, vinificacao, servico_consumo, gastronomia, public, id_produtor_regiao)
            VALUES (:vinho, :ano, :tipo, :alcool, :volume, :caracteristicas, :premios, :front_label, :back_label, :valor_aprox, :stamptime, :vinificacao, :servico_consumo, :gastronomia, :public, :id_produtor_regiao)
SQL;

            $con = new Connection();
            $con->beginTransaction();

            try {
                $con->query($query);
                $con->bind(':vinho', $this->vinho);
                $con->bind(':ano', $this->ano);
                $con->bind(':tipo', $this->tipo);
                $con->bind(':alcool', $this->alcool);
                $con->bind(':volume', $this->volume);
                $con->bind(':caracteristicas', $this->caracteristicas);
                $con->bind(':premios', $this->premios);
                $con->bind(':front_label', $this->front_label);
                $con->bind(':back_label', $this->back_label);
                $con->bind(':valor_aprox', $this->valor_aprox);
                $con->bind(':stamptime', $this->stamptime);
                $con->bind(':vinificacao', $this->vinificacao);
                $con->bind(':servico_consumo', $this->servico_consumo);
                $con->bind(':gastronomia', $this->gastronomia);
                $con->bind(':public', $this->public);
                $con->bind(':id_produtor_regiao', $this->id_produtor_regiao);
                $con->execute();
                $count = $con->rowCount();
                $this->id_vinho = $con->lastInsertId();

                $con->endTransaction();
                return ($count > 0) ? true : false ;
            } catch (PDOException $e) {
                $con->cancelTransaction();

            }
        } else {
            return 'vinho já existe!';
        }
    }

    public function update()
    {
        $query = <<<SQL
        UPDATE vinho
        SET vinho = :vinho, ano = :ano, tipo = :tipo, alcool = :alcool, volume = :volume, caracteristicas = :caracteristicas, premios = :premios, front_label = :front_label, back_label = :back_label, valor_aprox = :valor_aprox, stamptime = :stamptime, vinificacao = :vinificacao, servico_consumo = :servico_consumo, gastronomia = :gastronomia, public = :public, id_produtor_regiao = :id_produtor_regiao
        WHERE id_vinho = :id_vinho
SQL;

        $con = new Connection();
        $con->beginTransaction();

        try {
            $con->query($query);
            $con->bind(':vinho', $this->vinho);
            $con->bind(':ano', $this->ano);
            $con->bind(':tipo', $this->tipo);
            $con->bind(':alcool', $this->alcool);
            $con->bind(':volume', $this->volume);
            $con->bind(':caracteristicas', $this->caracteristicas);
            $con->bind(':premios', $this->premios);
            $con->bind(':front_label', $this->front_label);
            $con->bind(':back_label', $this->back_label);
            $con->bind(':valor_aprox', $this->valor_aprox);
            $con->bind(':stamptime', $this->stamptime);
            $con->bind(':vinificacao', $this->vinificacao);
            $con->bind(':servico_consumo', $this->servico_consumo);
            $con->bind(':gastronomia', $this->gastronomia);
            $con->bind(':public', $this->public);
            $con->bind(':id_produtor_regiao', $this->id_produtor_regiao);
            $con->bind(':id_vinho', $this->id_vinho);
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
        DELETE FROM vinho
        WHERE id_vinho = :id_vinho
SQL;

        $con = new Connection();
        $con->beginTransaction();

        try {
            $con->query($query);
            $con->bind(':id_vinho', $this->id_vinho);

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
        $query .= ' FROM vinho ';
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
                return $con->populateSelectBox($result, 'vinho', 'id_vinho');
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

            $con->query("SHOW COLUMNS FROM vinho");
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
            $con->query("SELECT * FROM vinho");
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

    public function getIdVinho()
    {
        return $this->id_vinho;
    }

    public function setIdVinho($id_vinho)
    {
        $this->id_vinho = $id_vinho;
    }

    public function getvinho()
    {
        return $this->vinho;
    }

    public function setvinho($vinho)
    {
        $this->vinho = $vinho;
    }

    public function getAno()
    {
        return $this->ano;
    }

    public function setAno($ano)
    {
        $this->ano = $ano;
    }

    public function getTipo()
    {
        return $this->tipo;
    }

    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
    }

    public function getAlcool()
    {
        return $this->alcool;
    }

    public function setAlcool($alcool)
    {
        $this->alcool = $alcool;
    }

    public function getVolume()
    {
        return $this->volume;
    }

    public function setVolume($volume)
    {
        $this->volume = $volume;
    }

    public function getCaracteristicas()
    {
        return $this->caracteristicas;
    }

    public function setCaracteristicas($caracteristicas)
    {
        $this->caracteristicas = $caracteristicas;
    }

    public function getPremios()
    {
        return $this->premios;
    }

    public function setPremios($premios)
    {
        $this->premios = $premios;
    }

    public function getFrontLabel()
    {
        return $this->front_label;
    }

    public function setFrontLabel($front_label)
    {
        $this->front_label = $front_label;
    }

    public function getBackLabel()
    {
        return $this->back_label;
    }

    public function setBackLabel($back_label)
    {
        $this->back_label = $back_label;
    }

    public function getValorAprox()
    {
        return $this->valor_aprox;
    }

    public function setValorAprox($valor_aprox)
    {
        $this->valor_aprox = $valor_aprox;
    }

    public function getStampTime()
    {
        return $this->stamptime;
    }

    public function setStampTime($stamptime)
    {
        $this->stamptime = $stamptime;
    }

    public function getVinificacao()
    {
        return $this->vinificacao;
    }

    public function setVinificacao($vinificacao)
    {
        $this->vinificacao = $vinificacao;
    }

    public function getServicoConsumo()
    {
        return $this->servico_consumo;
    }

    public function setServicoConsumo($servico_consumo)
    {
        $this->servico_consumo = $servico_consumo;
    }

    public function getGastronomia()
    {
        return $this->gastronomia;
    }

    public function setGastronomia($gastronomia)
    {
        $this->gastronomia = $gastronomia;
    }

    public function getPublic()
    {
        return $this->public;
    }

    public function setPublic($public)
    {
        $this->public = $public;
    }
}
