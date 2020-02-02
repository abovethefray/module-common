/**
 * Copyright © Above The Fray Design, Inc. All rights reserved.
 * See ATF_COPYING.txt for license details.
 */
var config = {
    paths: {
        'atf/common': 'ATF_Common/js/common_scripts',
        'atf/tooltip': 'ATF_Common/js/atf-tooltip',
        'atf/persistent-customer-cache-fix': 'ATF_Common/js/persistent-customer-cache-fix',
        'jqueryMatchHeight': 'ATF_Common/js/plugin/jquery.matchHeight',
        'owlCarousel': 'ATF_Common/js/plugin/owl-carousel/owl.carousel.min',
        'colourBrightness': 'ATF_Common/js/plugin/jquery.colourbrightness',
        'lity': 'ATF_Common/js/plugin/lity.min',
        'simpleBar': 'ATF_Common/js/plugin/simplebar.min',
        'bodyScrollLock': 'ATF_Common/js/plugin/bodyScrollLock',
        'slick': 'ATF_Common/js/plugin/slick-carousel/slick.min.js',
    },
    shim: {
        'atf/common': {
            deps: ['jquery']
        },
        'atf/tooltip': {
            deps: ['jquery']
        },
        'atf/persistent-customer-cache-fix': {
            deps: ['jquery']
        },
        'jqueryMatchHeight': {
            deps: ['jquery']
        },
        'owlCarousel': {
            deps: ['jquery']
        },
        'colourBrightness': {
            deps: ['jquery']
        },
        'lity': {
            deps: ['jquery']
        },
        'simpleBar': {
            deps: ['jquery']
        },
        'slick': {
            deps: ['jquery']
        },
    }
};
