<?php

    class Users{
        public function __contsruct()
        {

        }

        public function createUser($con,$data)
        {
            $sql = 'INSERT INTO users (username) VALUES (?,?)';
            $result = null; 
            $stmt = $con->prepare($sql);
            $stmt->bind_param('ss',$data['username'],$data['id_rol']);
            $result = $stmt->execute();
            return $result;
        }
    }

?>