<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'filepath',
    ];

    /**
     * Get the email this attachment belongs to.
     */
    protected function email()
    {
        return $this->belongsTo(Email::class);
    }
}
