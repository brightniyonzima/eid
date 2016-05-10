<?php

namespace EID;

use Illuminate\Database\Eloquent\Model;

class ReceiveStock extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'receivestocks';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['commodity_id', 'qty_rcvd', 'batch_number', 'arrival_date', 'expiry_date'];

}
