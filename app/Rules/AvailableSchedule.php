<?php

namespace App\Rules;

use App\Models\Schedule;
use Illuminate\Contracts\Validation\InvokableRule;

class AvailableSchedule implements InvokableRule
{
    protected string $vetId;

    public function __construct(int $vetId)
    {
        $this->vetId = $vetId;
    }

    /**
     * Run the validation rule.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     * @return void
     */
    public function __invoke($attribute, $value, $fail)
    {
        $schedule = Schedule::where([
            'vet_id' => $this->vetId,
            'date' => $value
        ]);

        if($schedule){
            $fail('There is already a schedule at this time for this professional');
        }
    }
}
