<?php
require_once './model/album_model.php';
require_once './model/band_model.php';
require_once './view/json_view.php';

class AlbumsApiController
{
    private $model_albums;
    private $model_bands;
    private $view;

    public function __construct()
    {
        $this->model_albums = new ModelAlbums();
        $this->model_bands = new ModelBands();
        $this->view = new JSONView();
    }

    // /api/albums
    public function getAllAlbums($req, $res)
    {
        $filter = null;
        $value = false;

        $orderBy = false;
        $order = null;

        $page = false;
        $limit = false;

        if (isset($req->query->order_by) && isset($req->query->order)) {
            
            $orderBy = $req->query->order_by;
            $order = $req->query->order;

            if ($orderBy == 'album' || $orderBy == 'year' || $orderBy == 'genre' || $orderBy == 'record_label') {
                if ($order == 'ASC' || $order == 'DESC') {
                    $albums = $this->model_albums->getAlbums('', '', $orderBy, $order);
                } else {
                    return $this->view->response('no especifica order ASC o DESC', 400); #muestro mensaje de error
                }
            } else {
                return $this->view->response('el orden que escogio no corresponde con ninguna opcion', 400); #muestro mensaje de error
            }
        }

        if (isset($req->query->filter) && isset($req->query->value)) { #evaluo que este seteado el filtro y su valor

            $filter = $req->query->filter;
            $value = $req->query->value;

            if ($filter == 'band' || $filter == 'genre' || $filter == 'year' || $filter == 'record_label') { #evaluo que el filtro este bien escrito para que corresponda con alguna de las opciones
                $albums = $this->model_albums->getAlbums($filter, $value); #obtengo el resultado
                if (!$albums) { #si no devuelve nada
                    return $this->view->response('el valor del parametro ingresado no corresponde con ningun elemento', 404); #muestro mensaje de error
                }
            } else { #si el filtro no corresponde con ninguna opcion
                return $this->view->response('el filtro ingresado no corresponde con ningun elemento', 404); #muestro mensaje de error
            }
        }

        if (isset($req->query->page) && isset($req->query->limit)) {
            if (is_numeric($req->query->page) && is_numeric($req->query->limit)) {

                $page = $req->query->page;
                $limit = $req->query->limit;
                $albums = $this->model_albums->getAlbums('', '', '', '', $page, $limit); #obtengo el resultado
            } else {
                return $this->view->response('los valores ingresados no son numericos', 400); #muestro mensaje de error
            }
        }
        $albums = $this->model_albums->getAlbums($filter, $value, $orderBy, $order, $page, $limit);
        $this->view->response($albums, 200);
    }

    // /api/albums/:id
    public function getAlbum($req, $res)
    {
        $id = $req->params->id; #obtengo el id del album desde la ruta
        $album = $this->model_albums->getAlbum($id); #obtengo el album de la DB

        if ($album) { #evaluo si existe
            $this->view->response($album, 200);
        } else {
            $this->view->response("este album no existe", 404);
        }
    }

    public function createAlbum($req, $res)
    {
        if (!$res->user) {
            return $this->view->response("No autorizado crear", 401);
        }

        if (empty($req->body->album) || empty($req->body->anio_de_lanzamiento) || empty($req->body->genero) || empty($req->body->discografica) || empty($req->body->descripcion) || empty($req->body->imagen) || empty($req->body->id_banda)) {
            return $this->view->response("uno o mas campos vacios", 400);
        } else {

            $id_band = $req->body->id_banda; #obtengo el id ingresado
            $band = $this->model_bands->getBand($id_band); #lo mando a la db para comprobar

            if ($band) { #si la banda existe

                $album = array($req->body->album, $req->body->anio_de_lanzamiento, $req->body->genero, $req->body->discografica, $req->body->descripcion, $req->body->imagen, $req->body->id_banda); #creo un array con los valores ingresados
                $id = $this->model_albums->createAlbum($album); #creo un array y que me devuelva el ultimo id
                $new_album = $this->model_albums->getAlbum($id); #obtengo el album con el ultimo id
                $this->view->response($new_album, 201); #lo muestro por pantalla

            } else { #si la banda no existe
                return $this->view->response("esta banda no existe", 404); #muestro mensaje de error
            }
        }
    }

    public function editAlbum($req, $res)
    {
        if (!$res->user) {
            return $this->view->response("No autorizado editar", 401);
        }

        if (empty($req->body->album) || empty($req->body->anio_de_lanzamiento) || empty($req->body->genero) || empty($req->body->discografica) || empty($req->body->descripcion) || empty($req->body->imagen) || empty($req->body->id_banda)) {
            return $this->view->response("uno o mas campos vacios", 400);
        } else {

            $id_album = $req->params->id;
            $album = $this->model_albums->getAlbum($id_album);

            if ($album) { #verifico que exista el album

                $id_band = $req->body->id_banda;
                $band = $this->model_bands->getBand($id_band);

                if ($band) { #verifico que exista la banda, puede pasar que se quiera cambiar el id_banda poniendo un id inexistente

                    $album = array($req->body->album, $req->body->anio_de_lanzamiento, $req->body->genero, $req->body->discografica, $req->body->descripcion, $req->body->imagen, $req->body->id_banda);
                    $this->model_albums->editAlbum($album, $id_album);
                    $edit_album = $this->model_albums->getAlbum($id_album);
                    $this->view->response($edit_album, 201);

                } else {
                    return $this->view->response("esta banda no existe", 404);
                }
            } else {
                return $this->view->response("este album no existe", 404);
            }
        }
    }

    public function deleteAlbum($req, $res)
    {
        if (!$res->user) {
            return $this->view->response("No autorizado eliminar", 401);
        }

        $id_album = $req->params->id;
        $album = $this->model_albums->getAlbum($id_album);
        if ($album) {
            $this->model_albums->deleteAlbum($id_album);
            $albums = $this->model_albums->getAlbums();
            return $this->view->response($albums, 200);
        } else {
            $this->view->response("este album no existe", 404);

        }
    }
}
