"use strict";
import {Alert} from "../../../global/alert.js"
import {RequestHandler} from "../../../global/request.js"
import {modal_state} from "../../../global.js"

export function fvNewTractorTrailer(){

    var init_fvNewTractorTrailer = (function () {

        var _handlefvNewTractorTrailer = function(){
            let form = document.querySelector("#form");
            let modal_id = form.getAttribute('modal-id');
            let fvNewTractorTrailer = FormValidation.formValidation(form, {
                fields: {
                    tractor: {validators: { notEmpty: { message: 'This field is required' } }},
                    trailer: {validators: { notEmpty: { message: 'This field is required' } }},
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

            app.on('click','#cancel',function(e){
                e.preventDefault()
                e.stopImmediatePropagation()
                Alert.confirm('question',"Close this form ?",{
                    onConfirm: () => {
                        modal_state(modal_id);
                        fvNewTractorTrailer.resetForm();
                        form.reset();
                        $('#form').attr('action','/services/tractor_trailer/create');
                        $('#submit').attr('data-id','');
                        $('.modal_title').text('New Tractor Trailer');
                    }
                })
            })

            app.on('click','#submit',function(e){
                e.preventDefault()
                e.stopImmediatePropagation()
                let btn_submit = $(this);
                let form_url = form.getAttribute('action');
                fvNewTractorTrailer && fvNewTractorTrailer.validate().then(function (v) {
                    if(v == "Valid"){
                        Alert.confirm("question","Submit this form?", {
                            onConfirm: function() {
                                btn_submit.attr("data-kt-indicator","on");
                                let formData = new FormData(form);
                                if(btn_submit.attr('data-id').trim() !== '') {
                                    formData.append('id',btn_submit.attr('data-id'))
                                }
                                (new RequestHandler).post(form_url,formData).then((res) => {
                                    Alert.toast(res.status,res.message);
                                    if(res.status == 'success' && form_url!='/services/tractor_trailer/update'){
                                        form.reset();
                                    }
                                    fvNewTractorTrailer.resetForm();
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
