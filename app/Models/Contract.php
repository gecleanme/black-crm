<?php

namespace App\Models;

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

}
