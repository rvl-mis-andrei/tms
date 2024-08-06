"use strict";

import { DataTableHelper } from "../../../../global/datatable.js";
import {Alert} from "../../../../global/alert.js"
import {RequestHandler} from "../../../../global/request.js"
import {modal_state} from "../../../../global.js"
import {trigger_select} from "../../../../global/select.js"



export async function TractorTrailerListDT() {

    let dataTableHelper = new DataTableHelper("tractor_trailer_table","tractor_trailer_wrapper");
    let page = $('.tractor_trailer_listing');

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
            { data: "tractor", name: "tractor", title: "Tractor",
                render: function(data,type,row){
                    if(data !=null){
                        return `<div class="d-flex flex-column">
                            <a href="javascript:;" class="text-gray-800 text-hover-primary mb-1">${data}</a>
                            <span>${row.tractor_plate_no}</span>
                        </div>`;
                    }
                    return 'No Tractor';
                }
            },
            { data: "tractor_plate_no", name: "tractor_plate_no", title: "Tractor Plate No",visible:false },
            {
                data: "trailer", name: "trailer", title: "Trailer",
                render: function(data,type,row){
                    if(data !=null){
                        return `<div class="d-flex flex-column">
                            <a href="javascript:;" class="text-gray-800 text-hover-primary mb-1">${data}</a>
                            <span>${row.trailer_type}</span>
                        </div>`;
                    }
                     return 'No Trailer';
                }
            },
            { data: "trailer_type", name: "trailer_type", title: "Trailer Type",visible:false },
            { data: "pdriver_emp", name: "pdriver_emp", title: "Driver 1",
                render: function(data,type,row){
                    if(data !=null){ return data; }
                    return '--';
                }
            },
            { data: "sdriver_emp", name: "sdriver_emp", title: "Driver 2",
                render: function(data,type,row){
                    if(data !=null){ return data; }
                    return '--';
                }
            },
            {
                data: "remarks", name: "remarks", title: "Remarks" ,
                render: function(data,type,row){
                    return `<div class="d-flex flex-column">
                            <a href="javascript:;" class="text-muted text-hover-primary mb-1">${data}</a>
                        </div>`;
                }
            },
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
                            <a class="btn btn-icon btn-icon btn-light-primary btn-sm me-1 hover-elevate-up"
                            href="/tms/cco-b/dispatcher/tractor_trailer_info/${data}" data-bs-toggle="tooltip" title="View Truck Trailer Details">
                                <i class="ki-duotone ki-pencil fs-2x">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                    <span class="path4"></span>
                                </i>
                            </a>
                            <a class="btn btn-icon btn-icon btn-light-danger btn-sm me-1 hover-elevate-up delete" data-id="${data}" url="/services/tractor_trailer/upsert"
                            id="" data-bs-toggle="tooltip" title="Delete this record">
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

    page.on('keydown','.search', function (e) {
        e.stopImmediatePropagation()
        if (e.key === 'Enter' || e.keyCode === 13) {
            const searchTerm = $(this).val();
            dataTableHelper.search(searchTerm)
        }
    });

    page.on('change','.status',function(e){
        e.preventDefault()
        e.stopImmediatePropagation()
        TractorTrailerListDT()
    })

    page.on('click','.delete',function(e){
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
                    $("#tractor_trailer_table").DataTable().ajax.reload(null, false);
                });
            }
        });

    })

    // page.on('click','.edit',function(e){
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
    //         trigger_select({
    //             'select[name="tractor"]': data.tractor,
    //             'select[name="trailer"]': data.trailer,
    //             'select[name="pdriver"]': data.pdriver,
    //             'select[name="sdriver"]': data.sdriver,
    //         })
    //         $('textarea[name="remarks"]').val(data.remarks);
    //         $('select[name="is_active"]').val(data.status).trigger('change');
    //         $('.modal_title').text('Edit Tractor Trailer Details');
    //         // $('#form').attr('action','/services/client/upsert');
    //         $('.submit').attr('data-id',btn_data);
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
