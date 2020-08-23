var config = {
    map: {
        '*': {
            Countdown:           "Dev101_SpecialPrice/js/Countdown",
            try2:                "Dev101_SpecialPrice/js/try2"
        }
    },
       paths: {
            'Countdown': "Dev101_SpecialPrice/js/Countdown" ,
            'jcount': "Dev101_SpecialPrice/js/jquery.countdown",
            'jcountmin': "Dev101_SpecialPrice/js/jquery.countdown.min",
            'try2': "Dev101_SpecialPrice/js/try2",
            'showFromAjax' : "Dev101_SpecialPrice/js/showFromAjax"
        },
        shim: {
            Countdown: {
                deps: ['jquery']
            },
            jcount: {
                deps: ['jquery']
            },
            jcountmin: {
                deps: ['jquery']
            },
            try2: {
                deps: ['jquery']
            },
            showFromAjax: {
                deps: ['jquery']
            }
        }
};



