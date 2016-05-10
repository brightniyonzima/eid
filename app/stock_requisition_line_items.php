<?php

namespace EID;

use Illuminate\Database\Eloquent\Model;

class stock_requisition_line_items extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'stock_requisition_line_items';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['requisition_header_id', 'commodity_id', 'quantity_requested', 'quantity_forecasted', 'quantity_approved', 'approval_result', 'approval_comment', 'requisition_status'];

}
