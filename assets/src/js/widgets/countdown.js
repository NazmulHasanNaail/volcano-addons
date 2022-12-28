/**!
 * CountDown Public Scripts
 *
 * @author volcano Addons
 * @version 1.0.0
 */
(function ($, window, elementor) {
    "use strict";

    $(window).on("elementor/frontend/init", function () {
        elementorFrontend.hooks.addAction("frontend/element_ready/volcano-countdown.default", function ($scope) {
            const el = $('.volcano-addons-countdown', $scope).psgTimer( {
                animation:false,
            } );
        });
    });

})(jQuery, window, window.elementorFrontend);
