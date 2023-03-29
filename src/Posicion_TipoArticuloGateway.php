<?php

class Posicion_TipoArticuloGateway
{
    private PDO $conn;

    public function __construct(Database $database)
    {
        $this->conn = $database->getConnection();
    }

    public function getAll(): array
    {

        $sql = "SELECT *
                FROM posicionestipoarticulos";

        $stmt = $this->conn->query($sql);


        $data = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

            // $row["obsoleto"] = (bool) $row["obsoleto"]; ESTO ES PARA EL BOOLEAN DE LA TABLA LOGOS

            $data[] = $row;
        }

        return $data;
    }

    public function create(array $data): string
    {
        $sql = "INSERT INTO posicionestipoarticulos (id_tipo_articulo, id_posicion)
                VALUES (:id_tipo_articulo, :id_posicion)";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(":id_tipo_articulo", $data["id_tipo_articulo"], PDO::PARAM_INT);
        $stmt->bindValue(":id_posicion", $data["id_posicion"], PDO::PARAM_INT);

        $stmt->execute();

        return $this->conn->lastInsertId();
    }

    public function get(string $id): array | false
    {
        $sql = "SELECT *
                FROM posicionestipoarticulos
                WHERE id = :id";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(":id", $id, PDO::PARAM_INT);

        $stmt->execute();

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        // if ($data !== false) {
        //     $data["obsoleto"] = (bool) $data["obsoleto"]; //    ESTO PARA EL BOOLEAN DE LOGOS
        // }

        return $data;
    }

    public function update(array $current, array $new): int
    {
        $sql = "UPDATE posicionestipoarticulos
        SET id_tipo_articulo = :id_tipo_articulo, id_posicion = :id_posicion
        WHERE id = :id";

        

        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(":id_tipo_articulo", $new["id_tipo_articulo"] ?? $current["id_tipo_articulo"], PDO::PARAM_INT);
        $stmt->bindValue(":id_posicion", $new["id_posicion"] ?? $current["id_posicion"], PDO::PARAM_INT);
        $stmt->bindValue(":id", $current["id"], PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->rowCount();
    }

    public function delete(string $id): int
    {
        $sql = "DELETE FROM posicionestipoarticulos
                WHERE id = :id";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(":id", $id, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->rowCount();
    }
}
