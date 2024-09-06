"use strict";
import {Alert} from "../../../global/alert.js"
import {RequestHandler} from "../../../global/request.js"
import {modal_state,fv_validator} from "../../../global.js"


export function fvHaulingPlan(){

    var init_fvHaulingPlan = (function () {

        var _handlefvHaulingPlan = function(){
            let page = $('.hauling_plan');
            let form = document.querySelector("#form");
            let modal_id = form.getAttribute('modal-id');
            let fvHaulingPlan = FormValidation.formValidation(form, {
                fields: {
                    'name': {
                        validators: {
                            notEmpty: { message: 'This field is required'
                            },
                            remote: {
                                url: '/services/haulage/validate',
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
                    'planning_date': {
                        validators: {
                            notEmpty: { message: 'This field is required'
                            },
                            date: {
                                format: 'MM-DD-YYYY',
                                message: 'The date is not valid'
                            }
                        }
                    },
                    status: fv_validator(),
                    'plan_type': fv_validator(),
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
                        fvHaulingPlan.resetForm();
                        form.reset();
                        $('#form').attr('action','/services/haulage/create');
                        $('.submit').attr('data-id','');
                        $('.modal_title').text('New Hauling Plan');
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

                fvHaulingPlan && fvHaulingPlan.validate().then(function (v) {
                    if(v == "Valid"){
                        Alert.confirm("question","Submit this form?", {
                            onConfirm: function() {
                                let formData = new FormData(form);
                                if(btn_submit.attr('data-id').length > 0) {
                                    formData.append('id',btn_submit.attr('data-id'));
                                }
                                (new RequestHandler).post(form_url,formData).then((res) => {
                                    Alert.toast(res.status,res.message);
                                    if(res.status == 'success' && form_url!='/services/haulage/create'){
                                        form.reset();
                                    }
                                    modal_state(modal_id)
                                    fvHaulingPlan.resetForm();
                                })
                                .catch((error) => {
                                    console.log(error)
                                    Alert.alert('error',"Something went wrong. Try again later", false);
                                })
                                .finally(() => {
                                    btn_submit.attr("data-kt-indicator","off");
                                    btn_submit.attr("disabled",false);
                                    $("#hauling_plan_table").DataTable().ajax.reload(null, false);
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
            _handlefvHaulingPlan();
        },
      };

    })();

    KTUtil.onDOMContentLoaded(function () {
        init_fvHaulingPlan.init();
    });

}
