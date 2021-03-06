<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'schedule_id', 'specialty_id', 'name',
    ];

    /**
     * Get the specialty that owns the shift.
     */
    public function specialty()
    {
        return $this->belongsTo('App\Specialty');
    }

    /**
     * Get the Shift that owns the shift.
     */
    public function schedule()
    {
        return $this->belongsTo('App\Schedule');
    }

    /**
     * Shift / Spot relationship: One to Many
     * Get the spots that belong to this shift.
     */
    public function spots() {
        return $this->hasMany('App\Spot');
    }
}
