"use strict";
import {Alert} from "../../../global/alert.js"
import {RequestHandler} from "../../../global/request.js"
import {modal_state,fv_validator} from "../../../global.js"
import { TractorDT } from "../dt_controller/serverside/0005_0.js";

export function fvTrailer(param=false,_table=false){

    var init_fvTrailer = (function () {

        var _handleFvTrailer = function(){
            let fvTrailer;
            let form = document.querySelector("#form_add_trailer");
            let modal_id = form.getAttribute('modal-id');
            let modalContent = document.querySelector(`${modal_id} .modal-content`);

            let blockUI = new KTBlockUI(modalContent, {
                message: '<div class="blockui-message"><span class="spinner-border text-primary"></span> Loading...</div>',
            });

            if (!form.hasAttribute('data-fv-initialized')) {
                fvTrailer = FormValidation.formValidation(form, {
                    fields: {
                        trailer_type: fv_validator(),
                        plate_no: {
                            validators: {
                                notEmpty:{
                                    message:'This field is required'
                                },
                                remote: {
                                    url: '/services/trailer/validate_plate_number',
                                    method: 'POST',
                                    message: 'Plate No. already exist',
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                    },
                                    data: function() {
                                        let data_id = $(form).find('input[name="plate_no"]').attr('data-id');
                                        console.log(data_id);
                                        return {
                                            id: data_id
                                        };
                                    }
                                }
                            }
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
                form.setAttribute('data-fv-initialized', 'true');
            }

            $(modal_id).on('click','.cancel',function(e){
                e.preventDefault()
                e.stopImmediatePropagation()
                Alert.confirm('question',"Close this form ?",{
                    onConfirm: () => {
                        modal_state(modal_id);
                        fvTrailer.resetForm();
                        form.reset();
                        form.setAttribute('action','/services/trailer/update');
                        $(modal_id).find('.modal_title').text('New Trailer');
                        $(modal_id).find('.submit').attr('data-id','');
                        $(modal_id).find('select[name="trailer_type"]').val('').trigger('change');
                        $(modal_id).find('select[name="status"]').val('1').trigger('change').parent().addClass('d-none');
                        $(modal_id).find('input[name="plate_no"]').attr('data-id','');
                    }
                })
            })

            $(modal_id).on('click','.submit',function(e){
                e.preventDefault()
                e.stopImmediatePropagation()

                let _this = $(this);
                let url = form.getAttribute('action');
                fvTrailer && fvTrailer.validate().then(function (v) {
                    if(v == "Valid"){
                        Alert.confirm("question","Submit this form?", {
                            onConfirm: function() {
                                blockUI.block();
                                _this.attr("data-kt-indicator","on");
                                _this.attr("disabled",true);
                                let formData = new FormData(form);
                                formData.append('id',_this.attr('data-id') ?? '');
                                (new RequestHandler).post(url,formData).then((res) => {
                                    Alert.toast(res.status,res.message);
                                    if(res.status == 'success'){
                                        fvTrailer.resetForm();
                                        if($(_table).length){
                                            _table ?$(_table).DataTable().ajax.reload() :'';
                                        }else{
                                            TrailerDT().init();
                                        }
                                    }
                                })
                                .catch((error) => {
                                    console.log(error)
                                    Alert.alert('error',"Something went wrong. Try again later", false);
                                })
                                .finally(() => {
                                    _this.attr("data-kt-indicator","off");
                                    _this.attr("disabled",false);
                                    blockUI.release();
                                });
                            },
                        });
                    }
                })
            })
        }

        return {
            init: function () {
                _handleFvTrailer()
            },
        };

    })();

    KTUtil.onDOMContentLoaded(function () {
        init_fvTrailer.init();
    });

}


export function fvTractor(param=false,_table=false){

    var init_fvTractor = (function () {

        var _handleFvTractor = function(){
            let fvTractor;
            let form = document.querySelector("#form_add_tractor");
            let modal_id = form.getAttribute('modal-id');
            let modalContent = document.querySelector(`${modal_id} .modal-content`);

            let blockUI = new KTBlockUI(modalContent, {
                message: '<div class="blockui-message"><span class="spinner-border text-primary"></span> Loading...</div>',
            });

            if (!form.hasAttribute('data-fv-initialized')) {
                fvTractor = FormValidation.formValidation(form, {
                    fields: {
                        body_no: {
                            validators: {
                                notEmpty:{
                                    message:'This field is required'
                                },
                                remote: {
                                    url: '/services/tractor/validate_body_number',
                                    method: 'POST',
                                    message: 'Body No. already exist',
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                    },
                                    data: function() {
                                        let data_id = $(form).find('input[name="body_no"]').attr('data-id');
                                        return {
                                            id: data_id
                                        };
                                    }
                                }
                            }
                        },
                        plate_no: {
                            validators: {
                                notEmpty:{
                                    message:'This field is required'
                                },
                                remote: {
                                    url: '/services/tractor/validate_plate_number',
                                    method: 'POST',
                                    message: 'Plate No. already exist',
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                    },
                                    data: function() {
                                        let data_id = $(form).find('input[name="plate_no"]').attr('data-id');
                                        return {
                                            id: data_id
                                        };
                                    }
                                }
                            }
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
                form.setAttribute('data-fv-initialized', 'true');
            }

            $(modal_id).on('click','.cancel',function(e){
                e.preventDefault()
                e.stopImmediatePropagation()
                Alert.confirm('question',"Close this form ?",{
                    onConfirm: () => {
                        modal_state(modal_id);
                        fvTractor.resetForm();
                        form.reset();
                        form.setAttribute('action','/services/tractor/update');
                        $(modal_id).find('.modal_title').text('New Tractor');
                        $(modal_id).find('.submit').attr('data-id','');
                        $(modal_id).find('input[name="plate_no"]').attr('data-id','');
                        $(modal_id).find('input[name="body_no"]').attr('data-id','');
                    }
                })
            })

            $(modal_id).on('click','.submit',function(e){
                e.preventDefault()
                e.stopImmediatePropagation()

                let _this = $(this);
                let url = form.getAttribute('action');
                fvTractor && fvTractor.validate().then(function (v) {
                    if(v == "Valid"){
                        Alert.confirm("question","Submit this form?", {
                            onConfirm: function() {
                                blockUI.block();
                                _this.attr("data-kt-indicator","on");
                                _this.attr("disabled",true);
                                let formData = new FormData(form);
                                formData.append('id',_this.attr('data-id') ?? '');
                                (new RequestHandler).post(url,formData).then((res) => {
                                    Alert.toast(res.status,res.message);
                                    if(res.status == 'success'){
                                        fvTractor.resetForm();
                                        if($(_table).length){
                                            _table ?$(_table).DataTable().ajax.reload() :'';
                                        }else{
                                            TractorDT().init();
                                        }

                                    }
                                })
                                .catch((error) => {
                                    console.log(error)
                                    Alert.alert('error',"Something went wrong. Try again later", false);
                                })
                                .finally(() => {
                                    _this.attr("data-kt-indicator","off");
                                    _this.attr("disabled",false);
                                    blockUI.release();
                                });
                            },
                        });
                    }
                })
            })
        }

        return {
            init: function () {
                _handleFvTractor()
            },
        };

    })();

    KTUtil.onDOMContentLoaded(function () {
        init_fvTractor.init();
    });

}
