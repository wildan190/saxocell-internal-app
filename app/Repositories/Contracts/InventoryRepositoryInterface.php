<?php

namespace App\Repositories\Contracts;

interface InventoryRepositoryInterface
{
    public function getAll(array $filters = []);
    public function create(array $data);
}
