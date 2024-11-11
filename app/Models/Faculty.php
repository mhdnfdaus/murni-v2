<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faculty extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';
    protected $fillable = [
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

    public function course()
    {
        return $this->hasMany(Faculty::class, 'id', 'faculties_id');
    }
}
