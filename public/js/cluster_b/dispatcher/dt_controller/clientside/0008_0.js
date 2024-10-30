"use strict";

import { DataTableHelper } from "../../../../global/datatable.js";
import {Alert} from "../../../../global/alert.js"
import {RequestHandler} from "../../../../global/request.js"
import {modal_state,createBlockUI,data_bs_components} from "../../../../global.js"
import { fvTractorTrailer } from "../../fv_controller/0002_1.js";
import {trigger_select} from "../../../../global/select.js"

export var CycleTimeTable = function (param=false) {

    const _page = $('.page-car-list');
    const _table = 'car';
    const _tab = $(`.${_table}`);
    const _request = new RequestHandler;

    function initTable(){

        _request.get('/tms/cco-b/dispatcher/cycle_time/table')
        .then((res) => {
            let payload = JSON.parse(window.atob(res.payload));
            console.log(payload)
            let table = '#cycletime_table';
            const mainCategories = Object.keys(payload);

            // Loop through each main category
            mainCategories.forEach((category) => {
                // Get the dealer codes object for the current category
                const dealerCodes = payload[category];

                // Determine the total number of entries for the current category
                let totalEntries = 1;

                // Calculate the total number of entries for rowspan
                Object.keys(dealerCodes).forEach((dealerCode) => {
                    totalEntries += dealerCodes[dealerCode].length;
                });

                // Start a new row for the main category with rowspan
                let categoryRow = `<tr>`;
                categoryRow += `<td rowspan="${totalEntries}">${category}</td>`; // Merging the category
                categoryRow += `</tr>`;
                $(`${table} tbody`).append(categoryRow);

                // Loop through each dealer code
                Object.keys(dealerCodes).forEach((dealerCode) => {
                    const entries = dealerCodes[dealerCode];

                    // Loop through each entry for the dealer code
                    entries.forEach((item, index) => {
                        // Start a new row for each item
                        let row = `<tr>`;

                        // If it's the first entry for a dealer code, add a cell for the dealer code name spanning rows
                        if (index === 0) {
                            row += `<td rowspan="${entries.length}">${dealerCode}</td>`;
                        }

                        // Populate the row with each field
                        row += `<td>${item.departure_garage}</td>`;
                        row += `<td>${item.svc_garage_to_pickup || 'N/A'}</td>`;
                        row += `<td>${item.bvc_garage_to_pickup || 'N/A'}</td>`;
                        row += `<td>${item.time_loading || 'N/A'}</td>`;
                        row += `<td>${item.departure_to_pickup || 'N/A'}</td>`;
                        row += `<td>${item.dealer_to_garage || 'N/A'}</td>`;
                        row += `<td>${item.svc_total_cycle_time || 'N/A'}</td>`;
                        row += `<td>${item.bvc_total_cycle_time || 'N/A'}</td>`;
                        row += `<td>${item.additional_day !== null ? item.additional_day : '--'}</td>`;

                        // Action column with specified HTML structure
                        row += `
                            <td>
                                <div class="d-flex justify-content-center flex-shrink-0">
                                    <a href="#" class="btn btn-icon btn-light-primary btn-sm me-1 hover-elevate-up"
                                    data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end"
                                    data-bs-toggle="tooltip" title="More Actions">
                                        <i class="ki-duotone ki-pencil fs-2x">
                                            <span class="path1"></span><span class="path2"></span>
                                            <span class="path3"></span><span class="path4"></span>
                                        </i>
                                    </a>
                                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600
                                        menu-state-bg-light-primary fw-bold fs-7 w-150px py-4" data-kt-menu="true">
                                        <div class="menu-item px-3 text-start">
                                            <div class="menu-content text-muted pb-2 px-3 fs-7 text-uppercase">
                                                More Actions
                                            </div>
                                        </div>
                                        <div class="menu-item px-3">
                                            <a href="javascript:;" data-id="${item.encrypted_id}"
                                            class="menu-link px-3 view-details">
                                                View Details
                                            </a>
                                        </div>
                                    </div>
                                    <a href="javascript:;" class="btn btn-icon btn-light-danger btn-sm me-1 hover-elevate-up delete"
                                    data-id="${item.encrypted_id}" data-bs-toggle="tooltip" title="Delete this record">
                                        <i class="ki-duotone ki-trash fs-2x">
                                            <span class="path1"></span><span class="path2"></span>
                                            <span class="path3"></span><span class="path4"></span>
                                        </i>
                                    </a>
                                </div>
                            </td>
                        `;

                        row += `</tr>`;
                        $(`${table} tbody`).append(row);
                    });
                });
            });


        })
        .catch((error) => {
            console.log(error);
            Alert.alert('error', "Something went wrong. Try again later", false);
        })
        .finally((error) => {
            //code here
        });

    }

    function formatFields(item) {
        let fields = '<ul>';
        $.each(item, function(key, value) {
            fields += `<li><strong>${key}</strong>: ${value !== null ? value : 'N/A'}</li>`;
        });
        fields += '</ul>';
        return fields;
    }

    return {
        init: function () {
            initTable();
        }
    }

}

