<?php

class TrabajoGateway
{
    private PDO $conn;

    public function __construct(Database $database)
    {
        $this->conn = $database->getConnection();
    }

    public function getAll(): array
    {

        $sql = "SELECT *
                FROM trabajos";

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
        $sql = "INSERT INTO trabajos (posicion, id_tipo_articulo, id_tipo_trabajo, id_pedido, id_logo)
                VALUES (:posicion, :id_tipo_articulo, :id_tipo_trabajo, :id_pedido, :id_logo)";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(":posicion", $data["posicion"], PDO::PARAM_STR);
        $stmt->bindValue(":id_tipo_articulo", $data["id_tipo_articulo"], PDO::PARAM_INT);
        $stmt->bindValue(":id_tipo_trabajo", $data["id_tipo_trabajo"], PDO::PARAM_INT);
        $stmt->bindValue(":id_pedido", $data["id_pedido"], PDO::PARAM_INT);
        $stmt->bindValue(":id_logo", $data["id_logo"], PDO::PARAM_INT);

        $stmt->execute();

        return $this->conn->lastInsertId();
    }

    public function get(string $id): array | false
    {
        $sql = "SELECT *
                FROM trabajos
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
        $sql = "UPDATE trabajos
        SET posicion = :posicion, id_tipo_articulo = :id_tipo_articulo, id_tipo_trabajo = :id_tipo_trabajo, id_pedido = :id_pedido, id_logo = :id_logo
        WHERE id = :id";

        

        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(":posicion", $new["posición"] ?? $current["posicion"], PDO::PARAM_STR);
        $stmt->bindValue(":id_tipo_articulo", $new["id_tipo_articulo"] ?? $current["id_tipo_articulo"], PDO::PARAM_INT);
        $stmt->bindValue(":id_tipo_trabajo", $new["id_tipo_trabajo"] ?? $current["id_tipo_trabajo"], PDO::PARAM_INT);
        $stmt->bindValue(":id_pedido", $new["id_pedido"] ?? $current["id_pedido"], PDO::PARAM_INT);
        $stmt->bindValue(":id_logo", $new["id_logo"] ?? $current["id_logo"], PDO::PARAM_INT);
        $stmt->bindValue(":id", $current["id"], PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->rowCount();
    }

    public function delete(string $id): int
    {
        $sql = "DELETE FROM trabajos
                WHERE id = :id";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(":id", $id, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->rowCount();
    }
}
