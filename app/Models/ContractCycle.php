<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContractCycle extends Model
{
    use HasFactory;

    public function attachments()
    {
        return $this->morphMany(Attachment::class, 'attachable'); //has many attachables
    }


    public function contract() :BelongsTo
    {
        return $this->belongsTo(Contract::class, 'contract_id'); //has many attachables
    }



}
