<?php

namespace EID;

use Illuminate\Database\Eloquent\Model;

class stock_status extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'stock_status';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */ 
 

    protected $fillable = ['facility_id',
                           'commodity_id',
                           'stock_changed_by',
                           'stock_change_details_id',
                           'is_most_recent_change',
                           'average_monthly_consumption',
                           'alert_quantity',
                           'initial_quantity',
                           'restock_date',
                           'restock_quantity'
                           ];  

    //mutating the mysql date column to carbon instances
    protected $dates = ['restock_date'];

    public static function getStockStatus(){
      /*
      return stock_status::leftjoin(
                                ['stock_adjustments AS adjustments','adjustments.id', '=',
                                   'stock_status.stock_change_details_id'],
                                ['commodities.id','=','stock_status.commodity_id'],
                                ['facilities.id','=','stock_status.facility_id'])
                        ->select('stock_status.*','facilities.name AS facility',
                                 'commodities.commodity AS commodity','stock_adjustments.change_in_quantity AS adjustments');
                                 */
    }

}
