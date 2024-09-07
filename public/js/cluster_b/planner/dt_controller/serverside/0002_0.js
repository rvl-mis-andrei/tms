"use strict";

import { DataTableHelper } from "../../../../global/datatable.js";
import {Alert} from "../../../../global/alert.js"
import {RequestHandler} from "../../../../global/request.js"
import {modal_state} from "../../../../global.js"



export async function HaulingPlanDT() {

    let dataTableHelper = new DataTableHelper("hauling_plan_table","hauling_plan_wrapper");
    let page = $('.hauling_plan');

    dataTableHelper.initTable(
        `services/haulage/datatable`,
        {
            filter:$('.filter').val(),
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
                data: "remarks", name: "remarks", title: "Remarks",
                className:'',
            },
            {
                data: "status",
                name: "status",
                title: "Status",
                render: function (data, type, row) {
                    return `<span class="badge badge-${data[1]}">${data[0]}</span>`;
                },
            },
            {
                data: "encrypt_id",
                name: "encrypt_id",
                title: "Action",
                className: "text-center",
                responsivePriority: -1,
                render: function (data, type, row) {
                    let status = row.status[0];
                    console.log(status)
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
                                <a href="/tms/cco-b/planner/hauling_plan_info/${data}" class="menu-link px-3">View</a>
                            </div>
                            ${
                                status == 'On-Going' ?`<div class="menu-item px-3">
                                    <a href="javascript:;" class="menu-link px-3 edit" id="" data-id="${data}" url="/services/haulage/info" modal-id="#modal_add_hauling_plan">Edit Details</a>
                                </div>` : ''
                            }
                            ${
                                status == 'On-Going' ?`<div class="separator my-2 opacity-75"></div>
                            <div class="menu-item px-3">
                                <label class="form-check form-switch form-check-custom form-check-solid">
                                    <span class="form-check-label fw-bold text-muted me-2">Status</span>
                                    <input class="form-check-input update_status" type="checkbox" data-id="${data}" rq-url="/services/haulage/update_status">
                                </label>
                            </div>`:''
                            }
                        </div>

                        <a class="btn btn-icon btn-icon btn-light-danger btn-sm me-1 hover-elevate-up delete" data-id="${data}" url="/services/haulage/delete"
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

    page.on('keydown','.search',function(e){
        e.stopImmediatePropagation()
        if (e.key === 'Enter' || e.keyCode === 13) {
            const searchTerm = $(this).val();
            dataTableHelper.search(searchTerm)
        }
    });

    page.on('change','.filter',function(e){
        e.preventDefault()
        e.stopImmediatePropagation()
        HaulingPlanDT()
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
                    $("#hauling_plan_table").DataTable().ajax.reload(null, false);
                });
            }
        });

    })

    page.on('click','.new',function(e){
        e.stopImmediatePropagation()
        const options = { year: 'numeric', month: 'long', day: 'numeric' };
        let date = new Date().toLocaleDateString('en-US', options);
        $('input[name="name"]').val('Final Hauling Plan - '+date);
        document.querySelector("#planning_date")._flatpickr.setDate(new Date(),true);
    })

    page.on('click','.edit',function(e){
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
            $('textarea[name="remarks"]').val(data.remarks);
            $('select[name="status"]').val(data.status).trigger('change');
            $('select[name="plan_type"]').val(data.plan_type).trigger('change').prop('disabled',true);

            document.querySelector("#planning_date")._flatpickr.setDate(new Date(data.planning_date),true);
            $('input[name="planning_date"]').prop('disabled',true);

            $('.modal_title').text('Edit Hauling Plan Details');
            $('#form').attr('action','/services/haulage/update');
            $('.submit').attr('data-id',btn_data);
        })
        .catch((error) => {
            console.log(error)
            Alert.alert('error',"Something went wrong. Try again later", false);
        })
        .finally(() => {
            modal_state(modal_id,'show');
        });
    })

    page.on('change','.update_status',function(e){
        e.preventDefault()
        e.stopImmediatePropagation()
        let formData = new FormData();
        let data_id = $(this).attr('data-id');
        let url = $(this).attr('rq-url');
        let status = $(this).is(':checked')?1:2;
        formData.append('haulage_id',data_id);
        formData.append('status',status);

        (new RequestHandler).post(url,formData).then((res) => {
            Alert.toast(res.status,res.message);
        })
        .catch((error) => {
            console.log(error)
            Alert.alert('error',"Something went wrong. Try again later", false);
        })
        .finally(() => {
            $("#hauling_plan_table").DataTable().ajax.reload(null, false);
        });
    })

}
