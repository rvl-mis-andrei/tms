"use strict";

import { DataTableHelper } from "../../../../global/datatable.js";
import {Alert} from "../../../../global/alert.js"
import {RequestHandler} from "../../../../global/request.js"
import {modal_state} from "../../../../global.js"


export var ClientListDT = function (param) {

    const _page = $('.client_listing');
    const _table = 'client_list';
    const dataTableHelper = new DataTableHelper(`${_table}_table`,`${_table}_wrapper`);

    function initTable(){

        dataTableHelper.initTable(
            `services/client/datatable`,
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
                { data: "name", name: "name", title: "Name" },
                {
                    data: "description", name: "description", title: "Description",
                    className:'',
                },
                {
                    data: "is_active",
                    name: "is_active",
                    title: "Status",
                    render: function (data, type, row) {
                        var status = {
                            Active: "success",
                            Inactive: "info",
                        };
                        return `<span class="badge badge-${status[data]}">${data}</span>`;
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
                                            <a href="javascript:;" class="menu-link px-3 edit" id="" data-id="${data}" url="/services/client/info" modal-id="#modal_add_client">Edit Details</a>
                                        </div>
                                    </div>
                                    <a class="btn btn-icon btn-icon btn-light-danger btn-sm me-1 hover-elevate-up delete" data-id="${data}" url="/services/client/delete"
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

        $(`#${_table}_table`).ready(function() {

            _page.on('keyup','.search',function(e){
                e.preventDefault()
                e.stopImmediatePropagation()

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
            });

            _page.on('change','.status',function(e){
                e.preventDefault()
                e.stopImmediatePropagation()
                initTable()
            })

            $(`#${_table}_table`).on('click','.delete',function(e){
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

            $(`#${_table}_table`).on('click','.edit',function(e){
                e.preventDefault()
                e.stopImmediatePropagation()

                let _this = $(this);
                let id = _this.attr('data-id');
                let modal_id = _this.attr('modal-id');

                let form = $('#form');
                let formData = new FormData();
                formData.append('id',id);

                (new RequestHandler).post(_this.attr('url'),formData).then((res) => {
                    let data = JSON.parse(window.atob(res.payload));

                    form.find('input[name="name"]').val(data.name);
                    form.find('textarea[name="description"]').val(data.description);
                    form.find('select[name="is_active"]').val(data.is_active).trigger('change')
                    .parent().removeClass('d-none');

                    $(modal_id).find('button.submit').attr('data-id',id);
                })
                .catch((error) => {
                    console.log(error)
                    Alert.alert('error',"Something went wrong. Try again later", false);
                })
                .finally(() => {
                    $(modal_id).find('.modal_title').text('Edit Details');
                    $(modal_id).find('#form').attr('action','/services/client/update');
                    modal_state(modal_id,'show');
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
