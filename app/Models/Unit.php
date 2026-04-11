<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Unit extends Model
{
    use SoftDeletes;
    protected $fillable = ['name','slug','order','integrated_theme_id','summary'];

    public function integratedTheme() { return $this->belongsTo(IntegratedTheme::class); }
    public function lessons() { return $this->hasMany(Lesson::class); }
}
