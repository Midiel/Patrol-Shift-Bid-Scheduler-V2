<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;

class BiddingQueue extends Model
{
    /**
 * The attributes that are mass assignable.
 *
 * @var array
 */
    protected $fillable = [
        'user_id', 'schedule_id', 'bidding_spot', 'waiting_to_bid', 'bidding', 'bid_submitted', 'start_time_bidding', 'end_time_bidding'
    ];

    /**
     * Get the users for the bidding queue.
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * Get the schedule for the bidding queue.
     */
    public function schedule()
    {
        return $this->belongsTo('App\Schedule');
    }

    /**
     * Returns the bid associated witht this queue.
     */
    public function bid() {
        return $this->hasOne('App\Bid');
    }
}
