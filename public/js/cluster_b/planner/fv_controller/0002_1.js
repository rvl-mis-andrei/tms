"use strict";
import {Alert} from "../../../global/alert.js"
import {RequestHandler} from "../../../global/request.js"
import {modal_state,fv_validator} from "../../../global.js"


export function fvHaulingPlanInfo(param){

    var init_fvHaulingPlanInfo = (function () {

        var _handlefvMasterList = function(){
            let form = document.querySelector("#form_masterlist");
            let modal_id = form.getAttribute('modal-id');
            let modalContent = document.querySelector(`${modal_id} .modal-content`);
            let blockUI = new KTBlockUI(modalContent, {
                message: '<div class="blockui-message"><span class="spinner-border text-primary"></span> Loading...</div>',
            });

            let fvMasterPlan = FormValidation.formValidation(form, {
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

            $(modal_id).on('click','.cancel',function(e){
                e.preventDefault()
                e.stopImmediatePropagation()
                Alert.confirm('question',"Close this form ?",{
                    onConfirm: () => {
                        modal_state(modal_id);
                        fvMasterPlan.resetForm();
                        form.reset();
                        $('#form').attr('action','/services/haulage_info/masterlist');
                        $('.submit').attr('data-id','');
                        $('.modal_title').text('Upload MasterList');
                    }
                })
            })

            $(modal_id).on('click','.submit',function(e){
                e.preventDefault()
                e.stopImmediatePropagation()

                let btn_submit = $(this);
                let form_url = form.getAttribute('action');

                fvMasterPlan && fvMasterPlan.validate().then(function (v) {
                    if(v == "Valid"){
                        Alert.confirm("question","Submit this form?", {
                            onConfirm: function() {

                                blockUI.block();

                                let formData = new FormData(form);
                                btn_submit.attr("data-kt-indicator","on");
                                btn_submit.attr("disabled",true);
                                formData.append('batch',$('select[name="batch"]').val())
                                if(param) { formData.append('id',param) }
                                (new RequestHandler).post(form_url,formData,true).then((res) => {
                                    Alert.toast(res.status,res.message)
                                    if(res.status == 'success'){
                                        if(res.payload >= 1){
                                            Alert.loading("Page is refreshing . . .",{
                                                didOpen:function(){
                                                    setTimeout(function() {
                                                        window.location.reload();
                                                    }, 300);
                                                }
                                            });
                                        }else{

                                        }
                                        // form.reset()
                                        // fvMasterPlan.resetForm()
                                        // $('.nav-tab.active').click()
                                        // modal_state(modal_id)
                                    }else {
                                        Alert.alert('error',res.message, false)
                                    }
                                })
                                .catch((error) => {
                                    console.log(error)
                                    Alert.alert('error',"Something went wrong. Try again later", false);
                                })
                                .finally(() => {
                                    btn_submit.attr("data-kt-indicator","off");
                                    btn_submit.attr("disabled",false);
                                    blockUI.release();
                                });
                            },
                            onCancel: () => {
                                btn_submit.attr("data-kt-indicator","off");
                                btn_submit.attr("disabled",false);
                            }
                        });
                    }
                })
            })
        }

        var _handlefvHaulingPlan = function(){
            let form = document.querySelector("#form_hauling_plan");
            let modal_id = form.getAttribute('modal-id');

            let modalContent = document.querySelector(`${modal_id} .modal-content`);
            let blockUI = new KTBlockUI(modalContent, {
                message: '<div class="blockui-message"><span class="spinner-border text-primary"></span> Loading...</div>',
            });

            let fvFinalHaulingPlan = FormValidation.formValidation(form, {
                fields: {
                    hauling_plan: {
                        validators: {
                            notEmpty: { message: 'This field is required'
                            },
                            file: {
                                extension: 'xls,xlsx,xlsm',
                                type: 'application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.ms-excel.sheet.macroenabled.12',
                                message: 'Please select a valid Excel file (xls, xlsx, xlsm)'
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

            $(modal_id).on('click','.cancel',function(e){
                e.preventDefault()
                e.stopImmediatePropagation()
                Alert.confirm('question',"Close this form ?",{
                    onConfirm: () => {
                        modal_state(modal_id);
                        fvFinalHaulingPlan.resetForm();
                        form.reset();
                        $('#form').attr('action','/services/haulage_info/masterlist');
                        $('.submit').attr('data-id','');
                        $('.modal_title').text('Upload MasterList');
                    }
                })
            })

            $(modal_id).on('click','.submit',function(e){
                e.preventDefault()
                e.stopImmediatePropagation()

                let btn_submit = $(this);
                let form_url = form.getAttribute('action');

                fvFinalHaulingPlan && fvFinalHaulingPlan.validate().then(function (v) {
                    if(v == "Valid"){
                        Alert.confirm("question","Submit this form?", {
                            onConfirm: function() {
                                blockUI.block();
                                let formData = new FormData(form);
                                formData.append('batch',$('select[name="batch"]').val())
                                btn_submit.attr("data-kt-indicator","on")
                                btn_submit.attr("disabled",true)
                                if(param) {
                                    formData.append('id',param)
                                }
                                (new RequestHandler).post(form_url,formData,true).then((res) => {
                                    if(res.status == 'success'){
                                        if(res.payload >= 2){
                                            Alert.loading("Page is refreshing . . .",{
                                                didOpen:function(){
                                                    setTimeout(function() {
                                                        window.location.reload();
                                                    }, 300);
                                                }
                                            });
                                        }else{
                                            Alert.toast(res.status,res.message)
                                            form.reset()
                                            fvFinalHaulingPlan.resetForm()
                                            $('select[name="batch"]').trigger('change');
                                            $('.nav-tab.active').click()
                                            modal_state(modal_id)
                                        }
                                    }else {
                                        Alert.alert('error',res.message, false)
                                    }
                                })
                                .catch((error) => {
                                    console.log(error)
                                    Alert.alert('error',"Something went wrong. Try again later", false)
                                })
                                .finally(() => {
                                    btn_submit.attr("data-kt-indicator","off")
                                    btn_submit.attr("disabled",false)
                                    blockUI.release();
                                });
                            },
                            onCancel: () => {
                                btn_submit.attr("data-kt-indicator","off")
                                btn_submit.attr("disabled",false)
                            }
                        });
                    }
                })
            })
        }

        var _handlefvNewUnit = function(){
            let form = document.querySelector("#form_new_unit");
            let modal_id = form.getAttribute('modal-id');

            let modalContent = document.querySelector(`${modal_id} .modal-content`);
            let blockUI = new KTBlockUI(modalContent, {
                message: '<div class="blockui-message"><span class="spinner-border text-primary"></span> Loading...</div>',
            });

            let fvNewUnit = FormValidation.formValidation(form, {
                fields: {
                    'invoice_date': {
                        validators: {
                            notEmpty: { message: 'This field is required'
                            },
                            date: {
                                format: 'MM-DD-YYYY',
                                message: 'The date is not valid'
                            }
                        }
                    },
                    'dealer': fv_validator(),
                    'model': fv_validator(),
                    'location': fv_validator(),
                    'hub': fv_validator(),
                    'cs_no': fv_validator(),
                    'color_description': fv_validator(),
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

            $(modal_id).on('click','.cancel',function(e){
                e.preventDefault()
                e.stopImmediatePropagation()
                Alert.confirm('question',"Close this form ?",{
                    onConfirm: () => {
                        modal_state(modal_id);
                        fvNewUnit.resetForm();
                        form.reset();
                        $('#form').attr('action','/tms/cco-b/planner/haulage_info/add_block_unit');
                        $('.modal_title').text('Add New Unit');
                    }
                })
            })

            $(modal_id).on('click','.submit',function(e){
                e.preventDefault()
                e.stopImmediatePropagation()

                let btn_submit = $(this);
                let form_url = form.getAttribute('action');

                fvNewUnit && fvNewUnit.validate().then(function (v) {
                    if(v == "Valid"){
                        Alert.confirm("question","Submit this form?", {
                            onConfirm: function() {
                                blockUI.block();
                                btn_submit.attr("data-kt-indicator","on");
                                btn_submit.attr("disabled",true);
                                let formData = new FormData(form);
                                formData.append('id',param);
                                (new RequestHandler).post(form_url,formData).then((res) => {
                                    Alert.toast(res.status,res.message);
                                    if(res.status == 'success'){
                                        // form.reset();
                                        fvNewUnit.resetForm();
                                        $('.nav-tab.active').click()
                                    }
                                })
                                .catch((error) => {
                                    console.log(error)
                                    Alert.alert('error',"Something went wrong. Try again later", false);
                                })
                                .finally(() => {
                                    btn_submit.attr("data-kt-indicator","off");
                                    btn_submit.attr("disabled",false);
                                    blockUI.release();
                                });
                            },
                            onCancel: () => {
                                btn_submit.attr("data-kt-indicator","off");
                                btn_submit.attr("disabled",false);
                            }
                        });
                    }
                })
            })
        }

    return {
        init: function () {
            _handlefvMasterList()
            _handlefvHaulingPlan()
            _handlefvNewUnit()
        },
      };

    })();

    KTUtil.onDOMContentLoaded(function () {
        init_fvHaulingPlanInfo.init()
    });

}
