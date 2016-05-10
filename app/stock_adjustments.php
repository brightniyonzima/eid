<?php

namespace EID;

use Illuminate\Database\Eloquent\Model;

class stock_adjustments extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'stock_adjustments';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['facility_id', 'commodity_id', 'adjustment_type', 'change_in_quantity', 'remarks'];

}
