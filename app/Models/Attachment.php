<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Attachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'filepath',
    ];

    protected $appends = [
        'filename',
        'filesize',
        'media_type'
    ];

    /**
     * Returns the filename derived from the filepath.
     *
     * @return mixed|string
     */
    public function getFilenameAttribute()
    {
        $splitPath = explode('/', $this->filepath);
        return end($splitPath);
    }

    /**
     * Returns the file size in with the appropriate size unit.
     *
     * @return mixed|string
     */
    public function getFilesizeAttribute()
    {
        $size = Storage::size($this->filepath);
        $sizeUnit = '';
        if ($size >= 524288) {
            $size = number_format($size / 1048576, 2) . ' MB';
        } elseif ($size >= 512) {
            $size = number_format($size / 1024, 2) . ' KB';
        } else {
            $sizeUnit = ' bytes';
        }

        return $size . $sizeUnit;
    }

    /**
     * Returns the file media type.
     *
     * @return string
     */
    public function getMediaTypeAttribute()
    {
        return mime_content_type(Storage::path($this->filepath));
    }

    /**
     * Get the email this attachment belongs to.
     */
    protected function email()
    {
        return $this->belongsTo(Email::class);
    }
}
