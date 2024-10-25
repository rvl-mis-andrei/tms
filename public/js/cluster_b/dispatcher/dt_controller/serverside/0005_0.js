// "use strict";

// import { DataTableHelper } from "../../../../global/datatable.js";
// import {Alert} from "../../../../global/alert.js"
// import {RequestHandler} from "../../../../global/request.js"
// import {modal_state} from "../../../../global.js"
// import {trigger_select} from "../../../../global/select.js"



// export async function TractorTrailerListDT() {

//     let dataTableHelper = new DataTableHelper("tractor_trailer_table","tractor_trailer_wrapper");
//     let page = $('.tractor_trailer_listing');

//     dataTableHelper.initTable(
//         `services/tractor_trailer/datatable`,
//         {
//             status:$('.status').val(),
//         },
//         [
//             {
//                 data: "count",
//                 name: "count",
//                 title: "No.",
//                 responsivePriority: -3,
//             },
//             { data: "tractor", name: "tractor", title: "Tractor",
//                 render: function(data,type,row){
//                     if(data !=null){
//                         return `<div class="d-flex flex-column">
//                             <a href="javascript:;" class="text-gray-800 text-hover-primary mb-1">${data}</a>
//                             <span>${row.tractor_plate_no}</span>
//                         </div>`;
//                     }
//                     return 'No Tractor';
//                 }
//             },
//             { data: "tractor_plate_no", name: "tractor_plate_no", title: "Tractor Plate No",visible:false },
//             {
//                 data: "trailer", name: "trailer", title: "Trailer",
//                 render: function(data,type,row){
//                     if(data !=null){
//                         return `<div class="d-flex flex-column">
//                             <a href="javascript:;" class="text-gray-800 text-hover-primary mb-1">${data}</a>
//                             <span>${row.trailer_type}</span>
//                         </div>`;
//                     }
//                      return 'No Trailer';
//                 }
//             },
//             { data: "trailer_type", name: "trailer_type", title: "Trailer Type",visible:false },
//             { data: "pdriver_emp", name: "pdriver_emp", title: "Driver 1",
//                 render: function(data,type,row){
//                     if(data !=null){ return data; }
//                     return '--';
//                 }
//             },
//             { data: "sdriver_emp", name: "sdriver_emp", title: "Driver 2",
//                 render: function(data,type,row){
//                     if(data !=null){ return data; }
//                     return '--';
//                 }
//             },
//             {
//                 data: "remarks", name: "remarks", title: "Remarks" ,
//                 render: function(data,type,row){
//                     return `<div class="d-flex flex-column">
//                             <a href="javascript:;" class="text-muted text-hover-primary mb-1">${data}</a>
//                         </div>`;
//                 }
//             },
//             {
//                 data: "status",
//                 name: "status",
//                 title: "Status",
//                 render: function (data, type, row) {
//                     var status = {
//                         Active: "success",
//                         Inactive: "danger",
//                     };
//                     return `<span class="badge badge-${status[data]}">${data}</span>`;
//                 },
//             },
//             {
//                 data: "encrypt_id",
//                 name: "encrypt_id",
//                 title: "Action",
//                 className: "text-center",
//                 responsivePriority: -1,
//                 render: function (data, type, row) {
//                     let checked = "";
//                     if (row.is_active == "Active") {
//                         checked = "checked";
//                     }
//                     return `<div class="d-flex justify-content-center flex-shrink-0">
//                             <a class="btn btn-icon btn-icon btn-light-primary btn-sm me-1 hover-elevate-up"
//                             href="/tms/cco-b/dispatcher/tractor_trailer_info/${data}" data-bs-toggle="tooltip" title="View Truck Trailer Details">
//                                 <i class="ki-duotone ki-pencil fs-2x">
//                                     <span class="path1"></span>
//                                     <span class="path2"></span>
//                                     <span class="path3"></span>
//                                     <span class="path4"></span>
//                                 </i>
//                             </a>
//                             <a class="btn btn-icon btn-icon btn-light-danger btn-sm me-1 hover-elevate-up delete" data-id="${data}" url="/services/tractor_trailer/upsert"
//                             id="" data-bs-toggle="tooltip" title="Delete this record">
//                                 <i class="ki-duotone ki-trash fs-2x">
//                                     <span class="path1"></span>
//                                     <span class="path2"></span>
//                                     <span class="path3"></span>
//                                     <span class="path4"></span>
//                                 </i>
//                             </a>
//                         </div>`;
//                 },
//             },
//         ],
//         null,
//         1
//     );

//     page.on('keydown','.search', function (e) {
//         e.stopImmediatePropagation()
//         if (e.key === 'Enter' || e.keyCode === 13) {
//             const searchTerm = $(this).val();
//             dataTableHelper.search(searchTerm)
//         }
//     });

//     page.on('change','.status',function(e){
//         e.preventDefault()
//         e.stopImmediatePropagation()
//         TractorTrailerListDT()
//     })

//     page.on('click','.delete',function(e){
//         e.preventDefault()
//         e.stopImmediatePropagation()
//         let btn_delete = $(this);

//         Alert.confirm('question',"Delete this record ?",{
//             onConfirm: () => {
//                 let formData = new FormData();
//                 let form_url = btn_delete.attr('url');
//                 formData.append('id',btn_delete.attr('data-id'));
//                 formData.append('is_deleted',1);
//                 formData.append('is_active',0);
//                 (new RequestHandler).post(form_url,formData).then((res) => {
//                     Alert.toast(res.status,res.message);
//                 })
//                 .catch((error) => {
//                     console.log(error)
//                     Alert.alert('error',"Something went wrong. Try again later", false);
//                 })
//                 .finally(() => {
//                     $("#tractor_trailer_table").DataTable().ajax.reload(null, false);
//                 });
//             }
//         });

//     })

//     page.on('click','.edit',function(e){
//         e.preventDefault()
//         e.stopImmediatePropagation()
//         let btn_edit = $(this);
//         let formData = new FormData();

//         let btn_data = btn_edit.attr('data-id');
//         let modal_id = $(this).attr('modal-id');
//         let url = btn_edit.attr('url');
//         formData.append('id',btn_data);

//         (new RequestHandler).post(url,formData).then((res) => {
//             let data = JSON.parse(window.atob(res.payload));
//             trigger_select({
//                 'select[name="tractor"]': data.tractor,
//                 'select[name="trailer"]': data.trailer,
//                 'select[name="pdriver"]': data.pdriver,
//                 'select[name="sdriver"]': data.sdriver,
//             })
//             $('textarea[name="remarks"]').val(data.remarks);
//             $('select[name="is_active"]').val(data.status).trigger('change');
//             $('.modal_title').text('Edit Tractor Trailer Details');
//             // $('#form').attr('action','/services/client/upsert');
//             $('.submit').attr('data-id',btn_data);
//         })
//         .catch((error) => {
//             console.log(error)
//             Alert.alert('error',"Something went wrong. Try again later", false);
//         })
//         .finally(() => {
//             modal_state(modal_id,'show');
//         });
//     })

// }

"use strict";

import { DataTableHelper } from "../../../../global/datatable.js";
import {Alert} from "../../../../global/alert.js"
import {RequestHandler} from "../../../../global/request.js"
import {modal_state,createBlockUI,data_bs_components} from "../../../../global.js"
import { fvTractorTrailer } from "../../fv_controller/0002_1.js";
import {trigger_select} from "../../../../global/select.js"



export var TractorTrailerDT = function () {

    const _page = $('.page-tractor-trailer');
    const _tab = $('.tractor_trailer');
    const _table = 'tractor_trailer';
    const _request = new RequestHandler;
    const dataTableHelper = new DataTableHelper(`${_table}_table`,`${_table}_wrapper`);

    const attendance = {
        present: {
            css: 'success',
            action: 'absent',
        },
        absent: {
            css: 'danger',
            action: 'present',
        }
    };

    const _urls ={
        update_driver_attendance : '/tms/cco-b/dispatcher/haulage_attendance/update_driver_attendance',
        update_remarks    : '/services/tractor_trailer/update_remarks',
        update_tractor_trailer_status    : '/services/tractor_trailer/update_status',
        update_tractor_trailer    : '/services/tractor_trailer/update_tractor_trailer',
        create_tractor_trailer    : '/services/tractor_trailer/create_tractor_trailer',
        tractor_trailer_info    : '/services/tractor_trailer/info',
        delete_tractor_trailer    : '/tms/cco-b/dispatcher/haulage_attendance/delete_tractor_trailer',
    }

    function initTable(){
        dataTableHelper.initTable(
            'services/tractor_trailer/datatable',
            {
                filter_status:$('select[name="filter_tractor_trailer_status"]').val(),
            },
            [
                {
                    data: "count",
                    name: "count",
                    title: "No.",
                    responsivePriority: -3,
                    searchable:false,
                },
                {
                    data: "tractor_name", name: "tractor_name", title: "Tractor",
                    render: function (data, type, row) {
                        if (!data || data.length === 0) {
                            return `<span class="text-muted">No Tractor</span>`;
                        } else {
                            // Return the formatted HTML if there is a tractor name
                            return `
                                <div class="d-flex flex-column">
                                    <a href="javascript:;" class="text-gray-800 text-hover-primary mb-1">${data}</a>
                                    <span class="text-muted">${row.tractor_plate_no ?? '--'}</span>
                                </div>
                            `;
                        }
                    },
                },
                {
                    data: "tractor_plate_no", name: "tractor_plate_no", title: "Tractor Plate No.",
                    className:'',
                    sortable:false,
                    visible:false,
                },

                {
                    data: "trailer_name", name: "trailer_name", title: "Trailer",
                    render: function (data, type, row) {
                        if (!data || data.length === 0) {
                            return `<span class="text-muted">No Trailer</span>`;
                        } else {
                            return `
                                <div class="d-flex flex-column">
                                    <a href="javascript:;" class="text-gray-800 text-hover-primary mb-1">${data}</a>
                                    <span class="text-muted">${row.trailer_type ?? '--'}</span>
                                </div>
                            `;
                        }
                    },
                },
                {
                    data: "trailer_type", name: "trailer_type", title: "Trailer Type",
                    className:'',
                    sortable:false,
                    visible:false,
                },

                {
                    data: "pdriver_name", name: "pdriver_name", title: "Driver 1",
                    sortable:false,
                    render: function (data, type, row) {
                        if (!data || data.length === 0) {
                            return `<span class="text-muted">No Driver 1</span>`;
                        } else {
                            return `
                                <div class="d-flex flex-column">
                                    <a href="javscript:;" class="text-gray-800 text-hover-primary mb-1">${data}</a>
                                    <span class="text-muted">Driver 1</span>
                                </div>
                            `;
                        }
                    },
                },

                {
                    data: "sdriver_name", name: "sdriver_name", title: "Driver 2",
                    sortable:false,
                    render: function (data, type, row) {
                        if (!data || data.length === 0) {
                            return `<span class="text-muted">No Driver 2</span>`;
                        } else {
                            return `
                                <div class="d-flex flex-column">
                                    <a href="javscript:;" class="text-gray-800 text-hover-primary mb-1">${data}</a>
                                    <span class="text-muted">Driver 2</span>
                                </div>
                            `;
                        }
                    },
                },

                {
                    data: "tractor_trailer_status", name: "tractor_trailer_status", title: "Tractor/Trailer Status",
                    sortable:false,
                    searchable:false,
                    render: function (data, type, row) {
                        return `<select class="form-select form-select-sm update-status"
                        data-control="select2" data-id=${row.encrypted_id} data-placeholder="Select an option"
                        data-minimum-results-for-search="Infinity" data-allow-clear="true" data-previous="${data}">
                                <option ${data==null?'selected':''}></option>
                                <option value="1" ${data==1?'selected':''}>Available</option>
                                <option value="2" ${data==2?'selected':''}>On Trip</option>
                                <option value="3" ${data==3?'selected':''}>Absent Driver</option>
                                <option value="4" ${data==4?'selected':''}>No Driver</option>
                                <option value="5" ${data==5?'selected':''}>For PMS</option>
                                <option value="6" ${data==6?'selected':''}>On-Going PMS</option>
                                <option value="7" ${data==7?'selected':''}>Trailer Repair</option>
                                <option value="8" ${data==8?'selected':''}>Tractor Repair</option>
                                <option value="9" ${data==9?'selected':''}>Rehab/Recon</option>
                                <option value="10" ${data==10?'selected':''}>Others</option>
                            </select>
                        `;
                    },
                },

                {
                    data: "remarks", name: "remarks", title: "Remarks",
                    sortable:false,
                    searchable:false,
                    render: function (data, type, row) {
                        return `<input type="text" class="form-control form-control-sm update-remarks"
                                    name="unit_remarks" value="${data}" data-id="${row.encrypted_id}">`;
                    },
                },
                {
                    data: "last_updated_by", name: "last_updated_by", title: "Last Updated By",
                    sortable:false,
                    searchable:false,
                    className:'',
                    render(data,type,row)
                    {
                        if(!data){
                            return '--';
                        }
                        return `<div class="d-flex align-items-center">
                                <div class="symbol symbol-circle symbol-50px overflow-hidden me-3">
                                    <span>
                                        <div class="symbol-label fs-3 bg-light-info text-info">
                                            ${data[0]}
                                        </div>
                                    </span>
                                </div>
                                <div class="d-flex flex-column">
                                    <span class="text-gray-800 text-hover-primary mb-1">
                                        ${data}
                                    </span>
                                    <span class="text-muted fs-7">User</span>
                                </div>
                            </div>
                        `
                    }
                },

                {
                    data: "encrypted_id",
                    name: "encrypted_id",
                    title: "Action",
                    sortable:false,
                    searchable:false,
                    className: "text-center",
                    responsivePriority: -1,
                    render: function (data, type, row) {
                        return `<div class="d-flex justify-content-center flex-shrink-0">
                            <a href="#" class="btn btn-icon btn-light-primary btn-sm me-1 hover-elevate-up"
                            data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end" data-bs-toggle="tooltip" title="More Actions">
                                <i class="ki-duotone ki-pencil fs-2x">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                    <span class="path4"></span>
                                </i>
                            </a>
                            <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-7 w-200px py-4" data-kt-menu="true">
                                <div class="menu-item px-3 text-start">
                                    <div class="menu-content text-muted pb-2 px-3 fs-7 text-uppercase">
                                        More Actions
                                    </div>
                                </div>
                                <div class="menu-item px-3">
                                    <a href="javascript:;" data-id="${data}" class="menu-link px-3 details">
                                        View Details
                                    </a>
                                </div>
                            </div>

                            <a href="javascript:;" class="btn btn-icon btn-icon btn-light-danger btn-sm me-1 hover-elevate-up delete" data-id="${data}"
                             data-bs-toggle="tooltip" title="Delete this record">
                                <i class="ki-duotone ki-trash fs-2x">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                    <span class="path4"></span>
                                </i>
                            </a>
                        </div>`;
                    },
                },
            ],
            null,
        );

        $(`#${_table}_table`).ready(function() {

            _tab.on('change','select.filter_table',function(e){
                e.preventDefault()
                e.stopImmediatePropagation()
                initTable();
            })

            _tab.on('keyup','.search',function(e){
                e.preventDefault()
                e.stopImmediatePropagation()
                
                let searchTerm = $(this).val();
                if (e.key === 'Enter' || e.keyCode === 13) {
                    dataTableHelper.search(searchTerm);
                } else if (e.keyCode === 8 || e.key === 'Backspace') {
                    setTimeout(() => {
                        let updatedSearchTerm = $(this).val();
                        if (updatedSearchTerm === '') {
                            dataTableHelper.search('');
                        }
                    }, 0);
                }
            })

            $(`#${_table}_table`).on('keyup', '.update-remarks', function(e) {
                e.preventDefault();
                e.stopImmediatePropagation();

                let _this = $(this);
                let id    = _this.attr('data-id');
                let formData = new FormData;

                if (e.key === 'Enter' || e.keyCode === 13) {
                    formData.append('remarks',_this.val());
                    formData.append('id',id);
                    _request.post(_urls['update_remarks'],formData)
                    .then((res) => {
                        Alert.toast(res.status,res.message);
                        _this.blur();
                    })
                    .catch((error) => {
                        Alert.alert('error', "Something went wrong. Try again later", false);
                    })
                    .finally((error) => {
                    });
                }

            })

            $(`#${_table}_table`).on('change','.update-status', function(e) {
                e.preventDefault();
                e.stopImmediatePropagation();

                let _this = $(this);
                let id    = _this.attr('data-id');
                let formData = new FormData;

                _this.attr('disabled',true);

                formData.append('id',id);
                formData.append('status',_this.val());
                _request.post(_urls['update_tractor_trailer_status'],formData)
                .then((res) => {
                    Alert.toast(res.status,res.message);
                    if(res.status == 'error'){
                        _this.val(_this.attr('data-previous')).select2();
                    }else{
                        _this.attr('data-previous',_this.val());
                    }
                })
                .catch((error) => {
                    Alert.alert('error', "Something went wrong. Try again later", false);
                })
                .finally((error) => {
                    _this.attr('disabled',false);
                });

            })

            $(`#${_table}_table`).on('click','.delete',function(e){
                e.preventDefault()
                e.stopImmediatePropagation()

                let _this = $(this);
                let url   =_this.attr('rq-url');
                let id    =_this.attr('data-id');

                Alert.confirm('question','Delete this record ?',{
                    onConfirm: function() {
                        let formData = new FormData;
                        formData.append('haulage_id',param);
                        formData.append('attendance_id',id);
                        _request.post(_urls['delete_tractor_trailer'],formData)
                        .then((res) => {
                            Alert.toast(res.status,res.message);
                            if(res.status =='success'){
                                $(`#${_table}_table`).DataTable().ajax.reload(null, false);
                            }
                        })
                        .catch((error) => {
                            Alert.alert('error', "Something went wrong. Try again later", false);
                        })
                        .finally((error) => {

                        });
                    }
                });


            })

            $(`#${_table}_table`).on('click','.view',function(e){
                e.preventDefault()
                e.stopImmediatePropagation()

                let _this = $(this);
                let url   =_this.attr('rq-url');
                let id    =_this.attr('data-id');
                let formData = new FormData;

                formData.append('id',id);
                _request.post(url,formData)
                .then((res) => {

                })
                .catch((error) => {
                    Alert.alert('error', "Something went wrong. Try again later", false);
                })
                .finally((error) => {

                });

            })

            $(`#${_table}_table`).on('click','.update-attendance',function(e){
                e.preventDefault()
                e.stopImmediatePropagation()

                let _this = $(this);
                _this.attr('disabled',true);

                let id    =_this.attr('data-id');
                let column = _this.attr('data-column');
                let data_att = _this.attr('data-att');

                let formData = new FormData;
                formData.append('id',id);
                formData.append('column',column);
                formData.append('attendance',data_att);
                formData.append('haulage_id',param);

                _request.post(_urls['update_driver_attendance'],formData)
                .then((res) => {
                    Alert.toast(res.status,res.message);
                    if(res.status =='success'){
                        _this.removeClass('btn-light-success btn-light-danger');
                        _this.addClass('btn-light-'+attendance[data_att].css);

                        _this.find(`i.${attendance[data_att].action}`).addClass('d-none');
                        _this.find('i.'+data_att).removeClass('d-none');
                        _this.find('.indicator-label').text(data_att);

                        _this.attr('data-att',attendance[data_att].action);
                        _this.blur();
                    }
                })
                .catch((error) => {
                    Alert.alert('error', "Something went wrong. Try again later", false);
                })
                .finally((error) => {
                    _this.attr('disabled',false);
                });

            })

            $(`#${_table}_table`).on('click','.details',function(e){
                e.preventDefault();
                e.stopImmediatePropagation();

                let _this = $(this);
                _this.attr('disabled',true);

                let formData = new FormData;
                formData.append('id',_this.attr('data-id'));

                _request.post(_urls['tractor_trailer_info'],formData)
                .then((res) => {
                    if(res.status =='success'){
                        let payload = JSON.parse(window.atob(res.payload));
                        $('select[name="tractor"]').empty().append(payload.tractor_option);
                        $('select[name="trailer"]').empty().append(payload.trailer_option);
                        $('select[name="pdriver"]').empty().append(payload.pdriver_option);
                        $('select[name="sdriver"]').empty().append(payload.sdriver_option);
                        $('textarea[name="remarks"]').parent().addClass('d-none');
                        $('select[name="is_active"]').parent().addClass('d-none');
                    }
                })
                .catch((error) => {
                    Alert.alert('error', "Something went wrong. Try again later", false);
                })
                .finally((error) => {
                    modal_state('#modal_add_tractor_trailer','show');
                    $('#modal_add_tractor_trailer').find('form').attr('action',_urls['update_tractor_trailer']);
                    $('#modal_add_tractor_trailer').find('.modal_title').text('Update Tractor Trailer');
                    $('#modal_add_tractor_trailer').find('.submit').attr('data-id',_this.attr('data-id'))
                    _this.attr('disabled',false);
                });

            })

        })
    }

    return {
        init: function () {
            initTable();
        }
    }

}


export var TractorDT = function (param) {

    const _page = $('.page-tractor-trailer');
    const _table = 'tractor';
    const _tab = $(`.${_table}`);
    const _request = new RequestHandler;
    const dataTableHelper = new DataTableHelper(`${_table}_table`,`${_table}_wrapper`);


    const _urls ={
        update_driver_attendance : '/tms/cco-b/dispatcher/haulage_attendance/update_driver_attendance',
        update_remarks    : '/services/tractor_trailer/update_remarks',
        update_tractor_trailer_status    : '/services/tractor_trailer/update_status',
        update_tractor_trailer    : '/services/tractor_trailer/update_tractor_trailer',
        create_tractor_trailer    : '/services/tractor_trailer/create_tractor_trailer',
        tractor_trailer_info    : '/services/tractor_trailer/info',
        delete_tractor_trailer    : '/tms/cco-b/dispatcher/haulage_attendance/delete_tractor_trailer',
    }

    function initTable(){

        dataTableHelper.initTable(
            'services/tractor/datatable',
            {
                filter_status:$('select[name="filter_tractor_status"]').val(),
            },
            [
                {
                    data: "count",
                    name: "count",
                    title: "No.",
                    responsivePriority: -3,
                    searchable:false,
                },
                {
                    data: "tractor_name", name: "tractor_name", title: "Tractor",
                    render: function (data, type, row) {
                        if (!data || data.length === 0) {
                            return `<span class="text-muted">No Tractor</span>`;
                        } else {
                            // Return the formatted HTML if there is a tractor name
                            return `
                                <div class="d-flex flex-column">
                                    <a href="javascript:;" class="text-gray-800 text-hover-primary mb-1">${data}</a>
                                    <span class="text-muted">${row.tractor_plate_no ?? '--'}</span>
                                </div>
                            `;
                        }
                    },
                },
                {
                    data: "tractor_plate_no", name: "tractor_plate_no", title: "Tractor Plate No.",
                    className:'',
                    sortable:false,
                    visible:false,
                },
                {
                    data: "tractor_status", name: "tractor_status", title: "Tractor Status",
                    sortable:false,
                    searchable:false,
                    render: function (data, type, row) {
                        let status = {
                            1: ["success", "Available"],
                            2: ["info", "Assigned"],
                        };
                        return `<span class="badge badge-${status[data][0]}">${status[data][1]}</span>`;
                    },
                },
                {
                    data: "remarks", name: "remarks", title: "Remarks",
                    sortable:false,
                    searchable:false,
                },
                {
                    data: "last_updated_by", name: "last_updated_by", title: "Last Updated By",
                    sortable:false,
                    searchable:false,
                    className:'',
                    render(data,type,row)
                    {
                        if(!data){
                            return '--';
                        }
                        return `<div class="d-flex align-items-center">
                                <div class="symbol symbol-circle symbol-50px overflow-hidden me-3">
                                    <span>
                                        <div class="symbol-label fs-3 bg-light-info text-info">
                                            ${data[0]}
                                        </div>
                                    </span>
                                </div>
                                <div class="d-flex flex-column">
                                    <span class="text-gray-800 text-hover-primary mb-1">
                                        ${data}
                                    </span>
                                    <span class="text-muted fs-7">User</span>
                                </div>
                            </div>
                        `
                    }
                },
                {
                    data: "encrypted_id",
                    name: "encrypted_id",
                    title: "Action",
                    sortable:false,
                    className: "text-center",
                    responsivePriority: -1,
                    render: function (data, type, row) {
                        return `<div class="d-flex justify-content-center flex-shrink-0">
                            <a href="#" class="btn btn-icon btn-light-primary btn-sm me-1 hover-elevate-up"
                            data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end" data-bs-toggle="tooltip" title="More Actions">
                                <i class="ki-duotone ki-pencil fs-2x">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                    <span class="path4"></span>
                                </i>
                            </a>
                            <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-7 w-150px py-4" data-kt-menu="true">
                                <div class="menu-item px-3 text-start">
                                    <div class="menu-content text-muted pb-2 px-3 fs-7 text-uppercase">
                                        More Actions
                                    </div>
                                </div>
                                <div class="menu-item px-3">
                                    <a href="javascript:;" data-id="${data}" class="menu-link px-3 view-details">
                                        View Details
                                    </a>
                                </div>
                            </div>

                            <a href="javascript:;" class="btn btn-icon btn-icon btn-light-danger btn-sm me-1 hover-elevate-up delete" data-id="${data}"
                             data-bs-toggle="tooltip" title="Delete this record">
                                <i class="ki-duotone ki-trash fs-2x">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                    <span class="path4"></span>
                                </i>
                            </a>
                        </div>`;
                    },
                },
            ],
            null,
        );

        $(`#${_table}_table`).ready(function() {

            _tab.on('change','select.filter_table',function(e){
                e.preventDefault()
                e.stopImmediatePropagation()
                initTable();
            })

            _tab.on('keyup','.search',function(e){
                e.preventDefault()
                e.stopImmediatePropagation()
                let searchTerm = $(this).val();
                if (e.key === 'Enter' || e.keyCode === 13) {
                    dataTableHelper.search(searchTerm);
                } else if (e.keyCode === 8 || e.key === 'Backspace') {
                    setTimeout(() => {
                        let updatedSearchTerm = $(this).val();
                        if (updatedSearchTerm === '') {
                            dataTableHelper.search('');
                        }
                    }, 0);
                }
            })

            $(`#${_table}_table`).on('click','.view-details',function(e){
                e.preventDefault()
                e.stopImmediatePropagation()

                let _this = $(this);
                let url   =_this.attr('rq-url');
                let id    =_this.attr('data-id');
                let modal_id = '#modal_add_tractor';
                let form = $('#form_add_tractor');

                let formData = new FormData;

                formData.append('id',id);
                _request.post('/services/tractor/info',formData)
                .then((res) => {
                    let payload = JSON.parse(window.atob(res.payload));
                    form.find('textarea[name="remarks"]').val(payload.remarks);
                    form.find('input[name="plate_no"]').val(payload.plate_no).attr('data-id',id);
                    form.find('input[name="body_no"]').val(payload.body_no).attr('data-id',id);

                    form.find('select[name="status"]').val(payload.status).trigger('change');
                    form.find('select[name="status"]').parent().removeClass('d-none');

                    $(modal_id).find('button.submit').attr('data-id',id);
                })
                .catch((error) => {
                    console.log(error);
                    Alert.alert('error', "Something went wrong. Try again later", false);
                })
                .finally((error) => {
                    modal_state(modal_id,'show');
                });

            })

            $(`#${_table}_table`).on('click','.delete',function(e){
                e.preventDefault()
                e.stopImmediatePropagation()

                let _this = $(this);
                let id    =_this.attr('data-id');
                let formData = new FormData;

                Alert.confirm('question','Delete this record ?',{
                    onConfirm: function() {
                        formData.append('id',id);
                        _request.post('/services/tractor/delete',formData)
                        .then((res) => {
                            Alert.toast(res.status,res.message);
                            $(`#${_table}_table`).DataTable().ajax.reload(null, false);
                        })
                        .catch((error) => {
                            Alert.alert('error', "Something went wrong. Try again later", false);
                        })
                        .finally((error) => {

                        });
                    }
                });


            })

        })

    }

    return {
        init: function () {
            initTable();
        }
    }

}


export var TrailerDT = function (param) {

    const _page = $('.page-tractor-trailer');
    const _table = 'trailer';
    const _tab = $(`.${_table}`);
    const _request = new RequestHandler;
    const dataTableHelper = new DataTableHelper(`${_table}_table`,`${_table}_wrapper`);


    const _urls ={
        update_driver_attendance : '/tms/cco-b/dispatcher/haulage_attendance/update_driver_attendance',
        update_remarks    : '/services/tractor_trailer/update_remarks',
        update_tractor_trailer_status    : '/services/tractor_trailer/update_status',
        update_tractor_trailer    : '/services/tractor_trailer/update_tractor_trailer',
        create_tractor_trailer    : '/services/tractor_trailer/create_tractor_trailer',
        tractor_trailer_info    : '/services/tractor_trailer/info',
        delete_tractor_trailer    : '/tms/cco-b/dispatcher/haulage_attendance/delete_tractor_trailer',
    }

    function initTable(){

        dataTableHelper.initTable(
            'services/trailer/datatable',
            {
                filter_status:$('select[name="filter_trailer_status"]').val(),
            },
            [
                {
                    data: "count",
                    name: "count",
                    title: "No.",
                    responsivePriority: -3,
                    searchable:false,
                },
                {
                    data: "trailer_name", name: "trailer_name", title: "Trailer",
                    render: function (data, type, row) {
                        if (!data || data.length === 0) {
                            return `<span class="text-muted">No Plate Number</span>`;
                        } else {
                            // Return the formatted HTML if there is a tractor name
                            return `
                                <div class="d-flex flex-column">
                                    <a href="javascript:;" class="text-gray-800 text-hover-primary mb-1">${row.trailer_plate_no}</a>
                                    <span class="text-muted fs-7"><em>Plate No</em></span>
                                </div>
                            `;
                        }
                    },
                },
                {
                    data: "trailer_types", name: "trailer_types", title: "Trailer Type",
                    className:'',
                    sortable:false,
                },
                {
                    data: "trailer_plate_no", name: "trailer_plate_no", title: "Trailer Plate No.",
                    className:'',
                    sortable:false,
                    visible:false,
                },
                {
                    data: "remarks", name: "remarks", title: "Remarks",
                    sortable:false,
                    searchable:false,
                },
                {
                    data: "trailer_status", name: "trailer_status", title: "Trailer Status",
                    sortable:false,
                    searchable:false,
                    render: function (data, type, row) {
                        let status = {
                            1: ["success", "Available"],
                            2: ["info", "Assigned"],
                        };
                        return `<span class="badge badge-${status[data][0]}">${status[data][1]}</span>`;
                    },
                },
                {
                    data: "last_updated_by", name: "last_updated_by", title: "Last Updated By",
                    sortable:false,
                    searchable:false,
                    className:'',
                    render(data,type,row)
                    {
                        if(!data){
                            return '--';
                        }
                        return `<div class="d-flex align-items-center">
                                <div class="symbol symbol-circle symbol-50px overflow-hidden me-3">
                                    <span>
                                        <div class="symbol-label fs-3 bg-light-info text-info">
                                            ${data[0]}
                                        </div>
                                    </span>
                                </div>
                                <div class="d-flex flex-column">
                                    <span class="text-gray-800 text-hover-primary mb-1">
                                        ${data}
                                    </span>
                                    <span class="text-muted fs-7">User</span>
                                </div>
                            </div>
                        `
                    }
                },
                {
                    data: "encrypted_id",
                    name: "encrypted_id",
                    title: "Action",
                    sortable:false,
                    className: "text-center",
                    responsivePriority: -1,
                    render: function (data, type, row) {
                        return `<div class="d-flex justify-content-center flex-shrink-0">
                            <a href="#" class="btn btn-icon btn-light-primary btn-sm me-1 hover-elevate-up"
                            data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end" data-bs-toggle="tooltip" title="More Actions">
                                <i class="ki-duotone ki-pencil fs-2x">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                    <span class="path4"></span>
                                </i>
                            </a>
                            <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-7 w-150px py-4" data-kt-menu="true">
                                <div class="menu-item px-3 text-start">
                                    <div class="menu-content text-muted pb-2 px-3 fs-7 text-uppercase">
                                        More Actions
                                    </div>
                                </div>
                                <div class="menu-item px-3">
                                    <a href="javascript:;" data-id="${data}" class="menu-link px-3 view-details">
                                        View Details
                                    </a>
                                </div>
                            </div>

                            <a href="javascript:;" class="btn btn-icon btn-icon btn-light-danger btn-sm me-1 hover-elevate-up delete" data-id="${data}"
                             data-bs-toggle="tooltip" title="Delete this record">
                                <i class="ki-duotone ki-trash fs-2x">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                    <span class="path4"></span>
                                </i>
                            </a>
                        </div>`;
                    },
                },
            ],
            null,
        );

        $(`#${_table}_table`).ready(function() {

            _tab.on('change','select.filter_table',function(e){
                e.preventDefault()
                e.stopImmediatePropagation()
                initTable();
            })

            _tab.on('keyup','.search',function(e){
                e.preventDefault()
                e.stopImmediatePropagation()

                let searchTerm = $(this).val();
                if (e.key === 'Enter' || e.keyCode === 13) {
                    dataTableHelper.search(searchTerm);
                } else if (e.keyCode === 8 || e.key === 'Backspace') {
                    setTimeout(() => {
                        let updatedSearchTerm = $(this).val();
                        if (updatedSearchTerm === '') {
                            dataTableHelper.search('');
                        }
                    }, 0);
                }
            })

            $(`#${_table}_table`).on('click','.view-details',function(e){
                e.preventDefault()
                e.stopImmediatePropagation()

                let _this = $(this);
                let url   =_this.attr('rq-url');
                let id    =_this.attr('data-id');
                let modal_id = '#modal_add_trailer';
                let form = $('#form_add_trailer');

                let formData = new FormData;

                formData.append('id',id);
                _request.post('/services/trailer/info',formData)
                .then((res) => {
                    let payload = JSON.parse(window.atob(res.payload));
                    form.find('textarea[name="remarks"]').val(payload.remarks);
                    form.find('input[name="plate_no"]').val(payload.plate_no).attr('data-id',id);
                    form.find('select[name="status"]').val(payload.status).trigger('change');
                    form.find('select[name="status"]').parent().removeClass('d-none');
                    let trailerType = form.find('select[name="trailer_type"]');
                    trigger_select(trailerType,payload.trailer_type);
                    $(modal_id).find('button.submit').attr('data-id',id);
                })
                .catch((error) => {
                    console.log(error);
                    Alert.alert('error', "Something went wrong. Try again later", false);
                })
                .finally((error) => {
                    modal_state(modal_id,'show');
                });

            })

            $(`#${_table}_table`).on('click','.delete',function(e){
                e.preventDefault()
                e.stopImmediatePropagation()

                let _this = $(this);
                let id    =_this.attr('data-id');
                let formData = new FormData;

                Alert.confirm('question','Delete this record ?',{
                    onConfirm: function() {
                        formData.append('id',id);
                        _request.post('/services/trailer/delete',formData)
                        .then((res) => {
                            Alert.toast(res.status,res.message);
                            $(`#${_table}_table`).DataTable().ajax.reload(null, false);
                        })
                        .catch((error) => {
                            Alert.alert('error', "Something went wrong. Try again later", false);
                        })
                        .finally((error) => {

                        });
                    }
                });


            })

        })

    }

    return {
        init: function () {
            initTable();
        }
    }

}
