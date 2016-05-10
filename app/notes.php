stock_status:
    - initial display shows only DBS kits
    - clicking on facility shows all commodties
    - warning: generation of forecasted_stockout_date will fail if average_monthly_consumption is 0
    - if stock_status.is_most_recent_change == 'YES' for more than one {facility_id, commodity_id} combination, then 
        it means there is a data-integrity error

Third action item in notebook should be: calculate AMC

