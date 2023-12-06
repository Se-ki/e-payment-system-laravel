<?php

namespace App\Models;

use App\Traits\UUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model {
    use HasFactory, UUID;

    protected $fillable = ['academic_year_id', 'description_id', 'amount', 'date_post', 'deadline', 'record_by', 'p_semester'];
    protected $with = ['student', 'academic'];
    public function student() {
        return $this->belongsTo(Student::class);
    }

    public function academic() {
        return $this->belongsTo(AcademicYear::class, foreignKey: 'academic_year_id');
    }

    public function description() {
        return $this->belongsTo(Description::class);
    }
}
