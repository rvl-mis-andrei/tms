"use strict";

import { DataTableHelper } from "../../../../global/datatable.js";
import {Alert} from "../../../../global/alert.js"
import {RequestHandler} from "../../../../global/request.js"
import {modal_state} from "../../../../global.js"



export async function DealershipListDT(param) {

    const dataTableHelper = new DataTableHelper("dealership_list_table","dealership_list_wrapper");
    dataTableHelper.initTable(
        `services/client_dealership/datatable`,
        {
            status:$('.status').val(),
            client_id:param
        },
        [
            {
                data: "count",
                name: "count",
                title: "No.",
                responsivePriority: -3,
            },
            { data: "name", name: "name", title: "Name" },
            {
                data: "code", name: "code", title: "Code",
                className:'',
            },
            {
                data: "location", name: "location", title: "Location",
                className:'',
            },
            {
                data: "receiving_personnel", name: "receiving_personnel", title: "Receiving Personnel",
                className:'',
            },
            {
                data: "pv_lead_time", name: "pv_lead_time", title: "PV Lead Time",
                className:'',
            },
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
                                <a href="#" id="edit" data-id="${data}" url="/services/client_dealership/info" modal-id="#modal_add_dealership" class="btn btn-icon btn-light-primary btn-sm me-1 hover-elevate-up">
                                    <i class="ki-duotone ki-pencil fs-2x">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                        <span class="path4"></span>
                                    </i>
                                </a>
                                <a class="btn btn-icon btn-icon btn-light-danger btn-sm me-1 hover-elevate-up" data-id="${data}" url="/services/client_dealership/delete"
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
            console.log($(this).val().length)
            const searchTerm = $(this).val();
            dataTableHelper.search(searchTerm)
        }
    });

    $('#status').on('change',function(e){
        e.preventDefault()
        e.stopImmediatePropagation()
        DealershipListDT()
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
                    $("#dealership_list_table").DataTable().ajax.reload(null, false);
                });
            }
        });

    })

    // app.on('click','#edit',function(e){
    //     e.preventDefault()
    //     e.stopImmediatePropagation()
    //     let btn_edit = $(this);
    //     let formData = new FormData();

    //     let btn_data = btn_edit.attr('data-id');
    //     let modal_id = $(this).attr('modal-id');
    //     let url = btn_edit.attr('url');
    //     formData.append('id',btn_data);

    //     (new RequestHandler).post(url,formData).then((res) => {
    //         let data = JSON.parse(window.atob(res.payload));
    //         $('input[name="name"]').val(data.name);
    //         $('input[name="code"]').val(data.code);
    //         $('input[name="pv_lead_time"]').val(data.pv_lead_time);
    //         $('select[name="is_active"]').val(data.is_active).trigger('change');
    //         $('.modal_title').text('Edit Dealership Details');
    //         $('#form').attr('action','/services/client_dealership/update');
    //         $('#submit').attr('data-id',btn_data);
    //     })
    //     .catch((error) => {
    //         console.log(error)
    //         Alert.alert('error',"Something went wrong. Try again later", false);
    //     })
    //     .finally(() => {
    //         modal_state(modal_id,'show');
    //     });
    // })

}
