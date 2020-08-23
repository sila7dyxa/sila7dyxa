

define(['jquery'], function($)
{
     function main (config)
    {
        var currentproduct=config.currentproduct;

        $.ajax({
            url: 'http://magento.loc/SpecialPrice/index/ajax',
            method: 'POST',
            data: { currentproduct
            },
            success: function (data) {
                $('#idDivAjax').html(data)
                    .trigger('contentUpdated');
                setTimeout(main(config),1000*60);
            }
        })
    }

    return main;
});


/*
define(['jquery'], function($) {
    return function (config) {
        var dt=config.myDate;
        var result = document.getElementById('idDivAjax');


        let promise = fetch('http://magento.loc/SpecialPrice/index/ajax?text='+dt);

        promise.then(
            response => {
                return response.data();
            }
        ).then(
            data => {

                result.InnerHTML = data;

            }
        );
    }
});
*/
