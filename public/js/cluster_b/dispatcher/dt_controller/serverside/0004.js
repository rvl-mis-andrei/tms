"use strict";

import { DataTableHelper } from "./../../../../global/datatable.js";

export function ClientListDT() {

    const dataTableHelper = new DataTableHelper();

    dataTableHelper.initTable(
        `services/client_list/datatable`,
        {
            status: status,
        },
        [
            // {
            //     data: "",
            //     name: "",
            //     title: "",
            //     responsivePriority: -1,
            //     className: "pl-0",
            // },
            {
                data: "count",
                name: "count",
                title: "No.",
                responsivePriority: -3,
            },
            { data: "name", name: "name", title: "Name" },
            {
                data: "is_active",
                name: "is_active",
                title: "Status",
                render: function (data, type, row) {
                    var status = {
                        Active: "success",
                        Inactive: "danger",
                    };
                    return `<span class="badge badge-${status[data]}">${data}</span>`;
                },
            },
            {
                data: "encrypt_id",
                name: "encrypt_id",
                title: "Action",
                className: "text-center",
                responsivePriority: -1,
                render: function (data, type, row) {
                    let checked = "";
                    if (row.is_active == "Active") {
                        checked = "checked";
                    }

                    return `<div class="d-flex justify-content-center flex-shrink-0">
                                <a class="btn btn-icon btn-light-success  btn-sm me-1 hover-elevate-up" href="/tms/cco-b/dispatcher/client_info/${data}" data-bs-toggle="tooltip" data-bs-placement="top" title="View Info">
                                        <i class="bi bi-pencil fs-2"></i>
                                </a>
                                <button class="btn btn-icon btn-light-primary btn-sm me-1 hover-elevate-up" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                        <i class="la la-gear fs-1"></i>
                                </button>
                                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-7 w-125px py-4" data-kt-menu="true">
                                    <div class="menu-item px-3">
                                        <label class="form-check form-switch form-check-custom form-check-solid">
                                            <span class="form-check-label fw-bold text-muted me-2">Status</span>
                                            <input class="form-check-input change_status" type="checkbox" data-id="${data}" ${checked}>
                                        </label>
                                    </div>
                                </div>
                            </div>`;
                },
            },
        ],
        null,
        1
    );

    $("#search").on("input", function () {
        const searchTerm = $(this).val();
        dataTableHelper.search(searchTerm);
    });

}
