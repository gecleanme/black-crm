<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class ContractCycle extends Model
{
    use HasFactory;

    protected $appends = ['attachment_urls', 'attachment_filename'];

    public function attachments()
    {
        return $this->morphMany(Attachment::class, 'attachable'); //has many attachables
    }

    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class, 'contract_id'); //has many attachables
    }

    public function getAttachmentUrlsAttribute()
    {
        $urls = [];

        foreach ($this->attachments as $attachment) {
            if (Storage::disk('public')->exists($attachment->attachments)) {
                $urls[] = Storage::disk('public')->url($attachment->attachments);
            }
        }

        return $urls;
    }

    public function getAttachmentFilenameAttribute()
    {
        $urls = [];

        foreach ($this->attachments as $attachment) {
            if (Storage::disk('public')->exists($attachment->attachments)) {
                $urls[] = $attachment->attachments;
            }
        }

        return $urls;
    }
}
