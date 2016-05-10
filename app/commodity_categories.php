<?php

namespace EID;

use Illuminate\Database\Eloquent\Model;

class commodity_categories extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'commodity_categories';

    /**
     *  Attributes that should be mass-assignable.
     *
     *  NOTE:
     *  id is included just to simplify unit testing in
     *  CommodityCreationTest::create_commodity_category
     *
     * @var array
     */
    protected $fillable = ['id', 'category_name'];

}
