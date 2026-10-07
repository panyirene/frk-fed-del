<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FollowUp extends Model
{
    protected $fillable = [
        'kode',
        'rencana_id',
        'assessment_id',
        'dosen_id',
        'assessor_id',
        'judul',
        'temuan',
        'rekomendasi',
        'prioritas',
        'status',
        'tanggapan_dosen',
        'bukti_perbaikan',
        'deadline',
        'submitted_at',
        'verified_at',
    ];

    protected $casts = [
        'deadline' => 'date',
        'submitted_at' => 'datetime',
        'verified_at' => 'datetime',
    ];

    public function rencana()
    {
        return $this->belongsTo(
            Rencana::class,
            'rencana_id',
            'id_rencana'
        );
    }

    public function assessment()
    {
        return $this->belongsTo(
            Assessment::class,
            'assessment_id'
        );
    }

    public function dosen()
    {
        return $this->belongsTo(
            User::class,
            'dosen_id'
        );
    }

    public function assessor()
    {
        return $this->belongsTo(
            User::class,
            'assessor_id'
        );
    }
}