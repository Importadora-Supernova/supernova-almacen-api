<?php
    class Conexion extends PDO
    {
        private $hostBd = 'localhost';
        private $nameBd = 'u983270445_prueba';
        private $userBd = 'root';
        private $passBd = '';

        public function __construct()
        {
            try
            {
                parent::__construct('mysql:host='.$this->hostBd.';dbname='.$this->nameBd.';charset=utf8', $this->userBd,$this->passBd, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
            }catch(PDOException $e)
            {
                echo 'Error :'.$e->getMessage();
                exit;
            }
        }

    }
?>