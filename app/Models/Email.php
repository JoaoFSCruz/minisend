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

    protected $appends = [
        'status',
        'posted_at'
    ];

    /**
     * Get the email status.
     *
     * @return string
     */
    public function getStatusAttribute(): string
    {
        switch ($this->attributes['status']) {
            case 'posted':
                return 'Posted';
            case 'sent':
                return 'Sent';
            case 'failed':
                return 'Failed';
        }

        return 'Unknown';
    }

    /**
     * Get the time that has passed since the email was posted.
     *
     * @return mixed
     */
    public function getPostedAtAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    /**
     * Get the attachments this email owns.
     */
    public function attachments()
    {
        return $this->hasMany(Attachment::class);
    }
}
