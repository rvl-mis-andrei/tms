"use strict";
import {Alert} from "../../../global/alert.js"
import {RequestHandler} from "../../../global/request.js"
import {modal_state,fv_validator} from "../../../global.js"


export function fvHaulingPlanInfo(param){

    var init_fvHaulingPlanInfo = (function () {

        var _handlefvMasterPlan = function(){
            let page = $('.haulage_info_page');
            let form = document.querySelector("#form");
            let modal_id = form.getAttribute('modal-id');
            let fvHaulingPlanInfo = FormValidation.formValidation(form, {
                fields: {
                    masterlist: {
                        validators: {
                            notEmpty: { message: 'This field is required'
                            },
                            file: {
                                extension: 'xls,xlsx,xlsm,csv',
                                type: 'application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,text/csv',
                                message: 'Please select a valid Excel or CSV file (xls, xlsx, xlsm, csv)'
                            },
                            fileSize: {
                                maxSize: 20480 * 1024, // in bytes (20 MB in this example)
                                message: 'The file is too large. Maximum size allowed is 20 MB.'
                            }
                            // remote: {
                            //     url: '/services/haulage/validate',
                            //     method: 'POST',
                            //     message: 'The name is not unique',
                            //     headers: {
                            //         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            //     },
                            //     data: function(){
                            //         return {  id: $('.submit').attr('data-id')  };
                            //     }
                            // }
                        },
                    },
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
                        fvHaulingPlanInfo.resetForm();
                        form.reset();
                        $('#form').attr('action','/services/haulage_info/masterlist');
                        $('.submit').attr('data-id','');
                        $('.modal_title').text('Upload MasterList');
                    }
                })
            })

            page.on('click','.submit',function(e){
                e.preventDefault()
                e.stopImmediatePropagation()

                let btn_submit = $(this);
                let form_url = form.getAttribute('action');

                // btn_submit.attr("data-kt-indicator","on");
                // btn_submit.attr("disabled",true);

                fvHaulingPlanInfo && fvHaulingPlanInfo.validate().then(function (v) {
                    if(v == "Valid"){
                        Alert.confirm("question","Submit this form?", {
                            onConfirm: function() {
                                let formData = new FormData(form);
                                if(param) {
                                    formData.append('id',param)
                                }
                                (new RequestHandler).post(form_url,formData,true).then((res) => {
                                    Alert.toast(res.status,res.message);
                                    if(res.status == 'success'){
                                        form.reset();
                                    }
                                    fvHaulingPlanInfo.resetForm();
                                    modal_state(modal_id);
                                })
                                .catch((error) => {
                                    console.log(error)
                                    Alert.alert('error',"Something went wrong. Try again later", false);
                                })
                                .finally(() => {
                                    btn_submit.attr("data-kt-indicator","off");
                                    btn_submit.attr("disabled",false);
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
            _handlefvMasterPlan();
        },
      };

    })();

    KTUtil.onDOMContentLoaded(function () {
        init_fvHaulingPlanInfo.init();
    });

}
