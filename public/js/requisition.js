
$(function () {

    $(".js-example-basic-single").select2();
    // $('.row-wrapper').cloneya();
    $('.clone-wrapper').cloneya({
            valueClone      : true,
            dataClone       : true,
            deepClone       : true    
    });

    $('.dbs_date').each(function (){

        var this_dateField = $(this);

            this_dateField.pikaday({
                firstDay: 1,
                minDate: new Date('2010-01-01'),
                maxDate: new Date(),
                defaultDate: new Date(this.value),
                setDefaultDate: true,
                format: 'YYYY-MM-DD'
            });
    });
});
