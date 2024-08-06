"use strict";
import {Alert} from "../../../global/alert.js"
import {RequestHandler} from "../../../global/request.js"
import {modal_state} from "../../../global.js"

export function fvNewClient(){

    var init_fvNewClient = (function () {

        var _handleFvNewClient = function(){
            let page = $('.client_listing');
            let form = document.querySelector("#form");
            let modal_id = form.getAttribute('modal-id');
            let fvNewClient = FormValidation.formValidation(form, {
                fields: {
                    name: {
                        validators: {
                            notEmpty: { message: 'This field is required'
                            },
                            remote: {
                                url: '/services/client/validate',
                                method: 'POST',
                                message: 'The name is not unique',
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                data: function(){
                                    return {  id: $('.submit').attr('data-id')  };
                                }
                            }
                        },
                    },
                    is_active: {validators: { notEmpty: { message: 'This field is required' } }},
                },
                plugins: {
                trigger: new FormValidation.plugins.Trigger(),
                bootstrap: new FormValidation.plugins.Bootstrap5({
                    rowSelector: ".fv-row",
                    eleInvalidClass: "",
                    eleValidClass: "",
                }),
                },
            })

            page.on('click','.cancel',function(e){
                e.preventDefault()
                e.stopImmediatePropagation()
                Alert.confirm('question',"Close this form ?",{
                    onConfirm: () => {
                        modal_state(modal_id);
                        fvNewClient.resetForm();
                        form.reset();
                        $('#form').attr('action','/services/client/create');
                        $('.submit').attr('data-id','');
                        $('.modal_title').text('New Client');
                    }
                })
            })

            page.on('click','.submit',function(e){
                e.preventDefault()
                e.stopImmediatePropagation()

                let btn_submit = $(this);
                let form_url = form.getAttribute('action');

                btn_submit.attr("data-kt-indicator","on");
                btn_submit.attr("disabled",true);

                fvNewClient && fvNewClient.validate().then(function (v) {
                    if(v == "Valid"){
                        Alert.confirm("question","Submit this form?", {
                            onConfirm: function() {
                                let formData = new FormData(form);
                                if(btn_submit.attr('data-id').length > 0) {
                                    formData.append('id',btn_submit.attr('data-id'))
                                }
                                (new RequestHandler).post(form_url,formData).then((res) => {
                                    Alert.toast(res.status,res.message);
                                    if(res.status == 'success' && form_url!='/services/client/update'){
                                        form.reset();
                                    }
                                    fvNewClient.resetForm();
                                })
                                .catch((error) => {
                                    console.log(error)
                                    Alert.alert('error',"Something went wrong. Try again later", false);
                                })
                                .finally(() => {
                                    btn_submit.attr("data-kt-indicator","off");
                                    $("#client_list_table").DataTable().ajax.reload(null, false);
                                });
                            },
                            onCancel: () => {
                                btn_submit.attr("data-kt-indicator","off");
                                btn_submit.attr("disabled",false);
                            }
                        });
                    }else{
                        btn_submit.attr("data-kt-indicator","off");
                        btn_submit.attr("disabled",false);
                    }
                })
            })
        }

    return {
        init: function () {
            _handleFvNewClient();
        },
      };

    })();

    KTUtil.onDOMContentLoaded(function () {
        init_fvNewClient.init();
    });

}
