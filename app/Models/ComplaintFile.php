<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComplaintFile extends Model
{
    use HasFactory;
    protected $table = 'complaint_files';
    protected $primaryKey = 'id';
    protected $fillable = [
        'complain_id',
        'file_path',
    ];


    public $incrementing = false;
    public static function booted()
    {
        static::creating(function ($model) {
            $model->id = _id($model);
        });
    }

    public function complains()
    {
        return $this->belongsTo(Complain::class, 'complain_id');
    }
}
