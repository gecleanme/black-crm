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
        return $this->hasMany(ContractCycle::class,'contract_id');
    }

    public function customer(){
        return $this->belongsTo(Customer::class, 'client_id');
    }

    public function getLastCycleAttribute(){
        return $this->cycles()->latest()->first();
    }


    public function scopeFilter(Builder $query, array $filters): Builder
    {
        return $query->when($filters['title'] ?? false, function ($query, $value) {
            $query->where('title', 'LIKE', '%'.$value.'%');
        })->when($filters['cycle_value'] ?? false, function ($query, $value) {
            $query->whereExists(function ($subQuery) use ($value) {
                $subQuery->selectRaw('value')
                    ->from('contract_cycles')
                    ->whereColumn('contract_cycles.contract_id', 'contracts.id')
                    ->where('contract_cycles.value', '>', $value)
                    ->whereRaw('contract_cycles.id = (SELECT max(id) FROM contract_cycles WHERE contract_id = contracts.id)');
            });
        })->when($filters['premium'] ?? false, function ($query, $value) {
            $query->whereExists(function ($subQuery) use ($value) {
                $subQuery->selectRaw('premium')
                    ->from('contract_cycles')
                    ->whereColumn('contract_cycles.contract_id', 'contracts.id')
                    ->where('contract_cycles.premium', '>', $value)
                    ->whereRaw('contract_cycles.id = (SELECT max(id) FROM contract_cycles WHERE contract_id = contracts.id)');
            });
        })->when($filters['ends_within'] ?? false, function ($query, $value) {
            $endDate = now()->addDays((int) $value)->format('Y-m-d');
            $query->whereExists(function ($subQuery) use ($endDate) {
                $subQuery->select('end_date')
                    ->from('contract_cycles')
                    ->whereColumn('contract_cycles.contract_id', 'contracts.id')
                    ->whereDate('end_date', '>=', $endDate)
                    ->whereRaw('contract_cycles.id = (SELECT max(id) FROM contract_cycles WHERE contract_id = contracts.id)');
            });
        })->when($filters['type'] ?? false, fn($query, $value) => $query->where('type', $value))

            ->when($filters['is_active'] ?? false, function ($query, $value){
                $query->whereExists(function ($subQuery){
                    $subQuery->select('end_date')
                        ->from('contract_cycles')
                        ->whereColumn('contract_cycles.contract_id', 'contracts.id')
                        ->whereDate('end_date', '>=', now())
                        ->whereRaw('contract_cycles.id = (SELECT max(id) FROM contract_cycles WHERE contract_id = contracts.id)');
                });
            })->when($filters['client'] ?? false , fn($query,$value) => $query->where('client_id', $value));
    }


}
