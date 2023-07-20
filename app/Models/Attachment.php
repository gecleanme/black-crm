<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Attachment extends Model
{
    use HasFactory;

    public function attachable() //this has to match the table column name
    {
        $this->morphTo(); //instead of belongsTo
    }

    protected static function boot()
    {
        parent::boot();
        static::deleting(function ($childModel) {
            // Loop through each child record & Delete it if exists
            foreach ($childModel->where('attachable_id', $childModel->attachable_id)->get() as $childRecord) {
                if (Storage::disk('public')->exists($childRecord->attachments)) {
                    Storage::delete($childRecord->attachments);
                }
            }
        });
    }
}
