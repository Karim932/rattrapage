<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Carbon\Carbon;

class MaxHoursInMonth implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $daysInMonth = Carbon::now()->daysInMonth; // jour dans le mois
        $maxHours = $daysInMonth * 24;

        if ($value > $maxHours) {
            $fail('Le nombre d\'heures ne doit pas dépasser ' . $maxHours . ' heures pour le mois courant.');
        }
    }
}
