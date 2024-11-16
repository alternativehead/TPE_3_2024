<?php

class ModelAlbums {
    private $db;

    public function __construct() {
       $this->db = new PDO('mysql:host=localhost;dbname=tpe_2024;charset=utf8', 'root', '');
    }

    public function getAlbums($filter = '', $value = '', $orderBy = '', $order = '', $page = '', $limit = '')
    {
        $sql = 'SELECT * FROM albumes';

    // Agregar filtro si está definido
    if ($filter && $value) {
        switch ($filter) {
            case 'band':
                $sql .= ' WHERE id_banda = :value';
                break;
            case 'genre':
                $sql .= ' WHERE genero = :value';
                break;
            case 'year':
                $sql .= ' WHERE anio_de_lanzamiento = :value';
                break;
            case 'record_label':
                $sql .= ' WHERE discografica = :value';
                break;
        }
    }

    // Agregar orden si está definido
    if ($orderBy && $order) {
        switch ($orderBy) {
            case 'album':
                $sql .= ' ORDER BY albumes.album';
                break;
            case 'year':
                $sql .= ' ORDER BY albumes.anio_de_lanzamiento';
                break;
            case 'genre':
                $sql .= ' ORDER BY albumes.genero';
                break;
            case 'record_label':
                $sql .= ' ORDER BY albumes.discografica';
                break;
        }
        if ($order == 'DESC') {
            $sql .= ' DESC';
        }
        else if ($order == 'ASC') {
            $sql .= ' ASC';
        }
    }

    // Agregar paginación si está definida
    if ($page && $limit) {
        $sql .= ' LIMIT :limit OFFSET :offset';
    }

    // Preparar la consulta
    $query = $this->db->prepare($sql);

    // Vincular parámetros dinámicos
    if ($filter && $value) {
        $query->bindValue(':value', $value);
    }
    if ($page && $limit) {
        $offset = ($page - 1) * $limit;
        $query->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $query->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
    }

    // Ejecutar la consulta una sola vez
    $query->execute();

    // Obtener los resultados
    $albums = $query->fetchAll(PDO::FETCH_OBJ);

    return $albums;
    }
 
    public function getAlbum($id) {    
        $query = $this->db->prepare('SELECT * FROM albumes WHERE id_album = ?');
        $query->execute([$id]);   
    
        $album = $query->fetch(PDO::FETCH_OBJ);
    
        return $album;
    }

    public function createAlbum($array){
        $query = $this->db->prepare('INSERT INTO albumes (album, anio_de_lanzamiento, genero, discografica, descripcion, imagen ,id_banda) VALUES (?,?,?,?,?,?,?)');
        $query->execute([$array[0],$array[1],$array[2],$array[3],$array[4],$array[5],$array[6]]);
        $id = $this->db->lastInsertId();
        return $id;
    }

    public function editAlbum($array, $id_album){
        $query = $this->db->prepare('UPDATE albumes SET album = ?, anio_de_lanzamiento = ?, genero = ?, discografica = ?, descripcion = ?, imagen = ?, id_banda = ? WHERE id_album = ?');
        $query->execute([$array[0],$array[1],$array[2],$array[3],$array[4],$array[5],$array[6], $id_album]);
    }
 
    public function deleteAlbum($id_album){
        $query = $this->db->prepare('DELETE FROM albumes WHERE id_album = ?');
        $query->execute([$id_album]);
    }
    /*public function insertTask($title, $description, $priority, $finished = false) { 
        $query = $this->db->prepare('INSERT INTO tareas(titulo, descripcion, prioridad, finalizada) VALUES (?, ?, ?, ?)');
        $query->execute([$title, $description, $priority, $finished]);
    
        $id = $this->db->lastInsertId();
    
        return $id;
    }
 
    public function eraseTask($id) {
        $query = $this->db->prepare('DELETE FROM tareas WHERE id = ?');
        $query->execute([$id]);
    }

    public function updateTask($id) {        
        $query = $this->db->prepare('UPDATE tareas SET finalizada = 1 WHERE id = ?');
        $query->execute([$id]);
    }*/
}
?>