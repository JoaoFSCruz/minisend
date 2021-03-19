<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Email extends Model
{
    use HasFactory;

    protected $fillable = [
        'sender',
        'recipient',
        'subject',
        'text',
        'html',
    ];

    /**
     * Get the attachments this email owns.
     */
    public function attachments()
    {
        return $this->hasMany(Attachment::class);
    }
}
