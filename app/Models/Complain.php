<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Complain extends Model
{
    use HasFactory;
    protected $table = 'complains';
    protected $primaryKey = 'id';
    protected $fillable = [
        'reporter_id',
        'culprit_id',
        'title',
        'description',
        'status',
        'incident_date'
    ];
    public $incrementing = false;

    public static function booted()
    {
        static::creating(function ($model) {
            $model->id = _id($model);
        });
    }

    public function reporter()
    {
        return $this->belongsTo(User::class, 'reporter_id', 'id');
    }

    public function culprit()
    {
        return $this->belongsTo(User::class, 'culprit_id', 'id');
    }

    public function complainFiles()
    {
        return $this->hasMany(ComplaintFile::class, 'complain_id');
    }

    public function getFilePathsAttribute()
    {
        return collect($this->complainFiles)->map(function ($file) {
            return asset('storage/complain/' . $file->file_path);
        });
    }

    public function witness()
    {
        return $this->hasMany(Witness::class, 'complain_id');
    }
}
