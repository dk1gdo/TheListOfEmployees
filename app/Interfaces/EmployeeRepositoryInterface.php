<?php

namespace App\Interfaces;

interface EmployeeRepositoryInterface
{
    public function getAllEmployees();
    public function getEmployee($id);
    public function createEmployee(array $data);
    public function updateEmployee($id, array $date);
    public function getCurrentEmployees();
    public function getFiredEmployees();

}
