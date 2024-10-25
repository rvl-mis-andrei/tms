"use strict";
import {Alert} from "../../../global/alert.js"
import {RequestHandler} from "../../../global/request.js"
import {modal_state,fv_validator} from "../../../global.js"
import { trailer_driver } from "../../../global/select.js";
import { DriverDT } from "../dt_controller/serverside/0006_0.js";

export function fvCar(param=false,_table=false){

    var init_fvCar = (function () {

        var _handlefvCar = function(){
            let fvCar;
            let form = document.querySelector("#form_add_car");
            let modal_id = form.getAttribute('modal-id');
            let modalContent = document.querySelector(`${modal_id} .modal-content`);

            let blockUI = new KTBlockUI(modalContent, {
                message: '<div class="blockui-message"><span class="spinner-border text-primary"></span> Loading...</div>',
            });

            if (!form.hasAttribute('data-fv-initialized')) {
                fvCar = FormValidation.formValidation(form, {
                    fields: {
                        car_model: {
                            validators: {
                                notEmpty:{
                                    message:'This field is required'
                                },
                                remote: {
                                    url: '/services/cluster_car/validate_car_model',
                                    method: 'POST',
                                    message: 'Car Model already exist',
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
                        short_name: fv_validator(),
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
                        fvCar.resetForm();
                        form.reset();
                        form.setAttribute('action','/services/cluster_car/update');
                        $(modal_id).find('.modal_title').text('New Car Model');
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
                fvCar && fvCar.validate().then(function (v) {
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
                                        fvCar.resetForm();
                                        if($(_table).length){
                                            _table ?$(_table).DataTable().ajax.reload() :'';
                                        }else{
                                            DriverDT().init()
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
                _handlefvCar();
            },
        };

    })();

    KTUtil.onDOMContentLoaded(function () {
        init_fvCar.init();
    });

}
