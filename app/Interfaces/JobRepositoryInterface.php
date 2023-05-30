<?php

namespace App\Interfaces;

interface JobRepositoryInterface
{
    public function getAllJobs();
    public function getJob($id);
    public function createJob($title);
    public function updateJob($id, $title);
    public function deleteJob($id);
    public function getOrCreateJobByTitle($title);
}
