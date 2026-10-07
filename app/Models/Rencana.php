<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rencana extends Model
{
    use HasFactory;

    protected $table = 'rencana';
    protected $primaryKey = 'id_rencana';

    protected $fillable = [
        'id_dosen',
        'jenis_rencana',
        'sub_rencana',
        'nama_kegiatan',
        'sks_terhitung',
        'sks_realisasi',
        'asesor1_frk',
        'asesor2_frk',
        'status_frk',
        'komentar_asesor',
        'lampiran_fed',
        'status_fed'
    ];

    public function assessments()
    {
        return $this->hasMany(
            Assessment::class,
            'rencana_id',
            'id_rencana'
        );
    }

    public function followUps()
    {
        return $this->hasMany(
            FollowUp::class,
            'rencana_id',
            'id_rencana'
        );
    } 
}