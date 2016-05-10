<?php

namespace EID;

use Illuminate\Database\Eloquent\Model;

class commodities extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'commodities';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'commodity_name', 'category_id', 'tests_per_unit'];

}
