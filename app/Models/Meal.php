<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meal extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'description'] ;
    public function foods()
    {
        return $this->belongsToMany(Food::class, 'meal_food')  
                    ->withPivot('quantity')
                    ->withTimestamps(); 

    }
    public function getTotalMacrosAttribute() 
    {
        $totals = ['calories' => 0 , 'protein' => 0 , 'carbs' => 0 ,'fat'=>0 , ] ; 
        foreach ($this->foods as $food) {
            $qty = $food->pivot->quantity ?? 1;
            $totals ['calories'] += $food->calories * $qty ; 
            $totals ['protein'] += $food->protein *$qty ; 
            $totals ['carbs'] += $food->carbs *$qty ; 
            $totals ['fat'] += $food->fat *$qty ; 

        }
        return $totals ; 

    }
}
