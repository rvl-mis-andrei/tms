"use strict";
import {Alert} from "../../../global/alert.js"
import {RequestHandler} from "../../../global/request.js"
import {modal_state,fv_validator} from "../../../global.js"

export function fvNewDealership(param){
    var fvNewDealership = (function () {
        var fvNewDealership;
        var _fvNewDealership = function(){
            let form = document.querySelector("#form");
            let modal_id = form.getAttribute('modal-id');
            fvNewDealership = FormValidation.formValidation(form, {
                fields: {
                    name: fv_validator(),
                    location: fv_validator(),
                    'personnel_list[0][name]': fv_validator(),
                    'personnel_list[0][contact_number]': fv_validator(),
                    is_active: fv_validator(),
                    code: {
                        validators: {
                            notEmpty: { message: 'This field is required'
                            },
                            remote: {
                                url: '/services/client_dealership/validate',
                                method: 'POST',
                                message: 'The code is not unique',
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                data: function(){
                                    return {
                                        client_id: param,
                                        id: $('#submit').attr('data-id'),
                                    };
                                }
                            }
                        },
                    },
                    pv_lead_time: {
                        validators: {
                            integer: {
                                message: 'The field must be an number'
                            }}
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
            app.on('click','#cancel',function(e){
                e.preventDefault()
                e.stopImmediatePropagation()
                Alert.confirm('question',"Close this form ?",{
                    onConfirm: () => {
                        modal_state(modal_id);
                        fvNewDealership.resetForm();
                        form.reset();
                        $('#form').attr('action','/services/client_dealership/create');
                        $('#submit').attr('data-id','');
                        $('.modal_title').text('New Dealership');
                    }
                })
            })
            app.on('click','#submit',function(e){
                e.preventDefault()
                e.stopImmediatePropagation()
                let btn_submit = $(this);
                let form_url = form.getAttribute('action');
                fvNewDealership && fvNewDealership.validate().then(function (v) {
                    if(v == "Valid"){
                        Alert.confirm("question","Submit this form?", {
                            onConfirm: function() {
                                btn_submit.attr("data-kt-indicator","on");
                                let formData = new FormData(form);
                                formData.append('repeater',JSON.stringify($('.repeater').repeaterVal()))
                                formData.append('client_id',param);
                                if(btn_submit.attr('data-id').trim() !== '') {
                                    formData.append('id',btn_submit.attr('data-id'))
                                }
                                (new RequestHandler).post(form_url,formData).then((res) => {
                                    Alert.toast(res.status,res.message);
                                    if(res.status == 'success' && form_url!='/services/client_dealership/update'){
                                        form.reset();
                                    }
                                    fvNewDealership.resetForm();
                                })
                                .catch((error) => {
                                    console.log(error)
                                    Alert.alert('error',"Something went wrong. Try again later", false);
                                })
                                .finally(() => {
                                    btn_submit.attr("data-kt-indicator","off");
                                    $("#client_list_table").DataTable().ajax.reload(null, false);
                                });
                            }
                        });
                    }
                })
            })
        }
        var _handleFormRepeater = function(){
            let row = 0;
            $('.repeater').repeater({
                initEmpty: false,
                isFirstItemUndeletable: true,
                // defaultValues: {
                //     'text-input': 'foo'
                // },
                show: function () {
                    row++;
                    fvNewDealership.addField(`personnel_list[${row}][name]`,fv_validator())
                    fvNewDealership.addField(`personnel_list[${row}][contact_number]`,fv_validator())
                    $(this).slideDown();
                },
                hide: function (deleteElement) {
                    fvNewDealership.removeField(`personnel_list[${row}][name]`)
                    fvNewDealership.removeField(`personnel_list[${row}][contact_number]`)
                    row--;
                    $(this).slideUp(deleteElement);
                },
                ready: function (setIndexes) {
                   //code here
                },
            })
        }
    return {
        init: function () {
            _handleFormRepeater()
            _fvNewDealership()

        },
      };
    })();
    KTUtil.onDOMContentLoaded(function () {
        fvNewDealership.init()
    });
}
