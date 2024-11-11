<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';

    protected $fillable = [
        'faculties_id',
        'name'
    ];
    protected $keyType = 'string';
    public $inrementing = false;
    
    public static function booted()
    {
        static::creating(function ($model) {
            $model->id = _id($model);
        });
    }

    public function faculty()
    {
        return $this->belongsTo(Course::class, 'faculties_id', 'id');
    }

    public function classes()
    {
        return $this->hasMany(Course::class, 'id', 'course_id');
    }
}
