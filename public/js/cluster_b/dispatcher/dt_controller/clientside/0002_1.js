"use strict";

import { DataTableHelper } from "../../../../global/datatable.js";
import {Alert} from "../../../../global/alert.js"
import {RequestHandler} from "../../../../global/request.js"
import {modal_state,createBlockUI} from "../../../../global.js"



export var TractorTrailerDT = function (param) {

    const _table = 'tractor_trailer_driver';
    const _page = $('.haulage_info_page');

    const _request = new RequestHandler;
    const dataTableHelper = new DataTableHelper(`${_table}_table`,`${_table}_wrapper`);

    const attendance = {
        present: {
            css: 'success',
            action: 'absent',
        },
        absent: {
            css: 'warning',
            action: 'present',
        }
    };

    const _urls ={
        update_attendance : '/tms/cco-b/dispatcher/haulage_attendance/update_attendance',
        create_attendance : '/tms/cco-b/dispatcher/haulage_attendance/create_attendance',
    }

    function initTable(){
        dataTableHelper.initTable(
            `tms/cco-b/dispatcher/tractor_trailer_driver/dt`,
            {
                id:param,
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
                    data: "pdriver_name", name: "pdriver_name", title: "Driver 1",
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
                    data: "pdriver_att", name: "pdriver_att", title: "Att.",
                    render: function (data, type, row) {
                        return `<button class="btn btn-sm btn-light-success btn-flex btn-center ${row.is_started ? 'attendance':''}"
                        data-column="pdriver" rq-url="${row.url}" data-att="absent" data-id="${row.encrypted_id}" data-kt-follow-btn="true" ${row.is_started ? '':'disabled'}>
                                    <i class="ki-duotone ki-cross fs-3 absent d-none">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                    <i class="ki-duotone ki-check present fs-3"></i>
                                    <span class="indicator-label text-capitalize">Present</span>
                                </button>`;
                    },
                },

                {
                    data: "sdriver_name", name: "sdriver_name", title: "Driver 2",
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
                    data: "sdriver_att", name: "sdriver_att", title: "Att.",
                    render: function (data, type, row) {
                        return `<button class="btn btn-sm btn-light-success btn-flex btn-center ${row.is_started ? 'attendance':''}"  data-column="sdriver" rq-url="${row.url}"
                        data-att="absent" data-id="${row.encrypted_id}" data-kt-follow-btn="true" ${row.is_started ? '':'disabled'}>
                                    <i class="ki-duotone ki-cross fs-3 absent d-none">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                    <i class="ki-duotone ki-check present fs-3"></i>
                                    <span class="indicator-label text-capitalize">Present</span>
                                </button>`;
                    },
                },

                {
                    data: "tractor_trailer_status", name: "tractor_trailer_status", title: "Tractor/Trailer Status",
                    render: function (data, type, row) {
                        return `<select class="form-select form-select-sm ${row.is_started ? 'update-tt-status':''}" data-control="select2" data-placeholder="Select an option" data-minimum-results-for-search="Infinity" ${row.is_started ? '':'disabled'}>
                                <option></option>
                                <option value="1">On Trip</option>
                                <option value="2">No Driver</option>
                                <option value="3">For PMS</option>
                                <option value="4">Available</option>
                                <option value="4">Absent Driver</option>
                                <option value="4">Trailer Repair</option>
                                <option value="4">Tractor Repair</option>
                            </select>
                        `;
                    },
                },

                {
                    data: "remarks", name: "remarks", title: "Remarks",
                    render: function (data, type, row) {
                        return `<input type="text" class="form-control form-control-sm ${row.is_started ? 'update-att-remarks':''}"
                                    name="unit_remarks" value="${data}" data-id="${row.encrypted_id}"
                                    rq-url="update_att_remarks" ${row.is_started ? '':'disabled'}>`;
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
                            <a href="javascript:;" class="btn btn-icon btn-light-primary btn-sm me-1 hover-elevate-up ${row.is_started ? 'edit':''}"
                                data-bs-toggle="tooltip" data-bs-placement="left" title="Update Tractor/Trailer or Drivers" data-id="${data}" ${row.is_started ? '':'disabled'}>
                                <i class="ki-duotone ki-pencil fs-2x">
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
            1
        );
    }

   $(`#${_table}_table`).ready(function() {

       $(`#${_table}_table`).on('click', '.edit', function(e) {
            e.preventDefault();
            e.stopImmediatePropagation();

            let _this = $(this);
            let url   =_this.attr('rq-url');
            let id    =_this.attr('data-id');

            let formData = new FormData;
            formData.append('id',id);

            // _request.post(url,formData)
            // .then((res) => {
            //     Alert.toast(res.status,res.message);

            // })
            // .catch((error) => {
            //     Alert.alert('error', "Something went wrong. Try again later", false);
            // })
            // .finally((error) => {

            // });


        })

       $(`#${_table}_table`).on('click','.delete',function(e){
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

        $(`#${_table}_table`).on('click','.attendance',function(e){
            e.preventDefault()
            e.stopImmediatePropagation()

            let _this = $(this);
            let id    =_this.attr('data-id');
            let column = _this.attr('data-column');
            let data_att = _this.attr('data-att');
            let data_url = _this.attr('rq-url');

            let formData = new FormData;
            formData.append('id',id);
            formData.append('column',column);
            formData.append('attendance',data_att);
            formData.append('haulage_id',param);

            _request.post(_urls[data_url],formData)
            .then((res) => {
                Alert.toast(res.status,res.message);
                if(res.status =='success'){
                    _this.removeClass('btn-light-success btn-light-danger');
                    _this.addClass('btn-light-'+attendance[data_att].css);

                    _this.find(`i.${attendance[data_att].data_att}`).addClass('d-none');
                    _this.find('i.'+data_att).removeClass('d-none');
                    _this.find('.indicator-label').text(data_att);

                    _this.attr('data-action',attendance[data_att].data_att);
                    _this.blur();
                }
            })
            .catch((error) => {
                Alert.alert('error', "Something went wrong. Try again later", false);
            })
            .finally((error) => {

            });

        })


    })

    return {
        init: function () {
            initTable();
        }
    }

}
