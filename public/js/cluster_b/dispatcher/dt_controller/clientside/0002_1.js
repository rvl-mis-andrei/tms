"use strict";

import { DataTableHelper } from "../../../../global/datatable.js";
import {Alert} from "../../../../global/alert.js"
import {RequestHandler} from "../../../../global/request.js"
import {modal_state,createBlockUI,data_bs_components} from "../../../../global.js"
import { fvTractorTrailer } from "../../fv_controller/0002_1.js";



export var TractorTrailerDT = function (param) {

    const _page = $('.haulage_info_page');
    const _table = 'tractor_trailer_driver';
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
        update_remarks    : '/tms/cco-b/dispatcher/haulage_attendance/update_tractor_trailer_remarks',
        update_tractor_trailer_status    : '/tms/cco-b/dispatcher/haulage_attendance/update_tractor_trailer_status',
        update_tractor_trailer    : '/tms/cco-b/dispatcher/haulage_attendance/update_tractor_trailer',
        tractor_trailer_info    : '/tms/cco-b/dispatcher/haulage_attendance/tractor_trailer_info',
        delete_tractor_trailer    : '/tms/cco-b/dispatcher/haulage_attendance/delete_tractor_trailer',
    }

    function initTable(){
        dataTableHelper.initTable(
            $(`#${_table}_wrapper`).attr('data-url'),
            {
                id:param,
                filter_status:$('select[name="filter_status"]').val(),
                filter_attendance:$('select[name="filter_attendance"]').val(),
            },
            [
                {
                    data: "count",
                    name: "count",
                    title: "No.",
                    responsivePriority: -3,
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
                    data: "url", name: "url", title: "URL",
                    className:'',
                    sortable:false,
                    visible:false,
                    searchable:false,
                },

                {
                    data: "is_started", name: "is_started", title: "Is Started ?",
                    className:'',
                    sortable:false,
                    visible:false,
                    searchable:false,
                },

                {
                    data: "is_final", name: "is_final", title: "Is Final ?",
                    className:'',
                    sortable:false,
                    visible:false,
                    searchable:false,
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
                    data: "pdriver_att", name: "pdriver_att", title: "Driver 1 Att.",
                    sortable:false,
                    searchable:false,
                    render: function (data, type, row) {
                        return `<button class="btn btn-sm btn-light-${data?`success`:`danger`} btn-flex btn-center ${row.is_started && row.is_final==false ? 'update-attendance':''}"
                        data-column="pdriver" data-att="${data==1?`absent`:`present`}" data-id="${row.encrypted_id}" data-kt-follow-btn="true" ${row.is_started ? '':'disabled'}>
                                    <i class="ki-duotone ki-cross fs-3 absent ${data==0?``:`d-none`}">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                    <i class="ki-duotone ki-check present fs-3 ${data==1?``:`d-none`}"></i>
                                    <span class="indicator-label text-capitalize">${data==1?`Present`:`Absent`}</span>
                                </button>`;
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
                    data: "sdriver_att", name: "sdriver_att", title: "Driver 2 Att.",
                    sortable:false,
                    searchable:false,
                    render: function (data, type, row) {
                        return `<button class="btn btn-sm btn-light-${data?`success`:`danger`} btn-flex btn-center ${row.is_started && row.is_final==false ? 'update-attendance':''}"
                        data-column="sdriver" data-att="${data==1?`absent`:`present`}" data-id="${row.encrypted_id}" data-kt-follow-btn="true" ${row.is_started ? '':'disabled'}>
                                    <i class="ki-duotone ki-cross fs-3 absent ${data==0?``:`d-none`}">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                    <i class="ki-duotone ki-check present fs-3 ${data==1?``:`d-none`}"></i>
                                    <span class="indicator-label text-capitalize">${data==1?`Present`:`Absent`}</span>
                                </button>`;
                    },
                },

                {
                    data: "tractor_trailer_status", name: "tractor_trailer_status", title: "Tractor/Trailer Status",
                    sortable:false,
                    searchable:false,
                    render: function (data, type, row) {
                        return `<select class="form-select form-select-sm ${row.is_started && row.is_final==false ? 'update-status':''}"
                        data-control="select2" data-id=${row.encrypted_id} data-placeholder="Select an option"
                        data-minimum-results-for-search="Infinity" ${row.is_started ? '':'disabled'} data-allow-clear="true" data-previous="${data}" ${row.is_started && row.is_final==false?``:`disabled`}>
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
                        return `<input type="text" class="form-control form-control-sm ${row.is_started && row.is_final==false ? 'update-remarks':''}"
                                    name="unit_remarks" value="${data}" data-id="${row.encrypted_id}" ${row.is_started && row.is_final==false?``:`disabled`}>`;
                    },
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
                            <a href="${row.is_started && row.is_final==false?`#`:`javascript:;`}" class="btn btn-icon btn-light-primary btn-sm me-1 hover-elevate-up"
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
                                    <a href="javascript:;" data-id="${data}" class="menu-link px-3 ${row.is_started && row.is_final==false?`update-tractor-trailer`:``}" data-modal="modal_add_tractor_trailer">
                                        Update Tractor Trailer
                                    </a>
                                </div>
                            </div>

                            <a href="javascript:;" class="btn btn-icon btn-icon btn-light-danger btn-sm me-1 hover-elevate-up ${row.is_started && row.is_final==false?`delete`:``}" data-id="${data}"
                             data-bs-toggle="tooltip" title="Delete this record" ${row.is_started && row.is_final==false?``:`disabled`}>
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

            _page.on('change','select.filter_table',function(e){
                e.preventDefault()
                e.stopImmediatePropagation()
                initTable();
            })

            _page.on('keyup','.search',function(e){
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
                    formData.append('haulage_id',param);
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
                formData.append('haulage_id',param);
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

            $(`#${_table}_table`).on('click','.update-tractor-trailer',function(e){
                e.preventDefault();
                e.stopImmediatePropagation();

                let _this = $(this);
                let modal_id = ('#modal_add_tractor_trailer');
                _this.attr('disabled',true);

                let formData = new FormData;
                formData.append('id',_this.attr('data-id'));
                formData.append('haulage_id',param);

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

                        $('select[name="tractor"] ,select[name="trailer"] ,select[name="pdriver"] ,select[name="sdriver"]').select2({
                            dropdownParent: modal_id
                        });
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



                // Alert.select('info', 'Update Tractor Trailer', {
                //     onConfirm: (selectedValues) => {
                //         console.log('Confirmed selections:', selectedValues);
                //     },
                //     onCancel: () => {
                //         console.log('Selection canceled');
                //     }
                // }, [{name:'tractor'},{name:'trailer'}]);

            })

        })
    }

    return {
        init: function () {
            initTable();
        }
    }

}
