<?php

class Pedidos_ArticulosGateway
{
    private PDO $conn;

    public function __construct(Database $database)
    {
        $this->conn = $database->getConnection();
    }

    public function getAll(): array
    {

        $sql = "SELECT *
                FROM pedidosarticulos";

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
        $sql = "INSERT INTO pedidosarticulos (id_articulo, id_pedido)
                VALUES (:id_articulo, :id_pedido)";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(":id_articulo", $data["id_articulo"], PDO::PARAM_INT);
        $stmt->bindValue(":id_pedido", $data["id_pedido"], PDO::PARAM_INT);

        $stmt->execute();

        return $this->conn->lastInsertId();
    }

    public function get(string $id): array | false
    {
        $sql = "SELECT *
                FROM pedidosarticulos
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
        $sql = "UPDATE pedidosarticulos
        SET id_articulo = :id_articulo, id_pedido = :id_pedido
        WHERE id = :id";

        

        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(":id_articulo", $new["id_articulo"] ?? $current["id_articulo"], PDO::PARAM_INT);
        $stmt->bindValue(":id_pedido", $new["id_pedido"] ?? $current["id_pedido"], PDO::PARAM_INT);
        $stmt->bindValue(":id", $current["id"], PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->rowCount();
    }

    public function delete(string $id): int
    {
        $sql = "DELETE FROM pedidosarticulos
                WHERE id = :id";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(":id", $id, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->rowCount();
    }
}
