<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;
    protected $fillable = [
        "name",
        "job_id",
        "phone",
        "birthday",
        "employment_date",
    ];
    public $timestamps = false;
    public function job() {
        return $this->belongsTo(Job::class);
    }
}
