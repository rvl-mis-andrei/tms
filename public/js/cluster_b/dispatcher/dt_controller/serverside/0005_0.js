"use strict";

import { DataTableHelper } from "../../../../global/datatable.js";
import {Alert} from "../../../../global/alert.js"
import {RequestHandler} from "../../../../global/request.js"
import {modal_state} from "../../../../global.js"



export async function TractorTrailerListDT() {

    const dataTableHelper = new DataTableHelper("tractor_trailer_table","tractor_trailer_wrapper");
    dataTableHelper.initTable(
        `services/tractor_trailer/datatable`,
        {
            status:$('.status').val(),
        },
        [
            {
                data: "count",
                name: "count",
                title: "No.",
                responsivePriority: -3,
            },
            { data: "trailer", name: "trailer", title: "Tractor" },
            { data: "trailer", name: "trailer", title: "Trailer" },
            { data: "trailer_type", name: "trailer_type", title: "Trailer Type" },
            {
                data: "status",
                name: "status",
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
                                <a href="#" class="btn btn-icon btn-light-primary btn-sm me-1 hover-elevate-up" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                    <i class="ki-duotone ki-pencil fs-2x">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                        <span class="path4"></span>
                                    </i>
                                </a>
                                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-7 w-125px py-4" data-kt-menu="true">
                                    <div class="menu-item px-3">
                                        <a href="/tms/cco-b/dispatcher/client_info/${data}" class="menu-link px-3">View</a>
                                    </div>
                                    <div class="menu-item px-3">
                                        <a href="javascript:;" class="menu-link px-3" id="edit" data-id="${data}" url="/services/client/info" modal-id="#modal_add_client">Edit Details</a>
                                    </div>
                                </div>
                                <a class="btn btn-icon btn-icon btn-light-danger btn-sm me-1 hover-elevate-up" data-id="${data}" url="/services/client/delete"
                                id="delete" data-bs-toggle="tooltip" title="Delete this record">
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
        1
    );

    $("#search").on("keydown", function (e) {
        if (e.key === 'Enter' || e.keyCode === 13) {
            const searchTerm = $(this).val();
            dataTableHelper.search(searchTerm)
        }
    });

    $('#status').on('change',function(e){
        e.preventDefault()
        e.stopImmediatePropagation()
        TractorTrailerListDT()
    })

    app.on('click','#delete',function(e){
        e.preventDefault()
        e.stopImmediatePropagation()
        let btn_delete = $(this);

        Alert.confirm('question',"Delete this record ?",{
            onConfirm: () => {
                let formData = new FormData();
                let form_url = btn_delete.attr('url');
                formData.append('id',btn_delete.attr('data-id'));
                formData.append('is_deleted',1);
                formData.append('is_active',0);
                (new RequestHandler).post(form_url,formData).then((res) => {
                    Alert.toast(res.status,res.message);
                })
                .catch((error) => {
                    console.log(error)
                    Alert.alert('error',"Something went wrong. Try again later", false);
                })
                .finally(() => {
                    $("#client_list_table").DataTable().ajax.reload(null, false);
                });
            }
        });

    })

    app.on('click','#edit',function(e){
        e.preventDefault()
        e.stopImmediatePropagation()
        let btn_edit = $(this);
        let formData = new FormData();

        let btn_data = btn_edit.attr('data-id');
        let modal_id = $(this).attr('modal-id');
        let url = btn_edit.attr('url');
        formData.append('id',btn_data);

        (new RequestHandler).post(url,formData).then((res) => {
            let data = JSON.parse(window.atob(res.payload));
            $('input[name="name"]').val(data.name);
            $('textarea[name="description"]').val(data.description);
            $('select[name="is_active"]').val(data.is_active).trigger('change');
            $('.modal_title').text('Edit Client Details');
            $('#form').attr('action','/services/client/update');
            $('#submit').attr('data-id',btn_data);
        })
        .catch((error) => {
            console.log(error)
            Alert.alert('error',"Something went wrong. Try again later", false);
        })
        .finally(() => {
            modal_state(modal_id,'show');
        });
    })

}
