
define(['jquery'], function($) {
    "use strict";
  return  function(getDate)
    {
         var dt=getDate.myDate;

        $('#MageStyle-Timer').countdown(dt, function (event) {

            $(this).html(event.strftime('%d days %H:%M:%S'));
        });
    }
});
