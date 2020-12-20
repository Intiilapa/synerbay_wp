(function ($) {
    'use strict';
    var synerbay = synerbay || {};
    synerbay.init = function () {
        synerbay.$body = $(document.body);
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
        DayPilot.Modal.confirm("Are you sure?").then(function(args) {
            if (args.result) {
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
        });
    }

    synerbay.disAppearOffer= function(offerID) {
        DayPilot.Modal.confirm("Are you sure?").then(function(args) {
            if (args.result) {
                let response = synerbay.restCall({
                    'offerID': offerID
                }, 'disappear_offer', true);

                if (response.loginRequired === undefined) {
                    location.reload();
                }
            }
        });
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
        DayPilot.Modal.confirm("Are you sure?").then(function(args) {
            if (args.result) {
                synerbay.restCall2({
                    'offerID': offerID
                }, 'delete_offer').then(function(result) {
                    if (result.data.deleted !== 'undefined' && result.data.deleted) {
                        let row = document.getElementById("my_offer_row_" + offerID);
                        row.parentNode.removeChild(row);
                    }

                    if (result.data.messages !== 'undefined') {
                        for (let i in result.data.messages) {
                            for (let j in result.data.messages[i]) {
                                window.notification[j]({
                                    message: result.data.messages[i][j]
                                });
                            }

                        }
                    }
                });
            }
        });
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

    synerbay.restCall2 = (data, endpoint) => {
        return new Promise(function(resolve, reject) {
            synerbay.showLoader();

            // create form data
            const formData = new FormData();
            Object.keys(data).forEach(key => formData.append(key, data[key]));

            let request = new XMLHttpRequest();
            request.responseType = 'json';
            request.onreadystatechange = function() {
                if (request.readyState === XMLHttpRequest.DONE) {
                    synerbay.hideLoader();
                    // resolve(request.response, request.status);
                    if (request.status === 200) {
                        resolve(request.response);
                    } else if (request.status === 401) {
                        synerbay.showModal('login');
                    } else {
                        reject(Error(request.status));
                    }
                }
            };
            request.onerror = function() {
                synerbay.hideLoader();
                reject(Error("Network Error"));
            };
            request.open('post', synerbayAjax.restURL + 'synerbay/api/v1/' + endpoint, true);
            request.setRequestHeader('X-WP-Nonce', synerbayAjax.restNonce)
            request.send(formData);
        });
    }

    synerbay.processToastMessages = function(messages, saveToLocalStorage = false) {
        if (messages !== 'undefined') {
            if (saveToLocalStorage) {

            }
        }
    }

    synerbay.showToastMessagesFromLocalStorage = function() {

    }

    synerbay.showToastMessages = function(messages) {
        if (messages !== 'undefined') {

        }
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
        synerbay.showToastMessagesFromLocalStorage();
        window.synerbay = synerbay;
    });
})
(jQuery);