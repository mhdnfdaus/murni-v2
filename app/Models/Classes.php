<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classes extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';

    protected $fillable = [
        'course_id',
        'name'
    ];

    protected $keyType = 'string';
    public $inrementing = false;

    public static function booted() {
        static::creating(function ($model) {
            $model->id = _id($model);
        });
    }

    public function course()
    {
        return $this->belongsTo(Classes::class, 'course_id', 'id');
    }
}
