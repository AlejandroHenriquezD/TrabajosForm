<?php

class BocetoController
{
    public function __construct(private BocetoGateway $gateway)
    {}

    public function processRequest(string $method, ?string $id): void
    {
        if ($id) {
            $this->processResourceRequest($method, $id);
        } else {
            $this->processCollectionRequest($method);
        }
    }

    private function processResourceRequest(string $method, string $id): void
    {
        $boceto = $this->gateway->get($id);

        if ( ! $boceto) {
            http_response_code(404);
            echo json_encode(["message" => "Boceto no encontrado"]);
            return;
        }

        switch ($method) {
            case "GET":
                echo json_encode($boceto);
                break;
            
            case "PATCH":
                $data = (array) $_POST;
                parse_str(file_get_contents("php://input"),$put_vars);


                $errors = $this->getValidationsErrors($data, false);
                
                if ( ! empty($errors)) {
                    http_response_code(422);
                    echo json_encode(["errors" => $errors]);
                    break;
                }
                
                $rows = $this->gateway->update($boceto, $put_vars);
                
                echo json_encode([
                    "message" => "Boceto $id actualizado",
                    "rows" => $rows
                ]);
                break;

            case "DELETE":
                $rows = $this->gateway->delete($id);

                echo json_encode([
                    "message" => "Boceto $id borrado",
                    "rows" => $rows
                ]);
                break;
            default:
                http_response_code(405);
                header("Alow: GET, PATCH, DELETE");

        }
    }

    private function processCollectionRequest(string $method): void
    {
        switch ($method){
            case "GET":
                echo json_encode($this->gateway->getAll());
                break;

            case "POST":
                $data = (array) $_POST;

                $errors = $this->getValidationsErrors($data, true);

                if ( ! empty($errors)) {
                    http_response_code(422);
                    echo json_encode(["errors" => $errors]);
                    break;
                }
                

                $id = $this->gateway->create($data);

                http_response_code(201);
                echo json_encode([
                    "message" => "Boceto Creado",
                    "id" => $id
                ]);
                break;

            default:
                http_response_code(405);
                header("Allow: GET, POST");
        }
    }

    private function getValidationsErrors(array $data, bool $is_new): array
    {
        $errors = [];

        if ($is_new && (empty($data["nombre"]))) {
            $errors[] = "Nombre es necesario";
        }
        if ($is_new && (empty($data["pdf"]))) {
            $errors[] = "Pdf es necesario";
        }
        if ($is_new && (empty($data["id_cliente"]))) {
            $errors[] = "Cliente es necesario";
        }

        return $errors;
    }
}