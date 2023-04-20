<?php

class ClienteGateway
{
    private PDO $conn;

    public function __construct(Database $database)
    {
        $this->conn = $database->getConnection();
    }

    public function getAll(): array
    {

        $sql = "SELECT *
                FROM clientes";

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
        $sql = "INSERT INTO clientes (nombre,telefono,correo,direccion,cif_nif,numero_cliente,razon_social)
                VALUES (:nombre,:telefono,:correo,:direccion,:cif_nif,:numero_cliente,:razon_social)";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(":nombre", $data["nombre"], PDO::PARAM_STR);
        $stmt->bindValue(":telefono", $data["telefono"], PDO::PARAM_STR);
        $stmt->bindValue(":correo", $data["correo"], PDO::PARAM_STR);
        $stmt->bindValue(":direccion", $data["direccion"], PDO::PARAM_STR);
        $stmt->bindValue(":cif_nif", $data["cif_nif"], PDO::PARAM_STR);
        $stmt->bindValue(":numero_cliente", $data["numero_cliente"], PDO::PARAM_STR);
        $stmt->bindValue(":razon_social", $data["razon_social"], PDO::PARAM_STR);

        $stmt->execute();

        return $this->conn->lastInsertId();
    }

    public function get(string $id): array | false
    {
        $sql = "SELECT *
                FROM clientes
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
        $sql = "UPDATE clientes
        SET nombre = :nombre, telefono = :telefono, correo = :correo, direccion = :direccion, cif_nif = :cif_nif, numero_cliente = :numero_cliente, razon_social = :razon_social
        WHERE id = :id";

        

        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(":nombre", $new["nombre"] ?? $current["nombre"], PDO::PARAM_STR);
        $stmt->bindValue(":telefono", $new["telefono"] ?? $current["telefono"], PDO::PARAM_STR);
        $stmt->bindValue(":correo", $new["correo"] ?? $current["correo"], PDO::PARAM_STR);
        $stmt->bindValue(":direccion", $new["direccion"] ?? $current["direccion"], PDO::PARAM_STR);
        $stmt->bindValue(":cif_nif", $new["cif_nif"] ?? $current["cif_nif"], PDO::PARAM_STR);
        $stmt->bindValue(":numero_cliente", $new["numero_cliente"] ?? $current["numero_cliente"], PDO::PARAM_STR);
        $stmt->bindValue(":razon_social", $new["razon_social"] ?? $current["razon_social"], PDO::PARAM_STR);
        $stmt->bindValue(":id", $current["id"], PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->rowCount();
    }

    public function delete(string $id): int
    {
        $sql = "DELETE FROM clientes
                WHERE id = :id";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(":id", $id, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->rowCount();
    }
}
