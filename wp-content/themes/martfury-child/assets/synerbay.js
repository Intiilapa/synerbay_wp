(function ($) {
    'use strict';
    var synerbay = synerbay || {};
    synerbay.init = function () {
        synerbay.$body = $(document.body);
        synerbay.$window = $(window);
    }

    synerbay.processGlobalSearchInput = function(selectObject) {
        var selectedOption = selectObject.options[selectObject.selectedIndex];
        document.getElementById('global-search-form').action = selectedOption.dataset.rewrite;
        document.getElementById('global-search-input').name = selectedOption.dataset.param;
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
                
                synerbay.restCall({
                    'offerID': offerID,
                    'productQty': productQty,
                }, 'appear_offer').then(function(result) {
                    if (result.data.success !== 'undefined' && result.data.success) {
                        synerbay.processToastMessages(result.data.messages, true)
                        location.reload();
                    } else {
                        synerbay.processToastMessages(result.data.messages)
                    }
                });
            }
        });
    }

    synerbay.disAppearOffer= function(offerID) {
        DayPilot.Modal.confirm("Are you sure?").then(function(args) {
            if (args.result) {
                synerbay.restCall({
                    'offerID': offerID
                }, 'disappear_offer').then(function(result) {
                    if (result.data.success !== 'undefined' && result.data.success) {
                        synerbay.processToastMessages(result.data.messages, true)
                        location.reload();
                    } else {
                        synerbay.processToastMessages(result.data.messages)
                    }
                });
            }
        });
    }

    synerbay.disAppearOfferDashboard= function(offerID) {
        DayPilot.Modal.confirm("Are you sure?").then(function(args) {
            if (args.result) {
                synerbay.restCall({
                    'offerID': offerID
                }, 'disappear_offer').then(function(result) {
                    if (result.data.success !== 'undefined' && result.data.success) {
                        let row = document.getElementById("my_active_offer_row_" + offerID);
                        row.parentNode.removeChild(row);
                    }

                    synerbay.processToastMessages(result.data.messages)
                });
            }
        });
    }

    synerbay.deleteOffer= function(offerID) {
        DayPilot.Modal.confirm("Are you sure?").then(function(args) {
            if (args.result) {
                synerbay.restCall({
                    'offerID': offerID
                }, 'delete_offer').then(function(result) {
                    if (result.data.deleted !== 'undefined' && result.data.deleted) {
                        // synerbay.processToastMessages(result.data.messages, true)
                        synerbay.processToastMessages(result.data.messages)
                        let row = document.getElementById("my_offer_row_" + offerID);
                        row.parentNode.removeChild(row);
                        // location.reload();
                    } else {
                        synerbay.processToastMessages(result.data.messages)
                    }
                });
            }
        });
    }

    synerbay.inviteUser = function(inviteUrl) {
        let form = [
            {name: "Name", id: "name"},
            {name: "E-mail", id: "email"},
        ];

        DayPilot.Modal.form(form, {}, {message: 'Please add a valid e-mail.'}).then(function(args) {
            if (args.result) {
                synerbay.restCall({
                    'inviteUrl': inviteUrl,
                    'email': args.result.email,
                    'name': args.result.name,
                }, 'invite').then(function(result) {
                    synerbay.processToastMessages(result.data.messages)
                });
            }
        });
    }

    synerbay.createRFQ = function(productID) {
        let form = [
            {name: "Quantity", id: "qty"},
        ];

        DayPilot.Modal.form(form, {}, {message: 'Mennyi kell belőle?'}).then(function(args) {
            if (args.result) {
                synerbay.restCall({
                    'productID': productID,
                    'qty': args.result.qty,
                }, 'request_for_quotation').then(function(result) {
                    synerbay.processToastMessages(result.data.messages, result.data.success)

                    if (result.data.success) {
                        location.reload();
                    }
                });
            }
        });
    }

    synerbay.deleteRFQ = function(rfqID) {
        DayPilot.Modal.confirm("Are you sure?").then(function(args) {
            if (args.result) {
                synerbay.restCall({
                    'rfqID': rfqID
                }, 'delete_request_for_quotation').then(function(result) {
                    synerbay.processToastMessages(result.data.messages, result.data.success)

                    if (result.data.success) {
                        location.reload();
                    }
                });
            }
        });
    }

    /**
     *
     * @param data
     * @param endpoint
     * @returns {Promise<unknown>}
     */
    synerbay.restCall = (data, endpoint) => {
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
                        console.log(request);
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
                window.localStorage.setItem('toastMessages', JSON.stringify(messages));
            } else {
                synerbay.showToastMessages(messages);
            }
        }
    }

    synerbay.showToastMessagesFromLocalStorage = function() {
        let toastMessages = window.localStorage.getItem('toastMessages');
        window.localStorage.removeItem('toastMessages');

        if (toastMessages !== 'undefined') {
            synerbay.showToastMessages(JSON.parse(toastMessages));
        }
    }

    synerbay.showToastMessages = function(messages) {
        if (messages !== 'undefined') {
            for (let i in messages) {
                for (let j in messages[i]) {
                    window.notification[j]({
                        message: messages[i][j]
                    });
                }
            }
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
        window.synerbay = synerbay;
        setTimeout(() => {
            synerbay.showToastMessagesFromLocalStorage();
        }, 500);
    });
})
(jQuery);