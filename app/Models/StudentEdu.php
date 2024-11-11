<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentEdu extends Model
{
    use HasFactory;
    protected $table = 'student_edu';
    protected $primaryKey = 'id';
    protected $fillable = [
        'user_id',
        'class_id'
    ];

    protected $keyType = 'string';
    public $incrementing = false;
    public static function booted() {
        static::creating(function ($model) {
            $model->id = _id($model);
        });
    }

    public function student() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function studentClass() {
        return $this->belongsTo(Classes::class, 'class_id', 'id');
    }
}
