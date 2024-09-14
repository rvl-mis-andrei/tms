'use strict';
'use strict';
import {Alert} from "../../../global/alert.js"
import {RequestHandler,} from "../../../global/request.js"
import {data_bs_components,modal_state,page_state,custom_upload,createBlockUI} from "../../../global.js";

export var HaulingPlanInfoController = function (page,param) {

    var block_number = 0;
    const _page = $('.haulage_info_page');
    const hauling_list = $('.hauling_list');
    const empty_hauling_list = $('.empty_hauling_list');
    const _alert = _page.find('.page-alert');
    const HaulingCard = createBlockUI('.hauling_list_card', 'Loading...');
    // const AllocationCard = createBlockUI('.for_allocation_card', 'Loading...');

    function loadTripBlock(batch = 1) {
        const formData = new FormData();
        formData.append('id', param);
        formData.append('batch', batch);

        return new Promise((resolve, reject) => {
            (new RequestHandler).post('/tms/cco-b/dispatcher/haulage_info/tripblock', formData)
                .then((res) => {
                    if (res.status === 'success') {
                        let payload = JSON.parse(window.atob(res.payload));
                        let html = '', tbody = '', tripBlockCount = 0, trpBlockUnit = 0;
                        let isAllBlockforDispatch = false;
                        let isBlockforPlanning = true;
                        hauling_list.addClass('d-none').empty();
                        empty_hauling_list.empty();
                        if (payload.length > 0) {
                            payload.forEach((item, key) => {
                                tbody = item.block_units.map(units => `
                                    <tr data-original-table="tbl_${units.dealer_code}_${units.hub}"
                                    data-type="forAllocation" data-id="${units.encrypted_id}">
                                        <td class="remove-cell">${units.dealer_code}</td>
                                        <td>${units.cs_no}</td>
                                        <td>${units.model}</td>
                                        <td>${units.color_description}</td>
                                        <td>${units.invoice_date}</td>
                                        <td>${units.updated_location}</td>
                                        <td class="remove-cell">${units.inspection_start}</td>
                                        <td class="remove-cell">${units.hub}</td>
                                        <td class="remove-cell">${units.remarks}</td>
                                    </tr>`).join('');

                                html = `
                                    <div class="card mb-5">
                                        <div class="card-header collapsible">
                                            <span class="card-title"><h6>${item.dealer_code ?? 'Trip Block #' + (key + 1)}</h6></span>
                                            <div class="card-toolbar">
                                                ${item.status !== 2 ? `
                                                <div class="me-0">
                                                    <button class="btn btn-sm btn-icon btn-active-color-primary" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                                        <i class="ki-solid ki-dots-horizontal fs-2x"></i>
                                                    </button>
                                                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-semibold w-200px py-3" data-kt-menu="true">
                                                        <div class="menu-item px-3">
                                                            <div class="menu-content text-muted pb-2 px-3 fs-7 text-uppercase">More Actions</div>
                                                        </div>
                                                        <div class="separator my-2 opacity-75"></div>
                                                        <div class="menu-item px-3">
                                                            <a href="#" class="menu-link px-3 text-danger remove-block" data-id="${item.encrypted_id}">Remove Block</a>
                                                        </div>
                                                    </div>
                                                </div>` : ''}
                                                <div class="rotate btn btn-icon btn-sm btn-active-color-info" data-kt-rotate="true" data-bs-toggle="collapse" data-bs-target="#trip_block_${item.block_number}">
                                                    <i class="ki-duotone ki-down fs-1 rotate-n180"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="trip_block_${item.block_number}" class="collapse show">
                                            <div class="card-body pt-0">
                                                <div class="table-responsive">
                                                    <table class="table table-row-bordered align-middle gy-5 table-sm" id="tbl_block_${item.block_number}" data-type="planning" data-id="${item.encrypted_id}">
                                                        <thead>
                                                            <tr class="fw-bold fs-8 text-uppercase gs-0">
                                                                <th>Dealer</th>
                                                                <th>Cs No.</th>
                                                                <th>Model</th>
                                                                <th>Color</th>
                                                                <th>Invoice Date</th>
                                                                <th>Location</th>
                                                                <th>Inspection Time</th>
                                                                <th>Hub</th>
                                                                <th>Remarks</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="fs-8 fw-semibold text-gray-600" data-type="planning" data-id="${item.encrypted_id}">
                                                            ${tbody}
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>`;
                                tripBlockCount++;
                                block_number = item.block_number;
                                trpBlockUnit += item.block_units.length;
                                if (item.status == 2) {
                                    isAllBlockforDispatch = true;
                                    isBlockforPlanning = false;
                                }
                                hauling_list.append(html);
                            });
                        } else {
                            setEmptyState();
                        }

                        alertHaulageState(isAllBlockforDispatch, batch,isBlockforPlanning);
                        updateCount(trpBlockUnit, tripBlockCount);
                        hauling_list.removeClass('d-none');
                        console.log(123)
                        resolve(true);
                    }

                })
                .catch((error) => {
                    console.log(error);
                    resolve(false);
                    Alert.alert('error', "Something went wrong. Try again later", false);
                });
        });
    }

    function alertHaulageState(isAllBlockforDispatch, batch,isBlockforPlanning) {
        if($('.complete-haulage').length <= 0){
            _alert.empty();
            if (isAllBlockforDispatch) {
                _alert.html(`
                    <div class="alert alert-dismissible bg-light-success d-flex flex-column flex-sm-row p-5 mb-5">
                        <i class="ki-duotone ki-notification-bing fs-2hx text-primary me-4 mb-5 mb-sm-0">
                        <span class="path1"></span><span class="path2"></span><span class="path3"></span>
                        </i>
                        <div class="d-flex flex-column pe-0 pe-sm-10">
                            <h4 class="fw-semibold">Batch ${batch} for Dispatch</h4>
                            <span>This batch is ready for dispatching.</span>
                        </div>
                        <button type="button" class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto" data-bs-dismiss="alert">
                            <i class="ki-duotone ki-cross fs-1 text-primary"><span class="path1"></span><span class="path2"></span></i>
                        </button>
                    </div>
                `);
                $('.finalize-dispatch').removeClass('d-none');
            }else if (isBlockforPlanning) {
                _alert.html(`
                    <div class="alert alert-dismissible bg-light-warning d-flex flex-column flex-sm-row p-5 mb-5">
                        <i class="ki-duotone ki-notification-bing fs-2hx text-primary me-4 mb-5 mb-sm-0">
                        <span class="path1"></span><span class="path2"></span><span class="path3"></span>
                        </i>
                        <div class="d-flex flex-column pe-0 pe-sm-10">
                            <h4 class="fw-semibold">Batch ${batch} Is Not Ready</h4>
                            <span>This batch is still under planning.</span>
                        </div>
                        <button type="button" class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto" data-bs-dismiss="alert">
                            <i class="ki-duotone ki-cross fs-1 text-primary"><span class="path1"></span><span class="path2"></span></i>
                        </button>
                    </div>
                `);
                $('.finalize-dispatch').addClass('d-none');
            }
        }
    }

    function setEmptyState() {
        hauling_list.addClass('d-none');
        empty_hauling_list.empty().append(`
            <div class="card-px text-center pt-20 pb-10">
                <h2 class="fs-2x mb-0">No Trip Block Found</h2>
                    <p class="text-gray-500 fs-4 fw-semibold py-7">
                        Wait for your <span class="text-info">Planner</span> to complete the planning.
                    </p>
            </div>
                <div class="text-center pb-15 px-5">
                    <img src="${asset_url+'/media/illustrations/sketchy-1/16.png'}" alt="" class="mw-100 h-200px h-sm-325px">
                </div>
            </div>
            `);
        $('.finalize-plan, .add-block, .more-actions').removeClass('d-none');
    }

    function updateCount(unitCount, blockCount) {
        $('.unit-count').text(unitCount);
        $('.trip-count').text(blockCount);
        KTComponents.init();
        data_bs_components();
    }

    function loadScript()
    {
        _page.on('change','select[name="batch"]',function(e){
            e.preventDefault()
            e.stopImmediatePropagation()

            HaulingCard.block();

            loadTripBlock($(this).val()).then((res) =>{
                setTimeout(function() {
                    HaulingCard.release();
                }, 500);

            })
        })
    }


    return {
        init: function () {

            page_block.block();

            loadTripBlock().then((res) => {
                if (res) {
                    // Delay releasing the page block
                    setTimeout(() => {
                        page_block.release();
                        KTComponents.init(); // Initialize components after the page is released
                    },500);
                }
            }).catch((error) => {
                console.error("Error loading trip block:", error);
                Alert.alert('error', "Failed to load trip block. Please try again.", false);
            }).finally(() => {
                loadScript()
            });

        }
    }

}
