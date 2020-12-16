(function ($) {
    'use strict';
    var synerbay = synerbay || {};
    synerbay.init = function () {
        synerbay.$body = $(document.body),
            synerbay.$window = $(window);
    }


    // MOCK
    synerbay.showModal = function(name) {
        var $modal = $('#mf-'+name+'-popup');
        $modal.fadeIn();
        $modal.addClass('open');

        // if (days > 0 && document.cookie.match(/^(.*;)?\s*mf_offer_apply_popup\s*=\s*[^;]+(.*)?$/)) {
        //     return;
        // }

        if ($modal.length < 1) {
            return;
        }

        // martfury.$window.on('load', function () {
        //     setTimeout(function () {
        //         $modal.addClass('open');
        //     }, seconds * 1000);
        // });



        $modal.on('click', '.close-modal', function (e) {
            e.preventDefault();
            closeModal();
            $modal.removeClass('open');
            $modal.fadeOut();
        });

        $modal.on('click', '.n-close', function (e) {
            e.preventDefault();
            closeModal();
            $modal.removeClass('open');
            $modal.fadeOut();
        });

        $modal.find('.mc4wp-form').submit(function () {
            closeModal();
        });

        $modal.find('.formkit-form').submit(function () {
            closeModal();
        });

        function closeModal() {
            var date = new Date(),
                value = date.getTime();
            //
            // date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));

            document.cookie = 'mf_'+name+'_popup=' + value + ';expires=' + date.toGMTString() + ';path=/';
        }
    }

    synerbay.appearOffer= function(offerID) {
        let productQty = $("input[name=quantity]").val();

        // console.log(productQty);
        let response = synerbay.restCall({
            'offerID': offerID,
            'productQty': productQty,
        }, 'appear_offer', true);

        if (response.loginRequired === undefined) {
            location.reload();
        }
    }

    synerbay.disAppearOffer= function(offerID) {
        // console.log(productQty);
        if(confirm('Are you sure you want to do this?')){
            let response = synerbay.restCall({
                'offerID': offerID
            }, 'disappear_offer', true);

            if (response.loginRequired === undefined) {
                location.reload();
            }
        }

    }

    // synerbay.deleteOffer= function(offerID) {
    //     // console.log(productQty);
    //     let response = synerbay.restCall({
    //         'offerID': offerID
    //     }, 'delete_offer', true);
    //     if (response.loginRequired === undefined) {
    //         location.reload();
    //     }
    // }

    synerbay.deleteOffer= function(offerID) {

        if(confirm('Are you sure you want to do this?')){
            let response = synerbay.restCall({
                        'offerID': offerID
                    }, 'delete_offer', true);

            console.log(response);

            if (response.loginRequired === undefined) {
                        location.reload();
            }
        }

        // synerbay.showModal('deleteOfferModal');
    }

    /**
     *
     * @param callParams
     * @param endpoint
     * @param returnResponse
     * @param async
     * @returns {boolean}
     */
    synerbay.restCall = function (callParams, endpoint, returnResponse = false, async = false) {
        synerbay.showLoader();

        let returnData = true;

        $.ajax({
            type: "post",
            dataType: "json",
            async: async,
            url : synerbayAjax.restURL + 'synerbay/api/v1/' + endpoint,
            beforeSend: function (xhr) {
                xhr.setRequestHeader('X-WP-Nonce', synerbayAjax.restNonce)
            },
            data : callParams,
            success: function(response, textStatus, xhr) {
                if(response.success !== 'undefined' && response.success)  {
                    if (returnResponse) {
                        returnData = response.data;
                    }

                    /**
                     * Login modal?
                     */
                    if (response.data.loginRequired !== undefined && response.data.loginRequired) {
                        synerbay.showModal('login');
                    }
                }
            }
        });

        synerbay.hideLoader();
        return returnData;
    }


    synerbay.showLoader = function() {
        let loader = document.getElementsByClassName('loader')[0];
        loader.style.display = "";
    }

    synerbay.hideLoader = function() {
        let loader = document.getElementsByClassName('loader')[0];
        loader.style.display = "none";
    }

    /**
     * Document ready
     */
    $(function () {
        synerbay.init();
        window.synerbay = synerbay;
    });
})
(jQuery);