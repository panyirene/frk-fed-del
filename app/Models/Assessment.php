<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assessment extends Model
{
    use HasFactory;

    protected $fillable = [
        'rencana_id',
        'assessor_id',
        'jenis',
        'status',
        'komentar',
        'reviewed_at',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
    ];

    public function rencana()
    {
        return $this->belongsTo(
            Rencana::class,
            'rencana_id',
            'id_rencana'
        );
    }

    public function assessor()
    {
        return $this->belongsTo(
            User::class,
            'assessor_id'
        );
    }

    public function followUp()
    {
        return $this->hasOne(
            FollowUp::class,
            'assessment_id'
        );
    }
}