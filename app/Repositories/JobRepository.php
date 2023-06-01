<?php

namespace App\Repositories;

use App\Interfaces\JobRepositoryInterface;
use App\Models\Job;

class JobRepository implements JobRepositoryInterface
{

    public function getAllJobs()
    {
        return Job::all();
    }

    public function getJob($id)
    {
        return Job::find($id);
    }

    public function createJob($title)
    {
        return Job::create([
            "title" => $title
        ]);
    }

    public function updateJob($id, $title)
    {
        return Job::whereId($id)->update(["title" => $title]);
    }

    public function deleteJob($id)
    {
        return Job::destroy($id);
    }

    public function getOrCreateJobByTitle($title)
    {
        $job = Job::where('title', '=', $title)->first();
        if (is_null($job)) {
            $job = $this->createJob($title);
        }
        return $job->id;
    }
}
