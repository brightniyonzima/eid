<?php

namespace EID;

use Illuminate\Database\Eloquent\Model;

class stock_requisition_header extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'stock_requisition_headers';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['facility_id', 'requisition_date', 'requisition_method', 'requestors_name', 'requestors_phone', 'requestors_batch_number', 'approved_by', 'date_approved', 'dispatched_by', 'date_dispatched', 'receivers_name', 'receivers_phone', 'date_received date'];

}
