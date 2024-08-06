"use strict";
import {Alert} from "../../../global/alert.js"
import {RequestHandler} from "../../../global/request.js"
import {modal_state,fv_validator} from "../../../global.js"

export function fvNewTractorTrailer(){

    var init_fvNewTractorTrailer = (function () {

        var _handlefvNewTractorTrailer = function(){

            let form = document.querySelector("#form");
            let page = $('.tractor_trailer_listing');


            let fvNewTractorTrailer = FormValidation.formValidation(form, {
                fields: {
                    tractor: {
                        validators: {
                            callback: {
                                message: 'Select at least a trailer or tractors',
                                callback: function(){
                                    let tractor = fvNewTractorTrailer.getElements('tractor');
                                    let trailer = fvNewTractorTrailer.getElements('trailer');
                                    if (tractor[0].value !== '' || trailer[0].value !== '') {
                                        fvNewTractorTrailer.updateFieldStatus('tractor', 'Valid', 'callback');
                                        return true;
                                    }
                                    return false;
                                }
                            },
                        }
                    },
                    trailer: {
                        validators: {
                            callback: {
                                message: 'Select at least a trailer or tractor',
                                callback: function(){
                                    let tractor = fvNewTractorTrailer.getElements('tractor');
                                    let trailer = fvNewTractorTrailer.getElements('trailer');
                                    if (tractor[0].value !== '' || trailer[0].value !== '') {
                                        fvNewTractorTrailer.updateFieldStatus('trailer', 'Valid', 'callback');
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
                                    const pdriver = fvNewTractorTrailer.getElements('pdriver');
                                    const sdriver = fvNewTractorTrailer.getElements('sdriver');
                                    if (pdriver[0].value !== '' || sdriver[0].value !== '') {
                                        fvNewTractorTrailer.updateFieldStatus('pdriver', 'Valid', 'callback');
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
                                    const pdriver = fvNewTractorTrailer.getElements('pdriver');
                                    const sdriver = fvNewTractorTrailer.getElements('sdriver');
                                    if (pdriver[0].value !== '' || sdriver[0].value !== '') {
                                        fvNewTractorTrailer.updateFieldStatus('sdriver', 'Valid', 'callback');
                                        return true;
                                    }
                                    return false;
                                }
                            },
                        },
                    },
                    is_active:fv_validator(),
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
                let modal_id = form.getAttribute('modal-id');
                Alert.confirm('question',"Close this form ?",{
                    onConfirm: () => {
                        modal_state(modal_id);
                        fvNewTractorTrailer.resetForm();
                        form.reset();
                        $('.modal-select').val(null).trigger('change');
                        $('.submit').attr('data-id','');
                        $('.modal_title').text('New Tractor Trailer');
                    }
                })
            })

            page.on('click','.submit',function(e){
                e.preventDefault()
                e.stopImmediatePropagation()

                let btn_submit = $(this);

                btn_submit.attr("data-kt-indicator","on");
                btn_submit.attr("disabled",true);

                let form_url = form.getAttribute('action');
                let id = btn_submit.attr('data-id');

                fvNewTractorTrailer && fvNewTractorTrailer.validate().then(function (v) {
                    if(v == "Valid"){
                        Alert.confirm("question","Submit this form?", {
                            onConfirm: function() {
                                let formData = new FormData(form);
                                if(id.length > 0) { formData.append('id',id) }
                                (new RequestHandler).post(form_url,formData).then((res) => {
                                    Alert.toast(res.status,res.message);
                                    if(res.status == 'success' && id.length > 0){ form.reset(); }
                                    fvNewTractorTrailer.resetForm();
                                })
                                .catch((error) => {
                                    console.log(error)
                                    Alert.alert('error',"Something went wrong. Try again later", false);
                                })
                                .finally(() => {
                                    btn_submit.attr("data-kt-indicator","off");
                                    btn_submit.attr("disabled",false);
                                    $("#tractor_trailer_table").DataTable().ajax.reload(null, false);
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
            _handlefvNewTractorTrailer();
        },
      };

    })();

    KTUtil.onDOMContentLoaded(function () {
        init_fvNewTractorTrailer.init();
    });

}
