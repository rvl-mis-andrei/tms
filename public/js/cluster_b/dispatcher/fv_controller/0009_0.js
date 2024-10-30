"use strict";
import {Alert} from "../../../global/alert.js"
import {RequestHandler} from "../../../global/request.js"
import {modal_state,fv_validator} from "../../../global.js"
import { GarageDT, LocationDT, TrailerTypeDT } from "../dt_controller/serverside/0009_0.js";

export function fvGarage(param=false,_table=false){

    var init_fvGarage = (function () {

        var _handlefvGarage = function(){
            let fvGarage;
            let form = document.querySelector("#form_add_garage");
            let modal_id = form.getAttribute('modal-id');
            let modalContent = document.querySelector(`${modal_id} .modal-content`);

            let blockUI = new KTBlockUI(modalContent, {
                message: '<div class="blockui-message"><span class="spinner-border text-primary"></span> Loading...</div>',
            });

            if (!form.hasAttribute('data-fv-initialized')) {
                fvGarage = FormValidation.formValidation(form, {
                    fields: {
                        garage_name: {
                            validators: {
                                notEmpty:{
                                    message:'This field is required'
                                },
                                remote: {
                                    url: '/services/settings/garage/validate_garage',
                                    method: 'POST',
                                    message: 'Garage already exist',
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                    },
                                    data: function() {
                                        let data_id = $(modal_id).find('button.submit').attr('data-id');
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
                        fvGarage.resetForm();
                        form.reset();
                        $(modal_id).find('.modal_title').text('New Garage');
                        $(modal_id).find('.submit').attr('data-id','');
                        $(modal_id).find('select[name="status"]').val(1).trigger('change').parent().addClass('d-none');
                    }
                })
            })

            $(modal_id).on('click','.submit',function(e){
                e.preventDefault()
                e.stopImmediatePropagation()

                let _this = $(this);
                let url = form.getAttribute('action');
                fvGarage && fvGarage.validate().then(function (v) {
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
                                        fvGarage.resetForm();
                                        if($(_table).length){
                                            _table ?$(_table).DataTable().ajax.reload() :'';
                                        }else{
                                            GarageDT().init()
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
                _handlefvGarage();
            },
        };

    })();

    KTUtil.onDOMContentLoaded(function () {
        init_fvGarage.init();
    });

}

export function fvLocation(param=false,_table=false){

    var init_fvLocation = (function () {

        var _handlefvLocation = function(){
            let fvLocation;
            let form = document.querySelector("#form_add_location");
            let modal_id = form.getAttribute('modal-id');
            let modalContent = document.querySelector(`${modal_id} .modal-content`);

            let blockUI = new KTBlockUI(modalContent, {
                message: '<div class="blockui-message"><span class="spinner-border text-primary"></span> Loading...</div>',
            });

            if (!form.hasAttribute('data-fv-initialized')) {
                fvLocation = FormValidation.formValidation(form, {
                    fields: {
                        location_name: {
                            validators: {
                                notEmpty:{
                                    message:'This field is required'
                                },
                                remote: {
                                    url: '/services/settings/location/validate_location',
                                    method: 'POST',
                                    message: 'Location already exist',
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                    },
                                    data: function() {
                                        let data_id = $(modal_id).find('button.submit').attr('data-id');
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
                        fvLocation.resetForm();
                        form.reset();
                        $(modal_id).find('.modal_title').text('New Location');
                        $(modal_id).find('.submit').attr('data-id','');
                        $(modal_id).find('select[name="status"]').val(1).trigger('change').parent().addClass('d-none');
                    }
                })
            })

            $(modal_id).on('click','.submit',function(e){
                e.preventDefault()
                e.stopImmediatePropagation()

                let _this = $(this);
                let url = form.getAttribute('action');
                fvLocation && fvLocation.validate().then(function (v) {
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
                                        fvLocation.resetForm();
                                        if($(_table).length){
                                            _table ?$(_table).DataTable().ajax.reload() :'';
                                        }else{
                                            LocationDT().init()
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
                _handlefvLocation();
            },
        };

    })();

    KTUtil.onDOMContentLoaded(function () {
        init_fvLocation.init();
    });

}

export function fvTrailerType(param=false,_table=false){

    var init_fvTrailerType = (function () {

        var _handlefvTrailerType = function(){
            let fvTrailerType;
            let form = document.querySelector("#form_add_trailer_type");
            let modal_id = form.getAttribute('modal-id');
            let modalContent = document.querySelector(`${modal_id} .modal-content`);

            let blockUI = new KTBlockUI(modalContent, {
                message: '<div class="blockui-message"><span class="spinner-border text-primary"></span> Loading...</div>',
            });

            if (!form.hasAttribute('data-fv-initialized')) {
                fvTrailerType = FormValidation.formValidation(form, {
                    fields: {
                        trailer_type_name: {
                            validators: {
                                notEmpty:{
                                    message:'This field is required'
                                },
                                remote: {
                                    url: '/services/settings/trailer_type/validate_trailer_type',
                                    method: 'POST',
                                    message: 'Trailer Type already exist',
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                    },
                                    data: function() {
                                        let data_id = $(modal_id).find('button.submit').attr('data-id');
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
                        fvTrailerType.resetForm();
                        form.reset();
                        $(modal_id).find('.modal_title').text('New Trailer Type');
                        $(modal_id).find('.submit').attr('data-id','');
                        $(modal_id).find('select[name="status"]').val(1).trigger('change').parent().addClass('d-none');
                    }
                })
            })

            $(modal_id).on('click','.submit',function(e){
                e.preventDefault()
                e.stopImmediatePropagation()

                let _this = $(this);
                let url = form.getAttribute('action');
                fvTrailerType && fvTrailerType.validate().then(function (v) {
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
                                        fvTrailerType.resetForm();
                                        if($(_table).length){
                                            _table ?$(_table).DataTable().ajax.reload() :'';
                                        }else{
                                            TrailerTypeDT().init()
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
                _handlefvTrailerType();
            },
        };

    })();

    KTUtil.onDOMContentLoaded(function () {
        init_fvTrailerType.init();
    });

}
