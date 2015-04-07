<?php
namespace classes;

class User implements TemplateClasses
{

    protected $id_user;
    protected $username;
    protected $password;
    protected $nome;
    protected $hash;
    protected $email;
    protected $permissoes;

    public $erro;

    public function __construct($username, $password, $nome = null, $email = null, $permissoes = null)
    {
        $this->username = $username;
        $this->nome = $nome;
        $this->password = $password;
        $this->email = $email;
        $this->permissoes = $permissoes;

        $this->id_user = $this->idUser();
    }

    public function __destruct()
    {
    }

    public function idUser()
    {
        $con = new Connection();
        $con->beginTransaction();

        try {
            $query = <<<SQL
            SELECT id_user
            FROM user
            WHERE username = :username
SQL;

            $con->query($query);
            $con->bind(':username', $this->username);
            $result = $con->single();
            $count = $con->rowCount();

            if ($count > 0) {
                $this->id_user = $result['id_user'];
                $con->endTransaction();
                return true;
            } else {
                $con->endTransaction();
                $this->erro = 'Username não existe na base de dados!';
                return false;
            }

        } catch (PDOException $e) {
            $con->cancelTransaction();

        }
    }

    public function populate()
    {
        $con = new Connection();
        $con->beginTransaction();

        try {
            $query = <<<SQL
            SELECT nome,email,permissoes
            FROM user
            WHERE username = :username
SQL;

            $con->query($query);
            $con->bind(':username', $this->username);
            $result = $con->resultset();
            foreach ($result as $user) {
                //$this->setId_user($user['id_user']);
                $this->setNome($user['nome']);
                $this->setEmail($user['email']);
                $this->setPermissoes($user['permissoes']);
            }

            $con->endTransaction();
            return ($count > 0) ? true : false ;
        } catch (PDOException $e) {
            $con->cancelTransaction();

        }
    }

    public function login()
    {
        if ($this->idUser()) {
            if ($this->fetchHash()) {
                if (password_verify($this->password, $this->hash)) {
                    if (password_needs_rehash($this->hash, PASSWORD_BCRYPT)) {
                        $this->hash = password_hash($this->password, PASSWORD_BCRYPT);
                        $this->updatehash();
                    }
                    $this->populate();
                    return true;
                }
            }
            $this->erro = 'Password incorrecta!';
            return false;
        } else {
            $this->erro = 'Username não existe na base de dados!';
            return false;
        }
    }

    public function updatehash()
    {
        $query = <<<SQL
        UPDATE user
        SET hash = :hash
        WHERE username = :username
SQL;

        $con = new Connection();
        $con->beginTransaction();

        try {
            $con->query($query);
            $con->bind(':username', $this->username);
            $con->bind(':hash', $this->hash);
            $result = $con->execute();
            $count = $con->rowCount();

            $con->endTransaction();
            return ($count > 0) ? true : false ;
        } catch (PDOException $e) {
            $con->cancelTransaction();

        }
    }

    public function register()
    {
        $this->hash = password_hash($this->password, PASSWORD_BCRYPT);
        return $this->insert();
    }

    public function insert()
    {
        if (!$this->idUser()) {
            $query = <<<SQL
            INSERT INTO user (username,nome,hash,email,permissoes)
            VALUES (:username, :nome, :hash, :email, :permissoes)
SQL;

            $con = new Connection();
            $con->beginTransaction();

            try {
                $con->query($query);
                $con->bind(':username', $this->username);
                $con->bind(':nome', $this->nome);
                $con->bind(':hash', $this->hash);
                $con->bind(':email', $this->email);
                $con->bind(':permissoes', $this->permissoes);
                $con->execute();
                $count = $con->rowCount();
                $this->id_user = $con->lastInsertId();

                $con->endTransaction();
                return ($count > 0) ? true : false ;
            } catch (PDOException $e) {
                $con->cancelTransaction();

            }
        } else {
            return 'Username já existe!';
        }
    }

    public function update()
    {
        $query = <<<SQL
        UPDATE user
        SET username = :username, nome = :nome, hash = :hash, email = :email, permissoes = :permissoes
        WHERE id_user = :id_user
SQL;

        $con = new Connection();
        $con->beginTransaction();

        try {
            $con->query($query);
            $con->bind(':username', $this->username);
            $con->bind(':nome', $this->nome);
            $con->bind(':hash', $this->hash);
            $con->bind(':email', $this->email);
            $con->bind(':permissoes', $this->permissoes);
            $con->bind(':id_user', $this->id_user);
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
        DELETE FROM user
        WHERE username = :username
SQL;

        $con = new Connection();
        $con->beginTransaction();

        try {
            $con->query($query);
            $con->bind(':username', $this->username);

            $con->endTransaction();
            return $con->execute();
        } catch (PDOException $e) {
            $con->cancelTransaction();

        }
    }

    public function fetchHash()
    {
        $query = <<<SQL
        SELECT hash
        FROM user
        WHERE username = :username
SQL;

        $con = new Connection();
        $con->beginTransaction();

        try {
            $con->query($query);
            $con->bind(':username', $this->username);
            $result = $con->single();
            $hash = $result['id_hash'];
            if (!empty($hash)) {
                $this->hash = $hash;
                $con->endTransaction();
                return true;
            } else {
                $con->endTransaction();
                return false;
            }
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
        $query .= ' FROM user ';
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
                return $con->populateSelectBox($result, 'user', 'id_user');
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

            $con->query("SHOW COLUMNS FROM user");
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
            $con->query("SELECT * FROM user");
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

    public function getIdUser()
    {
        return $this->id_user;
    }

    public function setIdUser($id_user)
    {
        $this->id_user = $id_user;
    }

    public function getUsername()
    {
        return $this->username;
    }
    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function getPassword()
    {
        return $this->password;
    }
    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function getNome()
    {
        return $this->nome;
    }
    public function setNome($nome)
    {
        $this->nome = $nome;
    }

    public function getHash()
    {
        return $this->hash;
    }
    public function setHash($hash)
    {
        $this->hash = $hash;
    }

    public function getEmail()
    {
        return $this->email;
    }
    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getPermissoes()
    {
        return $this->permissoes;
    }
    public function setPermissoes($permissoes)
    {
        $this->permissoes = $permissoes;
    }

    public function getErro()
    {
        return $this->erro;
    }

    public function setErro($erro)
    {
        $this->erro = $erro;
    }
}
