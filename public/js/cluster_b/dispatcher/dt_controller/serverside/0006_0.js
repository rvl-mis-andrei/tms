"use strict";

import { DataTableHelper } from "../../../../global/datatable.js";
import {Alert} from "../../../../global/alert.js"
import {RequestHandler} from "../../../../global/request.js"
import {modal_state,createBlockUI,data_bs_components} from "../../../../global.js"
import { fvTractorTrailer } from "../../fv_controller/0002_1.js";
import {trigger_select} from "../../../../global/select.js"

export var DriverDT = function (param) {

    const _page = $('.page-driver-list');
    const _table = 'driver';
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
            'services/cluster_driver/datatable',
            {
                filter_status:$('select[name="filter_driver_status"]').val(),
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
                    data: "driver", name: "driver", title: "Driver",
                    className:'',
                    render(data,type,row)
                    {
                        if(!data){
                            return '--';
                        }
                        return `<div class="d-flex align-items-center">
                                <div class="symbol symbol-circle symbol-50px overflow-hidden me-3">
                                    <span>
                                        <div class="symbol-label fs-3 bg-light-primary text-primary">
                                            ${data[0]}
                                        </div>
                                    </span>
                                </div>
                                <div class="d-flex flex-column">
                                    <span class="text-gray-800 text-hover-primary mb-1">
                                        ${data}
                                    </span>
                                    <span class="text-muted fs-7">${row.cluster}</span>
                                </div>
                            </div>
                        `
                    }
                },
                {
                    data: "status", name: "status", title: "Status",
                    sortable:false,
                    searchable:false,
                    render: function (data, type, row) {
                        let status = {
                            1: ["success", "Active"],
                            2: ["info", "Inactive"],
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
                    data: "cluster", name: "cluster", title: "Cluster",
                    sortable:false,
                    searchable:false,
                    visible:false,
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

            _page.on('change','select.filter_table',function(e){
                e.preventDefault()
                e.stopImmediatePropagation()
                initTable();
            })

            _page.on('keyup','.search',function(e){
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
                let modal_id = '#modal_add_driver';
                let form = $('#form_add_driver');

                let formData = new FormData;

                formData.append('id',id);
                _request.post('/services/cluster_driver/info',formData)
                .then((res) => {
                    let payload = JSON.parse(window.atob(res.payload));

                    form.find('textarea[name="remarks"]').val(payload.remarks);
                    form.find('select[name="status"]').val(payload.status).trigger('change');
                    form.find('select[name="status"]').parent().removeClass('d-none');

                    let trailerType = form.find('select[name="trailer_driver"]');
                    console.log(payload);
                    trigger_select(trailerType,payload.name);
                    trailerType.attr('disabled',true);

                    $(modal_id).find('button.submit').attr('data-id',id);
                    $(modal_id).find('.modal_title').text('Update Details');

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
                        _request.post('/services/cluster_driver/delete',formData)
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
