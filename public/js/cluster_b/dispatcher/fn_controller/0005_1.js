'use strict';
// import {TractorTrailerListDT} from '../dt_controller/serverside/0005_0.js';
// import {fvNewTractorTrailer} from '../fv_controller/0005_0.js';
// import {tractor,trailer,cluster_driver} from "../../../global/select.js"
import {Alert} from "../../../global/alert.js"
import {RequestHandler} from "../../../global/request.js"
import {data_bs_components,modal_state,page_state} from "../../../global.js";

export function TractorTrailerInfoController(page,param)
{
    function loadLastTab(){
        let tab = localStorage.getItem('tractor_trailer_tab') || 'tab-content-1';
        $(`a[data-tab='${tab}']`).addClass('active')
        loadTab(tab).then((res)=>{
            data_bs_components()
        })
    }

    function loadTab(tab)
    {
        return new Promise((resolve, reject) => {
            switch (tab) {
                case 'tab-content-1':
                    loadTractorTrailerOverview(tab).then((res)=>{
                        resolve(res)
                    })
                break;

                case 'tab-content-2':
                    loadTractorTrailerLogs(tab).then((res)=>{
                        resolve(res)
                    })
                break;

                default:
                    resolve(false)
                break;
            }
        })
    }

    async function loadTractorTrailerOverview (tab)
    {
        return new Promise((resolve, reject) => {

            $(`.${tab}`).removeClass('d-none').addClass('active show')
            resolve(true)
        })

    }


    async function loadTractorTrailerLogs(tab)
    {
        return new Promise((resolve, reject) => {

            $(`.${tab}`).removeClass('d-none').addClass('active show')
            resolve(true)
        })
    }

    function modalSearchEmpty(){

    }


    $(document).ready(function(e){
        if(document.readyState == 'complete'){

            loadLastTab()

            $('body').delegate('a[data-bs-toggle="tab"]','click',function(e){
                e.preventDefault()
                e.stopImmediatePropagation()

                let tab = $(this).attr('data-tab');
                $(`.tab-pane`).addClass('d-none').removeClass('active show')
                loadTab(tab).then((res)=>{
                    localStorage.setItem("tractor_trailer_tab",tab)
                })


            })

            $('body').delegate('.remove','click',function(e){
                e.preventDefault()
                e.stopImmediatePropagation()

                let action = $(this).attr('data-action');
                let column = $(this).attr('data-column');
                let url = $(this).attr('data-url');

                let formData = new FormData();
                formData.append('id',param)
                formData.append('column',column)
                Alert.confirm('question',action,{
                    onConfirm: () => {
                        (new RequestHandler).post(url,formData).then((res) => {
                            Alert.toast(res.status,res.message);
                            if(res.status == 'success' && res.page){
                                //refresh page
                                $('#Page').empty().html(res.page).promise().done(function(){
                                KTComponents.init()
                                data_bs_components()
                                loadLastTab()
                                });
                            }
                        })
                        .catch((error) => {
                            console.log(error)
                            Alert.alert('error',"Something went wrong. Try again later", false);
                        })
                        .finally(() => {
                            // code here
                        });
                    }
                })
            })

            $('body').delegate('.update_column','click',function(e){
                e.preventDefault()
                e.stopImmediatePropagation()
                let data_search = $(this).attr('data-search')
                let data_url = $(this).attr('data-url')
                let data_column = $(this).attr('data-column')

                let modal_id = $(this).attr('modal-id');

                $('.modal-title').text(data_search)
                $('.search-input').attr('name',data_search)
                $('.search-result').empty()
                $('#modal_search .modal-footer').addClass('d-none')

                $('#modal_search .add').attr('data-url',data_url);
                $('#modal_search .add').attr('data-column',data_column);
                $('#modal_search .add').attr('modal-id',modal_id);

                modal_state(modal_id,'show')
            })

            $('body').delegate('input[name="tractor"]','keyup',function(e){
                e.stopImmediatePropagation()
                let search = $(this).val()
                let formData = new FormData();
                let html ='';

                $(this).siblings('.search-loading').removeClass('d-none')
                formData.append('search',search)
                formData.append('type','search_modal')

                return (new RequestHandler).post("/services/select/tractor",formData)
                .then((res) => {
                    if(res.length > 0)
                    {
                        res.forEach(data => {
                            html += ` <div class="py-5">
                                            <div class="mh-375px me-n7 pe-7">
                                                <div class="rounded d-flex flex-stack bg-active-lighten p-4" data-user-id="0">
                                                    <div class="d-flex align-items-center">
                                                        <label class="form-check form-check-custom form-check-solid me-5">
                                                            <input class="form-check-input checkbox" type="checkbox" name="users" value="${data.id}" data-url="/services/tractor_trailer/update_column" />
                                                        </label>
                                                        <div class="ms-5">
                                                            <a href="#" class="fs-5 fw-bold text-gray-900 text-hover-primary mb-2">
                                                                ${data.description}
                                                            </a>
                                                            <div class="fw-semibold text-muted">${data.plate_no}</div>
                                                        </div>
                                                    </div>
                                                    <div class="text-end">
                                                        <span class="badge badge-light-${data.status[1]} fw-bold px-4 py-3">
                                                            ${data.status[0]}
                                                         </span>
                                                    </div>
                                                </div>
                                                <div class="border-bottom border-gray-300 border-bottom-dashed"></div>
                                            </div>
                                        </div>`;
                        });
                        $('.search-result').empty().append(html)
                        $('#modal_search .modal-footer').removeClass('d-none')
                    }else{
                        html =`<div data-kt-search-element="empty" class="text-center">
                                <div class="fw-semibold py-10">
                                    <div class="text-gray-600 fs-3 mb-2">No Tractor Found</div>
                                    <div class="text-muted fs-6">Try to search again</div>
                                </div>
                                <div class="text-center px-5">
                                    <img src="${asset_url+'/media/illustrations/sketchy-1/1.png'}" alt=""
                                        class="w-100 h-200px h-sm-325px"/>
                                </div>
                            </div>`;
                        $('.search-result').html(html)
                        $('#modal_search .modal-footer').addClass('d-none')

                    }
                })
                .catch((error) => {
                    console.error(error)
                })
                .finally(() => {
                    $(this).siblings('.search-loading').addClass('d-none')
                    $('.search-result').removeClass('d-none')
                });

            });

            $('body').delegate('input[name="trailer"]','keyup',function(e){
                e.stopImmediatePropagation()
                let search = $(this).val()
                let formData = new FormData();
                let html ='';

                $(this).siblings('.search-loading').removeClass('d-none')
                formData.append('search',search)
                formData.append('type','search_modal')

                return (new RequestHandler).post("/services/select/trailer",formData)
                .then((res) => {
                    if(res.length > 0)
                    {
                        res.forEach(data => {
                            html += ` <div class="py-5">
                                            <div class="mh-375px me-n7 pe-7">
                                                <div class="rounded d-flex flex-stack bg-active-lighten p-4" data-user-id="0">
                                                    <div class="d-flex align-items-center">
                                                        <label class="form-check form-check-custom form-check-solid me-5">
                                                            <input class="form-check-input checkbox" type="checkbox" name="users" value="${data.id}" data-url="/services/tractor_trailer/update_column" />
                                                        </label>
                                                        <div class="ms-5">
                                                            <a href="#" class="fs-5 fw-bold text-gray-900 text-hover-primary mb-2">
                                                                ${data.plate_no}
                                                            </a>
                                                            <div class="fw-semibold text-muted">${data.trailer_type}</div>
                                                        </div>
                                                    </div>
                                                    <div class="text-end">
                                                        <span class="badge badge-light-${data.status[1]} fw-bold px-4 py-3">
                                                            ${data.status[0]}
                                                         </span>
                                                    </div>
                                                </div>
                                                <div class="border-bottom border-gray-300 border-bottom-dashed"></div>
                                            </div>
                                        </div>`;
                        });
                        $('.search-result').empty().append(html)
                        $('#modal_search .modal-footer').removeClass('d-none')
                    }else{
                        html =`<div data-kt-search-element="empty" class="text-center">
                                <div class="fw-semibold py-10">
                                    <div class="text-gray-600 fs-3 mb-2">No Tractor Found</div>
                                    <div class="text-muted fs-6">Try to search again</div>
                                </div>
                                <div class="text-center px-5">
                                    <img src="${asset_url+'/media/illustrations/sketchy-1/1.png'}" alt=""
                                        class="w-100 h-200px h-sm-325px"/>
                                </div>
                            </div>`;
                        $('.search-result').html(html)
                        $('#modal_search .modal-footer').addClass('d-none')

                    }
                })
                .catch((error) => {
                    console.error(error)
                })
                .finally(() => {
                    $(this).siblings('.search-loading').addClass('d-none')
                    $('.search-result').removeClass('d-none')
                });

            });

            $('body').delegate('input[name="driver"]','keyup',function(e){
                e.stopImmediatePropagation()
                let search = $(this).val()
                let formData = new FormData();
                let html ='';

                $(this).siblings('.search-loading').removeClass('d-none')
                formData.append('search',search)
                formData.append('type','search_modal')

                return (new RequestHandler).post("/services/select/cluster_drivers",formData)
                .then((res) => {
                    if(res.length > 0)
                    {
                        res.forEach(data => {
                            html += ` <div class="py-5">
                                            <div class="mh-375px me-n7 pe-7">
                                                <div class="rounded d-flex flex-stack bg-active-lighten p-4" data-user-id="0">
                                                    <div class="d-flex align-items-center">
                                                        <label class="form-check form-check-custom form-check-solid me-5">
                                                            <input class="form-check-input checkbox" type="checkbox" name="users" value="${data.id}" data-url="/services/tractor_trailer/update_column" />
                                                        </label>
                                                        <div class="ms-5">
                                                            <a href="#" class="fs-5 fw-bold text-gray-900 text-hover-primary mb-2">
                                                                ${data.name}
                                                            </a>
                                                            <div class="fw-semibold text-muted">${data.emp_no}</div>
                                                        </div>
                                                    </div>
                                                    <div class="text-end">
                                                        <span class="badge badge-light-${data.status[1]} fw-bold px-4 py-3">
                                                            ${data.status[0]}
                                                         </span>
                                                    </div>
                                                </div>
                                                <div class="border-bottom border-gray-300 border-bottom-dashed"></div>
                                            </div>
                                        </div>`;
                        });
                        $('.search-result').empty().append(html)
                        $('#modal_search .modal-footer').removeClass('d-none')
                    }else{
                        html =`<div data-kt-search-element="empty" class="text-center">
                                <div class="fw-semibold py-10">
                                    <div class="text-gray-600 fs-3 mb-2">No Tractor Found</div>
                                    <div class="text-muted fs-6">Try to search again</div>
                                </div>
                                <div class="text-center px-5">
                                    <img src="${asset_url+'/media/illustrations/sketchy-1/1.png'}" alt=""
                                        class="w-100 h-200px h-sm-325px"/>
                                </div>
                            </div>`;
                        $('.search-result').html(html)
                        $('#modal_search .modal-footer').addClass('d-none')

                    }
                })
                .catch((error) => {
                    console.error(error)
                })
                .finally(() => {
                    $(this).siblings('.search-loading').addClass('d-none')
                    $('.search-result').removeClass('d-none')
                });

            });

            $('body').delegate('#modal_search .checkbox','change',function(e){
                e.stopImmediatePropagation()
                let isChecked = this.checked;
                if (isChecked) {
                    $('.checkbox').not(this).attr('disabled', true);
                    $('#modal_search .add').attr('disabled',false);
                } else {
                    $('.checkbox').attr('disabled', false);
                    $('#modal_search .add').attr('disabled',true);
                }
            })

            $('body').delegate('#modal_search .add','click',function(e){
                e.preventDefault()
                e.stopImmediatePropagation()

                let url = $(this).attr('data-url');
                let column = $(this).attr('data-column');
                let column_id = $('.checkbox:checked').val();
                let modal_id = $(this).attr('modal-id');

                let formData = new FormData();
                formData.append('id',param)
                formData.append('column',column)
                formData.append('column_id',column_id)

                Alert.confirm('question','Save Changes ?',{
                    onConfirm: () => {
                        (new RequestHandler).post(url,formData).then((res) => {
                            Alert.toast(res.status,res.message);
                            if(res.status == 'success' && res.page){
                                modal_state(modal_id)
                                $('#Page').empty().html(res.page).promise().done(function(){
                                    KTComponents.init()
                                    data_bs_components()
                                    loadLastTab()
                                });
                            }
                        })
                        .catch((error) => {
                            console.log(error)
                            Alert.alert('error',"Something went wrong. Try again later", false);
                        })
                        .finally(() => {
                            // code here
                        });
                    }
                })
            })

            $('body').delegate('.remarks','click',function(e){
                e.preventDefault()
                e.stopImmediatePropagation()

                // const { value: text } = await Swal.fire({
                //     input: "textarea",
                //     inputLabel: "Message",
                //     inputPlaceholder: "Type your message here...",
                //     inputAttributes: {
                //       "aria-label": "Type your message here"
                //     },
                //     showCancelButton: true
                //   });
                //   if (text) {
                //     Swal.fire(text);
                //   }
            })

        }
    });

}
