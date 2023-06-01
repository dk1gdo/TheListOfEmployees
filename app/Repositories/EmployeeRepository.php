<?php

namespace App\Repositories;

use App\Interfaces\EmployeeRepositoryInterface;
use App\Interfaces\JobRepositoryInterface;
use App\Models\Employee;

class EmployeeRepository implements EmployeeRepositoryInterface
{
    public function getAllEmployees()
    {
        return Employee::all();
    }

    public function getEmployee($id)
    {
        return Employee::find($id);
    }

    public function createEmployee(array $data)
    {
        $jobRepo = new JobRepository();
        return Employee::create([
            "name"              => $data['name'],
            "job_id"            => $jobRepo->getOrCreateJobByTitle($data['job']),
            "phone"             => $data['phone'],
            "birthday"          => $data['birthday'],
            "employment_date"   => $data['employment_date'],
        ]);
    }

    public function updateEmployee($id, array $data)
    {
        $jobRepo = new JobRepository();
        return Employee::whereId($id)->update([
            "name"              => $data['name'],
            "job_id"            => $jobRepo->getOrCreateJobByTitle($data['job']),
            "phone"             => $data['phone'],
            "birthday"          => $data['name'],
            "employment_date"   => $data['employment_date'],
            "dismissal_date"   => $data['dismissal_date'],
        ]);
    }

    public function getCurrentEmployees()
    {
        return Employee::whereNull('dismissal_date')->orderBy('name')->get();
    }

    public function getFiredEmployees()
    {
        return Employee::whereNotNull('dismissal_date')->orderBy('name')->get();
    }
}
