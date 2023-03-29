<?php

class PosicionGateway
{
    private PDO $conn;

    public function __construct(Database $database)
    {
        $this->conn = $database->getConnection();
    }

    public function getAll(): array
    {

        $sql = "SELECT *
                FROM posiciones";

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
        $sql = "INSERT INTO posiciones (descripcion)
                VALUES (:descripcion)";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(":descripcion", $data["descripcion"], PDO::PARAM_STR);

        $stmt->execute();

        return $this->conn->lastInsertId();
    }

    public function get(string $id): array | false
    {
        $sql = "SELECT *
                FROM posiciones
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
        $sql = "UPDATE posiciones
        SET descripcion = :descripcion
        WHERE id = :id";

        

        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(":descripcion", $new["descripcion"] ?? $current["descripcion"], PDO::PARAM_STR);
        $stmt->bindValue(":id", $current["id"], PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->rowCount();
    }

    public function delete(string $id): int
    {
        $sql = "DELETE FROM posiciones
                WHERE id = :id";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(":id", $id, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->rowCount();
    }
}
