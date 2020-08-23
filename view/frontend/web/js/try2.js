
define(['jquery'], function($) {

    return  function(getDate)
    {
        var dt=getDate.myDate;

        $('#x-init').countdown(dt, function (event) {

            $(this).html(event.strftime('%d days %H:%M:%S'));
        });
    }
});
