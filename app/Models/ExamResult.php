<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ExamResult extends Model
{
    protected $fillable = ['exam_id','child_id','score','total','duration_seconds','started_at','finished_at'];
    protected $casts = ['started_at' => 'datetime', 'finished_at' => 'datetime'];

    public function exam() { return $this->belongsTo(Exam::class); }
    public function child() { return $this->belongsTo(Child::class); }
}
