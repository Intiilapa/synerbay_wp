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
        document.getElementById('global-search-form').method = selectedOption.dataset.method;
        document.getElementById('global-search-input').name = selectedOption.dataset.param;
    }

    // user login
    synerbay.showModal= function(offerID) {
        DayPilot.Modal.alert("<h3>Login required</h3>In order to place order you need to be signed in. Please register or login <a href=\"/my-account\">here</a>", {theme: "modal_rounded"}).then(function(args) {
        });
    }

    // customer
    synerbay.appearOffer= function(offerID) {
        DayPilot.Modal.confirm("Are you sure?", {theme: "modal_rounded"}).then(function(args) {
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

    // customer
    synerbay.disAppearOffer= function(offerID) {
        DayPilot.Modal.confirm("Are you sure?", {theme: "modal_rounded"}).then(function(args) {
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

    // customer
    synerbay.disAppearOfferDashboard= function(offerID) {
        DayPilot.Modal.confirm("Are you sure?", {theme:"modal_rounded"}).then(function(args) {
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

    // vendor
    synerbay.acceptApply= function(id) {
        DayPilot.Modal.confirm("Are you sure?<br><br><strong>SUGGESTION!:</strong> It’s important to make sure that the customer is trustworthy. If you are not sure about that, please contact them before.", {theme: "modal_rounded"}).then(function(args) {
            if (args.result) {
                synerbay.restCall({
                    'id': id,
                }, 'accept_apply').then(function(result) {
                    console.log(result);
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

    // vendor
    synerbay.rejectApply= function(id) {
        let form = [
            {name: "Reason", id: "reason"},
        ];

        DayPilot.Modal.form(form, {}, {message: 'Biztos elutasítod?<br><br>Amennyiben fontosnak tartod pár szóban indokolhatod döntésed.', theme: "modal_rounded"}).then(function(args) {
            if (args.result) {
                synerbay.restCall({
                    'id': id,
                    'reason': args.result.reason,
                }, 'reject_apply').then(function(result) {
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

    // vendor
    synerbay.deleteOffer= function(offerID) {
        DayPilot.Modal.confirm("Are you sure?", {theme: "modal_rounded"}).then(function(args) {
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

    /**
     * @param getInviterName
     * @param endPoint
     * @param inviteUrl
     * @param msg
     */
    synerbay.inviteWrapper = function(getInviterName, endPoint, inviteUrl, msg) {
        let form;

        if (getInviterName) {
            form = [
                {name: "Your name", id: "inviterName"},
                {name: "Invited name", id: "invitedName"},
                {name: "Invited e-mail", id: "invitedEmail"},
            ];
        } else {
            form = [
                {name: "Invited name", id: "invitedName"},
                {name: "Invited e-mail", id: "invitedEmail"},
            ];
        }

        DayPilot.Modal.form(form, {}, {message: msg, theme: "modal_rounded" }).then(function(args) {
            if (args.result) {
                synerbay.restCall({
                    'inviteUrl': inviteUrl,
                    'inviterName': args.result.inviterName !== 'undefined' ? args.result.inviterName : '',
                    'invitedName': args.result.invitedName,
                    'invitedEmail': args.result.invitedEmail,
                }, endPoint).then(function(result) {
                    synerbay.processToastMessages(result.data.messages)
                });
            }
        });
    }

    synerbay.inviteUserHeader = function(inviteUrl, getInviterName) {
        synerbay.inviteWrapper(getInviterName, 'inviteHeader', inviteUrl, 'Invite suppliers to see new offers, customers to get more order request, or partners to collaborate with to take advantage of discounted prices.');
    }

    synerbay.inviteUserOfferPage = function(inviteUrl, getInviterName) {
        synerbay.inviteWrapper(getInviterName, 'inviteOffer', inviteUrl, '[Offer] Invite suppliers to see new offers, customers to get more order request, or partners to collaborate with to take advantage of discounted prices.');
    }

    synerbay.inviteUserProductPage = function(inviteUrl, getInviterName) {
        synerbay.inviteWrapper(getInviterName, 'inviteProduct', inviteUrl, '[Product] Invite suppliers to see new offers, customers to get more order request, or partners to collaborate with to take advantage of discounted prices.');
    }

    synerbay.createRFQ = function(productID) {
        let form = [
            {name: "Quantity", id: "qty"},
        ];

        DayPilot.Modal.form(form, {}, {message: 'Send Request For Quotation.<br> Set your quantity need.', theme: "modal_rounded"}).then(function(args) {
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
        DayPilot.Modal.confirm("Are you sure?", {theme: "modal_rounded"}).then(function(args) {
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
        let loader = document.getElementsByClassName('sb-chase')[0];
        loader.style.display = "";

        let backgroundLoader = document.getElementsByClassName('martfury-loader')[0];
        backgroundLoader.style.display = "block";
    }

    synerbay.hideLoader = function() {
        let loader = document.getElementsByClassName('sb-chase')[0];
        loader.style.display = "none";

        let backgroundLoader = document.getElementsByClassName('martfury-loader')[0];
        backgroundLoader.style.display = "none";
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