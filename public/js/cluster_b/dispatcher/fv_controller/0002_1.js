"use strict";
import {Alert} from "../../../global/alert.js"
import {RequestHandler} from "../../../global/request.js"
import {modal_state,fv_validator} from "../../../global.js"

export function fvTractorTrailer(param=false,_table=false){

    var init_fvTractorTrailer = (function () {

        var _handleTractorTrailer = function(){
            let fvTractorTrailer;
            let form = document.querySelector("#form");
            let modal_id = form.getAttribute('modal-id');
            let modalContent = document.querySelector(`${modal_id} .modal-content`);
            let blockUI = new KTBlockUI(modalContent, {
                message: '<div class="blockui-message"><span class="spinner-border text-primary"></span> Loading...</div>',
            });

            if (!form.hasAttribute('data-fv-initialized')) {
                fvTractorTrailer = FormValidation.formValidation(form, {
                    fields: {
                        tractor: {
                            validators: {
                                callback: {
                                    message: 'Select at least a trailer or tractors',
                                    callback: function(){
                                        let tractor = fvTractorTrailer.getElements('tractor');
                                        let trailer = fvTractorTrailer.getElements('trailer');
                                        if (tractor[0].value !== '' || trailer[0].value !== '') {
                                            fvTractorTrailer.updateFieldStatus('tractor', 'Valid', 'callback');
                                            return true;
                                        }
                                        return false;
                                    }
                                },
                                remote: {
                                    url: '/tms/cco-b/dispatcher/haulage_attendance/check_tractor_status',
                                    method: 'POST',
                                    message: 'The name is not unique',
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                    },
                                    data: function(){
                                        return {  haulage_id: param  };
                                    }
                                }
                            }
                        },
                        trailer: {
                            validators: {
                                callback: {
                                    message: 'Select at least a trailer or tractor',
                                    callback: function(){
                                        let tractor = fvTractorTrailer.getElements('tractor');
                                        let trailer = fvTractorTrailer.getElements('trailer');
                                        if (tractor[0].value !== '' || trailer[0].value !== '') {
                                            fvTractorTrailer.updateFieldStatus('trailer', 'Valid', 'callback');
                                            return true;
                                        }
                                        return false;
                                    }
                                },
                            }
                        },
                        pdriver: {
                            validators: {
                                // notEmpty: { message: 'This field is required' },
                                different: {
                                    compare: function () {
                                        return form.querySelector('[name="sdriver"]').value;
                                    },
                                    message: 'The Driver 1 and Driver 2 cannot be the same',
                                },
                                callback: {
                                    message: 'Select at least 1 driver',
                                    callback: function(){
                                        const pdriver = fvTractorTrailer.getElements('pdriver');
                                        const sdriver = fvTractorTrailer.getElements('sdriver');
                                        if (pdriver[0].value !== '' || sdriver[0].value !== '') {
                                            fvTractorTrailer.updateFieldStatus('pdriver', 'Valid', 'callback');
                                            return true;
                                        }
                                        return false;
                                    }
                                },
                            },
                        },
                        sdriver: {
                            validators: {
                                // notEmpty: { message: 'This field is required' },
                                different: {
                                    compare: function () {
                                        return form.querySelector('[name="pdriver"]').value;
                                    },
                                    message: 'The Driver 2 and Driver 1 cannot be the same',
                                },
                                callback: {
                                    message: 'Select at least 1 driver',
                                    callback: function(){
                                        const pdriver = fvTractorTrailer.getElements('pdriver');
                                        const sdriver = fvTractorTrailer.getElements('sdriver');
                                        if (pdriver[0].value !== '' || sdriver[0].value !== '') {
                                            fvTractorTrailer.updateFieldStatus('sdriver', 'Valid', 'callback');
                                            return true;
                                        }
                                        return false;
                                    }
                                },
                            },
                        },
                        // is_active:fv_validator(),
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
                if(param == false){
                    fvTractorTrailer.disableValidator('tractor', 'remote');
                }else{
                    fvTractorTrailer.enableValidator('tractor', 'remote');
                }
                form.setAttribute('data-fv-initialized', 'true');


            }

            $(modal_id).on('click','.cancel',function(e){
                e.preventDefault()
                e.stopImmediatePropagation()
                Alert.confirm('question',"Close this form ?",{
                    onConfirm: () => {
                        modal_state(modal_id);
                        fvTractorTrailer.resetForm();
                        form.reset();
                        form.setAttribute('action','/services/tractor_trailer/upsert');
                        $(modal_id).find('.modal_title').text('New Tractor Trailer');
                        $(modal_id).find('.submit').attr('data-id','');
                        $(modal_id).find('textarea[name="remarks"]').parent().removeClass('d-none');
                        $(modal_id).find('select[name="is_active"]').parent().removeClass('d-none');
                    }
                })
            })

            $(modal_id).on('click','.submit',function(e){
                e.preventDefault()
                e.stopImmediatePropagation()

                let _this = $(this);
                let url = form.getAttribute('action');
                fvTractorTrailer && fvTractorTrailer.validate().then(function (v) {
                    if(v == "Valid"){
                        Alert.confirm("question","Submit this form?", {
                            onConfirm: function() {
                                blockUI.block();
                                _this.attr("data-kt-indicator","on");
                                _this.attr("disabled",true);
                                let formData = new FormData(form);
                                formData.append('id',param);
                                formData.append('attendance_id',_this.attr('data-id'));
                                (new RequestHandler).post(url,formData).then((res) => {
                                    Alert.toast(res.status,res.message);
                                    if(res.status == 'success'){
                                        fvTractorTrailer.resetForm();
                                        // $(".dataTable").DataTable().ajax.reload();
                                        if($(_table).length && _table){
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
                _handleTractorTrailer()
            },
        };

    })();

    KTUtil.onDOMContentLoaded(function () {
        init_fvTractorTrailer.init();
    });

}

