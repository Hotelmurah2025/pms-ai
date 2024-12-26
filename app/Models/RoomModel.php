<?php

namespace App\Models;

use CodeIgniter\Model;

class RoomModel extends Model
{
    protected $table = 'rooms';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'room_number',
        'room_type',
        'price',
        'status',
        'description'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'room_number' => 'required|min_length[1]|max_length[10]|is_unique[rooms.room_number,id,{id}]',
        'room_type' => 'required|min_length[2]|max_length[50]',
        'price' => 'required|numeric|greater_than[0]',
        'status' => 'required|in_list[available,occupied,maintenance]',
        'description' => 'permit_empty|max_length[1000]'
    ];

    protected $validationMessages = [
        'room_number' => [
            'required' => 'Room number is required',
            'is_unique' => 'Room number must be unique'
        ],
        'room_type' => [
            'required' => 'Room type is required'
        ],
        'price' => [
            'required' => 'Price is required',
            'numeric' => 'Price must be a number',
            'greater_than' => 'Price must be greater than 0'
        ],
        'status' => [
            'required' => 'Status is required',
            'in_list' => 'Invalid room status'
        ]
    ];

    protected $skipValidation = false;

    public function getRoomTypes()
    {
        return $this->select('DISTINCT room_type')->findAll();
    }

    public function getRoomsByStatus($status)
    {
        return $this->where('status', $status)->findAll();
    }

    public function getAvailableRooms()
    {
        return $this->where('status', 'available')->findAll();
    }
}
