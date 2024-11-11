<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Witness extends Model
{
    use HasFactory;
    protected $table = 'witness';
    protected $primaryKey = 'id';
    protected $fillable = [
        'complain_id',
        'name',
        'phone'
    ];

    protected function cast(): array
    {
        return [
            'name' => 'array',
            'phone' => 'array'
        ];
    }

    public $incrementing = false;
    public $timestamps = false;
    public static function booted()
    {
        static::creating(function ($model) {
            $model->id = _id($model);
        });
    }

    public function complain()
    {
        return $this->belongsTo(Complain::class);
    }
}
