<?php
    class UserModel{
        private $db;

        public function __construct(){
            $this->db = new PDO('mysql:host=localhost;dbname=tpe_2024;charset=utf8', 'root', '');;
        }

        public function getUserByEmail($user){
            $query = $this->db->prepare('SELECT * FROM usuarios WHERE usuario = ?');
            $query->execute([$user]);
            $res = $query->fetch(PDO::FETCH_OBJ);
            return $res;
        }
    }
?>