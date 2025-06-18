<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VerifikasiNda extends Model
{
    use HasFactory;

    protected $table = 'verifikasinda';

    protected $casts = [
        'masaberlaku' => 'datetime'
    ];

    protected $fillable = [
        'id',
        'user_id',
        'file_path',
        'status',
        'signature',
        'created_at',
        'signed_by',
        'catatan',
        'masaberlaku'
    ];

    public $timestamps = false;
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function signedBy()
    {
        return $this->belongsTo(User::class, 'signed_by');
    }

}