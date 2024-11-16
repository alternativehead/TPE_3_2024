<?php
    class ModelBands{
        private $db;

    public function __construct() {
       $this->db = new PDO('mysql:host=localhost;dbname=tpe_2024;charset=utf8', 'root', '');
    }

    public function getBand($id_band){
        $query = $this->db->prepare('SELECT id_banda, banda FROM bandas WHERE id_banda = ?');
        $query->execute([$id_band]);
        $res = $query->fetch(PDO::FETCH_OBJ);
        return $res;
    }
    
}
?>