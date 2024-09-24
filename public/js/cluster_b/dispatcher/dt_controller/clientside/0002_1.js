"use strict";

import { DataTableHelper } from "../../../../global/datatable.js";
import {Alert} from "../../../../global/alert.js"
import {RequestHandler} from "../../../../global/request.js"
import {modal_state,createBlockUI} from "../../../../global.js"



export var TractorTrailerDT = function (param) {

    const _page = $('.haulage_info_page');
    const _table = 'tractor_trailer_driver';
    const dataTableHelper = new DataTableHelper(`${_table}_table`,`${_table}_wrapper`);

    function initTable(){
        return new Promise((resolve, reject) => {
            try {
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
                                return `<div class="d-flex flex-column">
                                    <a href="javscript:;" class="text-gray-800 text-hover-primary mb-1">${data}</a>
                                    <span class="text-muted">${row.tractor_plate_no}</span>
                                </div>`;
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
                                return `<div class="d-flex flex-column">
                                    <a href="javscript:;" class="text-gray-800 text-hover-primary mb-1">${data}</a>
                                    <span class="text-muted">${row.trailer_type}</span>
                                </div>`;
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
                            render: function (data, type, row) {
                                return `<div class="d-flex flex-column">
                                    <a href="javscript:;" class="text-gray-800 text-hover-primary mb-1">${data}</a>
                                    <span class="text-muted">Driver 1</span>
                                </div>`;
                            },
                        },

                        {
                            data: "pdriver_att", name: "pdriver_att", title: "Att.",
                            render: function (data, type, row) {
                                return `<button class="btn btn-sm btn-light-success btn-flex btn-center" data-kt-follow-btn="true">
                                            <i class="ki-duotone ki-plus follow fs-3 d-none"></i>
                                            <i class="ki-duotone ki-check following fs-3"></i>
                                            <span class="indicator-label">Present</span>
                                            <span class="indicator-progress">
                                                Please wait...
                                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                            </span>
                                        </button>`;
                            },
                        },

                        {
                            data: "sdriver_name", name: "sdriver_name", title: "Driver 1",
                            render: function (data, type, row) {
                                return `<div class="d-flex flex-column">
                                    <a href="javscript:;" class="text-gray-800 text-hover-primary mb-1">${data}</a>
                                    <span class="text-muted">Driver 2</span>
                                </div>`;
                            },
                        },
                        {
                            data: "pdriver_att", name: "pdriver_att", title: "Att.",
                            render: function (data, type, row) {
                                return `<button class="btn btn-sm btn-light-success btn-flex btn-center" data-kt-follow-btn="true">
                                            <i class="ki-duotone ki-plus follow fs-3 d-none"></i>
                                            <i class="ki-duotone ki-check following fs-3"></i>
                                            <span class="indicator-label">Present</span>
                                            <span class="indicator-progress">
                                                Please wait...
                                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                            </span>
                                        </button>`;
                            },
                        },

                        {
                            data: "tractor_trailer_status", name: "tractor_trailer_status", title: "Tractor/Trailer Status",
                            render: function (data, type, row) {
                                return `<select class="form-select form-select-sm" data-control="select2" data-placeholder="Select an option" data-minimum-results-for-search="Infinity">
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
                                return `<input type="text" class="form-control form-control-sm"
                            name="unit_remarks" value="${data}" data-id="${row.encrypted_id}"
                            rq-url="/tms/cco-b/planner/haulage_info/update_unit_remarks">`;
                            },
                            // <input type="text" class=" ${/priority/i.test(units.remarks) ? 'text-danger' : 'text-muted'} form-control form-control-sm form-control-solid"
                            // name="unit_remarks" value="${units.remarks}" data-id="${units.encrypted_id}"
                            // rq-url="/tms/cco-b/planner/haulage_info/update_unit_remarks">
                        }

                        // {
                        //     data: "creator", name: "creator", title: "Created By",
                        //     render: function (data, type, row) {
                        //         return `<div class="d-flex flex-column">
                        //             <a href="javscript:;" class="text-gray-800 text-hover-primary mb-1">${data}</a>
                        //             <span class="text-muted">${row.creator_role}</span>
                        //         </div>`;
                        //     },
                        // },
                        // {
                        //     data: "creator_role", name: "creator_role", title: "Created By",
                        //     className:'',
                        //     visible:false,
                        //     searchable:false,
                        // },
                        // {
                        //     data: "created_date", name: "created_date", title: "Created Date",
                        //     className:'',
                        // },
                        // {
                        //     data: "file_type", name: "file_type", title: "Plan Type",
                        //     className:'',
                        //     visible:false,
                        //     searchable:false,
                        // },
                        // {
                        //     data: "status",
                        //     name: "status",
                        //     title: "Status",
                        //     searchable:false,
                        //     sortable:false,
                        //     render: function (data, type, row) {
                        //         return `<span class="badge badge-${data[1]}">${data[0]}</span>`;
                        //     },
                        // },
                        // {
                        //     data: "view_url",
                        //     name: "view_url",
                        //     title: "view_url",
                        //     searchable:false,
                        //     visible:false,
                        // },
                        // {
                        //     data: "encrypted_id",
                        //     name: "encrypted_id",
                        //     title: "Action",
                        //     sortable:false,
                        //     className: "text-center",
                        //     responsivePriority: -1,
                        //     render: function (data, type, row) {
                        //         return `<div class="d-flex justify-content-center flex-shrink-0">
                        //             <a href="#" class="btn btn-icon btn-light-primary btn-sm me-1 hover-elevate-up" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                        //                 <i class="ki-duotone ki-pencil fs-2x">
                        //                     <span class="path1"></span>
                        //                     <span class="path2"></span>
                        //                     <span class="path3"></span>
                        //                     <span class="path4"></span>
                        //                 </i>
                        //             </a>
                        //             <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-7 w-175px py-4" data-kt-menu="true">
                        //                 <div class="menu-item px-3 text-start">
                        //                     <div class="menu-content text-muted pb-2 px-3 fs-7 text-uppercase">
                        //                         More Actions
                        //                     </div>
                        //                 </div>
                        //                 <div class="menu-item px-3">
                        //                     <a href="${row.view_url}" class="menu-link px-3">View</a>
                        //                 </div>
                        //                 <div class="menu-item px-3">
                        //                     <a href="javascript:;" data-id="${data}" class="menu-link px-3 export_haulaging_plan" rq-url="/tms/cco-b/planner/haulage_info/export_reports">
                        //                     Export Hauling Plan
                        //                     </a>
                        //                 </div>
                        //                 ${
                        //                     status == 'On-Going' ?`<div class="menu-item px-3">
                        //                         <a href="javascript:;" class="menu-link px-3 edit" id="" data-id="${data}" url="/services/haulage/info" modal-id="#modal_add_hauling_plan">Edit Details</a>
                        //                     </div>` : ''
                        //                 }
                        //                 ${
                        //                     status == 'On-Going' ?`<div class="separator my-2 opacity-75"></div>
                        //                 <div class="menu-item px-3">
                        //                     <label class="form-check form-switch form-check-custom form-check-solid">
                        //                         <span class="form-check-label fw-bold text-muted me-2">Status</span>
                        //                         <input class="form-check-input update_status" type="checkbox" data-id="${data}" rq-url="/services/haulage/update_status">
                        //                     </label>
                        //                 </div>`:''
                        //                 }
                        //             </div>

                        //             <a class="btn btn-icon btn-icon btn-light-danger btn-sm me-1 hover-elevate-up delete" data-id="${data}" url="/services/haulage/delete"
                        //             id="" data-bs-toggle="tooltip" title="Delete this record">
                        //                 <i class="ki-duotone ki-trash fs-2x">
                        //                     <span class="path1"></span>
                        //                     <span class="path2"></span>
                        //                     <span class="path3"></span>
                        //                     <span class="path4"></span>
                        //                 </i>
                        //             </a>
                        //         </div>`;
                        //     },
                        // },
                    ],
                    null,
                    1
                );
                resolve();
            } catch (error) {
                reject(error);
            }
        });
    }

    return {
        init: function () {
            return initTable();
        }
    }

}
