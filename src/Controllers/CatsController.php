<?php
namespace App\Controllers;

use App\Models\CatModel;

class CatsController
{
    private $model;

    /**
     * Inject CatModel dependency
     */
    public function __construct(CatModel $model)
    {
        $this->model = $model;
    }

    /**
     * GET /cats
     */
    public function index()
    {
        $cats = $this->model->getAll();
        $this->jsonResponse($cats);
    }

    /**
     * GET /cats/{id}
     */
    public function show($id)
    {
        $cat = $this->model->getById($id);

        // Error handling if there are no records in the database
        if (!$cat) {
            $this->jsonResponse(["error" => "Cat not found"], 404);
            return;
        }

        $this->jsonResponse($cat);
    }

    /**
     * POST /cats
     */
    public function store()
    {
        $data = json_decode(file_get_contents("php://input"), true);

        // Validation to see if there are missing fields in the request body
        if (!isset($data['name'], $data['owner'], $data['birth'], $data['gender'])) {
            $this->jsonResponse(["error" => "Missing required fields"], 400);
            return;
        }

        // This will create a new cat record in the database
        $this->model->create(
            $data['name'],
            $data['owner'],
            $data['birth'],
            $data['gender']
        );

        $this->jsonResponse(["message" => "Cat created",
                            "data" => $data], 201);
    }

    /**
     * PUT /cats/{id}
     */
    public function update($id)
    {
        $data = json_decode(file_get_contents("php://input"), true);

        // Validation to see if there are missing fields in the request body
        if (!isset($data['name'], $data['owner'], $data['birth'], $data['gender'])) {
            $this->jsonResponse(["error" => "Missing required fields"], 400);
            return;
        }

        // This will update an existing cat record in the database
        $updated = $this->model->update(
            $id,
            $data['name'],
            $data['owner'],
            $data['birth'],
            $data['gender']
        );

        // Error handling in case the update operation fails
        if (!$updated) {
            $this->jsonResponse(["error" => "Update failed"], 400);
            return;
        }


        $this->jsonResponse(["message" => "Cat updated"
                            ,"data" => $data]);
    }

    /**
     * DELETE /cats/{id}
     */
    public function destroy($id)
    {
        $deleted = $this->model->delete($id);

        if (!$deleted) {
            $this->jsonResponse(["error" => "Delete failed"], 400);
            return;
        }

        $this->jsonResponse(["message" => "Cat deleted"]);
    }

    /**
     * Helper function for JSON responses
     */
    private function jsonResponse($data, $status = 200)
    {
        http_response_code($status);
        header("Content-Type: application/json");
        echo json_encode($data);
    }
}