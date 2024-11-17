# TPE_3_2024

## Tema
Venta de albumes de musica
## Integrantes
Salvador Tucci González.

## Diseño
![image](https://github.com/user-attachments/assets/94baadce-e6c6-4abf-a1bc-9bc5ae854c9e)

## obtener todos los albumes `GET`:

```http
  /api/albums/
```

| Parametros        | Tipo    | Valores                                                                                                                                 |Descripcion                                                                                 | ejemplo                                 |
| :-----------------| :-------| :---------------------------------------------------------------------------------------------------------------------------------------| :------------------------------------------------------------------------------------------|:---------------------------------------|
| `order_by, order` | `string`|`order_by = album, year, genre, record_label. order = DESC, ASC`                                                                         | obtiene el resultado ordenado por alguno de sus campos y de manera ascendente o descendente| `/api/albums/order_by=album&order=DESC` |
|`filter, value`    | `string`| `filter = band, genre, year, record_label.` algunos valores que se pueden encontrar entre tantos:` value = 1, grunge, 1993, DCG Records`| filtra los resultados por alguno de sus campos y le asigna un valor                        | `/api/albums/filter=band&value=1`       |
|`page, limit`      |`number` |`page = 1,2,3,etc. limit = 1,2,3,etc.`                                                                                                   |divide los resultados por pagina y establece un limite de muestreo                          | `/api/albums/page=2&limit=3`            |

## obtener un album `GET`:

```http
  /api/albums/id
```

| Parametro | Tipo     | Descripcion                       |
| :-------- | :------- | :-------------------------------- |
| `id`      | `string` | Id del album a fetchar            |

## crear un album `POST`:
> [!IMPORTANT]
> se requiere de un token

```http
  /api/albums/
```
## Ejemplo de body

```http
  {
    "album": "Jar of Flies",
    "anio_de_lanzamiento": "1994",
    "genero": "Grunge",
    "discografica": "Columbia Records",
    "descripcion": "Jar of Flies es el segundo EP acústico de la banda de grunge Alice in Chains. Fue escrito y grabado en una semana, siendo lanzado a principios de 1994 y llegando a lo más alto en las listas de Estados Unidos, convirtiéndose en el primer EP en debutar en el puesto N.º 1 del ranking Billboard 200.",
    "imagen": "https://i.scdn.co/image/ab67616d0000b27325b42be683b8d3c6500db726",
    "id_banda": "3"
  }
```

## editar un album `PUT`:
> [!IMPORTANT]
> se requiere de un token

```http
  /api/albums/id
```

| Parametro | Tipo     | Descripcion                       |
| :-------- | :------- | :-------------------------------- |
| `id`      | `string` | Id del album a editar             |

## eliminar un album `DELETE`:
> [!IMPORTANT]
> se requiere de un token

```http
  /api/albums/id
```

| Parametro | Tipo     | Descripcion                       |
| :-------- | :------- | :-------------------------------- |
| `id`      | `string` | Id del album a eliminar           |

## obtener un token `GET` :

```http
  /api/users/token
```

| Basic authentication | value   | Tipo     | Descripcion                       |
| :--------            | :-------| :------- | :-------------------------------- |
| `username`           | webadmin| `string` | usuario a ingresar                |
|`password`            |123456789| `string` | contrasenia a ingresar            |
