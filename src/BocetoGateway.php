<?php

class BocetoGateway
{
    private PDO $conn;

    public function __construct(Database $database)
    {
        $this->conn = $database->getConnection();
    }

    public function getAll(): array
    {

        $sql = "SELECT *
                FROM bocetos";

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
        $sql = "INSERT INTO bocetos (nombre,pdf,id_cliente)
                VALUES (:nombre,:pdf,:id_cliente)";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(":nombre", $data["nombre"], PDO::PARAM_STR);
        $stmt->bindValue(":pdf", $data["pdf"], PDO::PARAM_STR);
        $stmt->bindValue(":id_cliente", $data["id_cliente"], PDO::PARAM_INT);

        $stmt->execute();

        return $this->conn->lastInsertId();
    }

    public function get(string $id): array | false
    {
        $sql = "SELECT *
                FROM bocetos
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
        $sql = "UPDATE bocetos
        SET nombre = :nombre, pdf = :pdf, id_cliente = :id_cliente
        WHERE id = :id";

        

        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(":nombre", $new["nombre"] ?? $current["nombre"], PDO::PARAM_STR);
        $stmt->bindValue(":pdf", $new["pdf"] ?? $current["pdf"], PDO::PARAM_STR);
        $stmt->bindValue(":id_cliente", $new["id_cliente"] ?? $current["id_cliente"], PDO::PARAM_INT);
        $stmt->bindValue(":id", $current["id"], PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->rowCount();
    }

    public function delete(string $id): int
    {
        $sql = "DELETE FROM bocetos
                WHERE id = :id";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(":id", $id, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->rowCount();
    }
}
