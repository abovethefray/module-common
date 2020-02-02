/**
 * Copyright © Above The Fray Design, Inc. All rights reserved.
 * See ATF_COPYING.txt for license details.
 */
define([
    'jquery'
], function ($, _) {
    $.widget('atf.tooltip', {
        options: {
            message: 'This is a tooltip!',
        },
        id: '',

        _create: function () {
            var self = this;
            self.id = self.makeId(10);
            self.element.addClass('atf-popup-trigger');
            self.element.after(
                '<span class="atf-popup container" id="' + self.id + '">' +
                '<span class="content">' + self.options.message + '</span>' +
                '</span>'
            );

            self.element.on('click', function() {
                $('#' + self.id + ' > .content').toggleClass('show');
            });
        },

        makeId: function(length) {
            var result = '';
            var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
            var charactersLength = characters.length;
            for ( var i = 0; i < length; i++ ) {
                result += characters.charAt(Math.floor(Math.random() * charactersLength));
            }
            return result;
        }
    });

    return $.atf.tooltip;
});
