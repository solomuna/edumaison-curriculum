<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    protected $fillable = ['household_id','subject_id','title','question_count','duration_minutes','scheduled_at','status'];
    protected $casts = ['scheduled_at' => 'datetime'];

    public function subject() { return $this->belongsTo(Subject::class); }
    public function results() { return $this->hasMany(ExamResult::class); }
}
