<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Storage;

class Customer extends Model
{
    use HasFactory;

    protected $appends=['attachment_urls','attachment_filename'];
    public function attachments(): MorphMany
    {
        return $this->morphMany(Attachment::class, 'attachable'); //has many attachables
    }

    public function contracts(): HasMany {

        return $this->hasMany(Contract::class);
    }

    public function getAttachmentUrlsAttribute()
    {
        $urls = [];

        foreach ($this->attachments as $attachment) {
            if (Storage::disk('public')->exists($attachment->attachments))
            $urls[] = Storage::disk('public')->url($attachment->attachments);
        }

        return $urls;
    }

    public function getAttachmentFilenameAttribute()
    {
        $urls = [];

        foreach ($this->attachments as $attachment) {
            if (Storage::disk('public')->exists($attachment->attachments))
                $urls[] = $attachment->attachments;
        }

        return $urls;
    }


    public function scopeFilter(Builder $query, array $filters): Builder
    {
        return $query->when($filters['name'] ?? false, fn ($query, $value) => $query->where('name', 'LIKE', '%'.$value.'%'))
            ->when($filters['type'] ?? false, fn ($query, $value) => $query->where('type', $value))
            ->when($filters['risk_level'] ?? false, fn ($query, $value) => $query->where('risk_level', (int)$value));


    }


    protected static function boot()
    {
        parent::boot();
        static::deleting(function ($customer) {
            $customer->contracts()->delete();
            $customer->attachments()->delete();
        });
    }

    }

