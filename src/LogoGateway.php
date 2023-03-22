<?php

class LogoGateway
{
    private PDO $conn;

    public function __construct(Database $database)
    {
        $this->conn = $database->getConnection();
    }

    public function getAll(): array
    {

        $sql = "SELECT *
                FROM logos";

        $stmt = $this->conn->query($sql);


        $data = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

            $row["obsoleto"] = (bool) $row["obsoleto"];

            $data[] = $row;
        }

        return $data;
    }

    public function create(array $data): string
    {
        $sql = "INSERT INTO logos (img,img_vectorizada,obsoleto,id_cliente)
                VALUES (:img,:img_vectorizada,:obsoleto,:id_cliente)";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(":img", $data["img"], PDO::PARAM_STR);
        $stmt->bindValue(":img_vectorizada", $data["img_vectorizada"], PDO::PARAM_STR);
        $stmt->bindValue(":obsoleto", $data["obsoleto"], PDO::PARAM_BOOL);
        $stmt->bindValue(":id_cliente", $data["id_cliente"], PDO::PARAM_INT);

        $stmt->execute();

        return $this->conn->lastInsertId();
    }

    public function get(string $id): array | false
    {
        $sql = "SELECT *
                FROM logos
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
        $sql = "UPDATE logos
        SET img = :img, img_vectorizada = :img_vectorizada, obsoleto = :obsoleto, id_cliente = :id_cliente
        WHERE id = :id";

        

        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(":img", $new["img"] ?? $current["img"], PDO::PARAM_STR);
        $stmt->bindValue(":img_vectorizada", $new["img_vectorizada"] ?? $current["img_vectorizada"], PDO::PARAM_STR);
        $stmt->bindValue(":obsoleto", $new["obsoleto"] ?? $current["obsoleto"], PDO::PARAM_BOOL);
        $stmt->bindValue(":id_cliente", $new["id_cliente"] ?? $current["id_cliente"], PDO::PARAM_INT);
        $stmt->bindValue(":id", $current["id"], PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->rowCount();
    }

    public function delete(string $id): int
    {
        $sql = "DELETE FROM logos
                WHERE id = :id";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(":id", $id, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->rowCount();
    }
}
