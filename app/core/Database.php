<?php

class Database{
    private $host = DB_HOST, $user = DB_USER, $pass = DB_PASS, $db_name = DB_NAME;

    private $dbh;
    private $stmn;

    public function __construct()
    {
        // data source name
        $dsn ='mysql:host='.$this->host.';dbname='.$this->db_name;

        $option = [
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ];

        try{
            $this->dbh = new PDO($dsn, $this->user, $this->pass, $option); // PDO (PHP Data Object) = Driver PHP untuk mengambil database
        } catch(PDOException $e){
            die($e->getMessage());
        }
    }

    public function query($query){
        $this->stmn = $this->dbh->prepare($query);
    }

    // dengan melakukan bind akan terhindar dari injektion database
    // karna nantinya string akan di bersihkan dulu sebelum di eksekusi

    public function bind($param, $value, $type = NULL){
        if(is_null($type)){
            switch (true){
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;

                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;

                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;

                default :
                    $type = PDO::PARAM_STR;
            }
        }

        $this->stmn->bindValue($param, $value, $type);

    }

    // untuk mengekseskusi
    public function execute(){
        $this->stmn->execute();
    }

    // jika ingin mengambil banyak data
    public function resultSet(){
        $this->execute();
        return $this->stmn->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // jika hanya mengambil satu data
    public function single(){
        $this->execute();
        return $this->stmn->fetch(PDO::FETCH_ASSOC);
    }

    public function rowCount(){
        return $this->stmn->rowCount();
    }

}