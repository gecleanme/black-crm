<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    use HasFactory;

    public const MAKES = [
    "Toyota",
    "Ford",
    "Honda",
    "Chevrolet",
    "Volkswagen",
    "Nissan",
    "Hyundai",
    "BMW",
    "Mercedes-Benz",
    "Audi",
    "Subaru",
    "Mazda",
    "Kia",
    "Lexus",
    "Jeep",
    "GMC",
    "Ram",
    "Tesla",
    "Volvo",
    "Chrysler",
    "Cadillac",
    "Buick",
    "Acura",
    "Infiniti",
    "Land Rover",
    "Mitsubishi",
    "Jaguar",
    "Porsche",
    "Mini",
    "Fiat"
];
    public const MODELS = [
        'BMW' => ['1 Series', '2 Series', '3 Series'],
        'Toyota' => ['Corolla', 'Yaris']
    ];


    protected $appends=['last_cycle'];

    public function cycles()
    {
        return $this->hasMany(ContractCycle::class,'contract_id')->orderBy('created_at','desc');
    }

    public function customer(){
        return $this->belongsTo(Customer::class, 'client_id');
    }

    public function getLastCycleAttribute(){
        return $this->cycles()->latest()->first();
    }





    public function scopeFilter(Builder $query, array $filters): Builder
    {
        return $query
            ->when(isset($filters['title']), function (Builder $query) use ($filters) {
                $query->where('title', 'LIKE', '%' . $filters['title'] . '%');
            })
            ->when(isset($filters['cycle_value']), function (Builder $query) use ($filters) {
                $query->whereHas('cycles', function (Builder $subQuery) use ($filters) {
                    $subQuery->where('value', '>', $filters['cycle_value']);
                })->take(1);
            })
            ->when(isset($filters['premium']), function (Builder $query) use ($filters) {
                $query->whereHas('cycles', function (Builder $subQuery) use ($filters) {
                    $subQuery->where('premium', '>', $filters['premium']);
                })->take(1);
            })
            ->when(isset($filters['ends_within']), function (Builder $query) use ($filters) {
                $endDate = now()->addDays((int) $filters['ends_within'])->format('Y-m-d');
                $query->whereHas('cycles', function (Builder $subQuery) use ($endDate) {
                    $subQuery->where('end_date', '>=', $endDate);
                })->take(1);
            })
            ->when(isset($filters['type']), function (Builder $query) use ($filters) {
                $query->where('type', $filters['type']);
            })
            ->when(isset($filters['is_active']), function (Builder $query) {
                $query->whereHas('cycles', function (Builder $subQuery) {
                    $subQuery->whereDate('end_date', '>=', now());
                })->take(1);
            })
            ->when(isset($filters['client']), function (Builder $query) use ($filters) {
                $query->where('client_id', $filters['client']);
            });
    }



    protected static function boot()
    {
        parent::boot();
        static::deleting(function ($contract) {
            $contract->attachments()->delete();
        });
    }

}


