<?php

namespace App\Controllers;

use App\Models\RoomModel;
use CodeIgniter\RESTful\ResourceController;

class Rooms extends ResourceController
{
    protected $roomModel;
    protected $session;

    public function __construct()
    {
        $this->roomModel = new RoomModel();
        $this->session = session();
    }

    public function index()
    {
        $data = [
            'title' => 'Room Management',
            'rooms' => $this->roomModel->findAll()
        ];
        return view('rooms/index', $data);
    }

    public function new()
    {
        $data = [
            'title' => 'Add New Room'
        ];
        return view('rooms/create', $data);
    }

    public function create()
    {
        if ($this->request->isAJAX()) {
            if (!$this->validate($this->roomModel->validationRules)) {
                return $this->response->setJSON([
                    'status' => false,
                    'errors' => $this->validator->getErrors()
                ]);
            }

            $data = $this->request->getPost();
            
            if ($this->roomModel->insert($data)) {
                return $this->response->setJSON([
                    'status' => true,
                    'message' => 'Room added successfully'
                ]);
            }

            return $this->response->setJSON([
                'status' => false,
                'message' => 'Failed to add room'
            ]);
        }

        return $this->response->setStatusCode(404);
    }

    public function edit($id = null)
    {
        $room = $this->roomModel->find($id);
        if (!$room) {
            return redirect()->to('/rooms')->with('error', 'Room not found');
        }

        $data = [
            'title' => 'Edit Room',
            'room' => $room
        ];
        return view('rooms/edit', $data);
    }

    public function update($id = null)
    {
        if ($this->request->isAJAX()) {
            if (!$this->validate($this->roomModel->validationRules)) {
                return $this->response->setJSON([
                    'status' => false,
                    'errors' => $this->validator->getErrors()
                ]);
            }

            $data = $this->request->getPost();
            
            if ($this->roomModel->update($id, $data)) {
                return $this->response->setJSON([
                    'status' => true,
                    'message' => 'Room updated successfully'
                ]);
            }

            return $this->response->setJSON([
                'status' => false,
                'message' => 'Failed to update room'
            ]);
        }

        return $this->response->setStatusCode(404);
    }

    public function delete($id = null)
    {
        if ($this->request->isAJAX()) {
            if ($this->roomModel->delete($id)) {
                return $this->response->setJSON([
                    'status' => true,
                    'message' => 'Room deleted successfully'
                ]);
            }

            return $this->response->setJSON([
                'status' => false,
                'message' => 'Failed to delete room'
            ]);
        }

        return $this->response->setStatusCode(404);
    }
}
