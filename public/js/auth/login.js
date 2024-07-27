import {toast} from '../global.js';
"use strict";

var fvLogin = (function () {

    let form, loginForm, url, submit= $('.form-btn-submit');

    var _handleLogin = function() {
        window.onload = function () {
            if (window.history.replaceState) {
                window.history.replaceState(null, null, window.location.href);
            }
        }
        form = document.querySelector("#form-login");
        url  = KTUtil.attr(form,'action');
        loginForm = FormValidation.formValidation(form, {
            fields: {
                username: { validators: { notEmpty: { message: "This field is required" } },
                },
                password: { validators: { notEmpty: { message: "This field is required" } },
                },
            },
            plugins: {
                trigger: new FormValidation.plugins.Trigger(),
                submitButton: new FormValidation.plugins.SubmitButton(),
                bootstrap: new FormValidation.plugins.Bootstrap5({
                    rowSelector: ".fv-row",
                    eleInvalidClass: "",
                    eleValidClass: "",
                }),
            }
        });

        submit.click((e) => {
            e.preventDefault()
            e.stopImmediatePropagation()
            loginForm.validate().then((i) => {
                if (i == "Valid") {
                    submit.attr("data-kt-indicator", "on").attr('disabled', true);
                    FormValidation.utils.fetch(url, {
                        method: 'POST',
                        dataType: 'json',
                        credentials: "same-origin",
                        headers: { "X-CSRF-Token": $('meta[name="csrf-token"]').attr('content') },
                        params: {
                            username: form.querySelector('[name="username"]').value,
                            password: form.querySelector('[name="password"]').value,
                        },
                    }).then((res) => {
                        toast().fire({
                            icon: res.status,
                            title: res.message
                        });
                        if(res.payload == 'throttle'){
                            submit.attr("data-kt-indicator", "on").attr('disabled', true);
                        }
                        if(res.status == 'error'){
                            $("#csrf-token").attr('content', res.payload);
                            submit.attr("data-kt-indicator", "off").attr('disabled', false);
                        }
                        if(res.status == 'success'){
                            window.location.replace(res.payload);
                        }
                    });
                }
            });
        });

    }

    return {
        init: function () {
            _handleLogin()
        }
    };

})();

jQuery(document).ready(function() {
    fvLogin.init();
});
