<div class="haulage_info_page d-none">
    @if ($data['status'] ==1)
        <div class="alert alert-dismissible bg-light-primary d-flex flex-column flex-sm-row p-5 mb-5 complete-haulage">
            <i class="ki-duotone ki-notification-bing fs-2hx text-primary me-4 mb-5 mb-sm-0"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
            <div class="d-flex flex-column pe-0 pe-sm-10">
                <h4 class="fw-semibold">Hauling Plan Complete</h4>
                <span>This is to notify you that '{{ $data['updated_by'] }}' set the status of hauling plan as complete.</span>
            </div>

            <button type="button" class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto" data-bs-dismiss="alert">
                <i class="ki-duotone ki-cross fs-1 text-primary"><span class="path1"></span><span class="path2"></span></i>
            </button>
        </div>
    @endif
    <div class="finalize-notif"></div>
    <div class="row">
        <div class="col-lg-12 col-xl-9">
            <div class="card pt-0 mb-6 mb-xl-9 hauling_list_card">
                <div class="card-header border-0 ">
                    <div class="card-title flex-column">
                        <h3 class="fw-bold mb-1">Hauling Plan</h3>
                        <div class="fs-6 d-flex text-gray-500 fs-6 fw-semibold">
                            <div class="d-flex align-items-center me-6">
                                <span class="menu-bullet d-flex align-items-center me-2">
                                    <span class="bullet bg-success"></span>
                                </span>
                                Units : <span class="unit-count">0</span>
                            </div>
                            <div class="d-flex align-items-center">
                                <span class="menu-bullet d-flex align-items-center me-2">
                                    <span class="bullet bg-primary"></span>
                                </span>
                                Blocks : <span class="trip-count">0</span>
                            </div>
                        </div>
                    </div>
                    <div class="card-toolbar">
                        <div class="d-flex flex-stack flex-wrap gap-4">
                            <div class="d-flex align-items-center fw-bold">
                                <div class="text-muted fs-7 me-2">Batch:</div>
                                <select class="form-select form-select-transparent text-dark fs-7 lh-1 fw-bold py-0 ps-3 w-auto" data-control="select2"
                                data-hide-search="true" data-dropdown-css-class="w-150px" data-placeholder="Select a batch" data-minimum-results-for-search="Infinity"
                                rq-url="/services/haulage/add_batch" name="batch">
                                <option value="All Batch"> All Batch</option>
                                    @for ($i = 1; $i <= $data['batch_count']; $i++)
                                        <option value="{{ $i }}">Batch {{ $i }}</option>
                                    @endfor
                                    <option value="Add Batch"> Add Batch</option>
                                </select>

                            </div>
                            <div class="card-toolbar">
                                @if ($data['status'] ==2)
                                    <button class="btn btn-sm btn-light-info add-block me-2">
                                        Add Block
                                        <i class="ki-duotone ki-plus ms-1"></i>
                                    </button>
                                @endif

                                <button class="btn btn-sm btn-light-primary export-menu" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                    More Actions
                                    <i class="ki-duotone ki-down ms-1"></i>
                                </button>
                                <div class="menu menu-sub  more-actions-menu menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-semibold w-200px py-4 pb-3"
                                    data-kt-menu="true">
                                    <div class="menu-item px-3">
                                        <div class="menu-content text-muted pb-2 px-3 fs-7 text-uppercase">
                                            More Actions
                                        </div>
                                    </div>
                                    <div class="menu-item px-3">
                                        <a href="#" class="menu-link px-3 export-haulage" modal-id="#modal_export_hauling_plan">
                                            Export Trip Block
                                        </a>
                                    </div>
                                    @if ($data['status'] ==2)
                                        <div class="separator mt-2 opacity-75"></div>
                                        <div class="menu-item px-3 pt-3">
                                            <a href="#" class="menu-link px-3 finalize-plan text-success" data-status="1" rq-url="/tms/cco-b/planner/haulage_info/finalize_plan">
                                                Finalize Trip Block
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body card-scroll h-1000px bg-light-secondary p-5">
                    <div class="hauling_list d-none" id="tripblock_list">
                    </div>
                    <div class="empty_hauling_list d-none">
                        <div class="card-px text-center pt-20 pb-10">
                            <h2 class="fs-2x mb-0">No Trip Block Found</h2>
                            <p class="text-gray-500 fs-4 fw-semibold py-7">
                                Click the button <span class="text-info">Add Block</span> to
                                add trip blocks.
                            </p>
                        </div>
                        <div class="text-center pb-15 px-5">
                            <img src="{{ asset('assets/media/illustrations/sketchy-1/16.png') }}" alt="" class="mw-100 h-200px h-sm-325px">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12 col-xl-3">
            <div class="card pt-0 mb-6 mb-xl-9 for_allocation_card">
                <div class="card-header position-relative min-h-50px border-bottom-2 py-3 px-5">
                    <ul class="nav nav-pills nav-pills-custom gap-3" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link btn btn-color-muted border-0 h-100 px-0 active nav-tab" data-bs-toggle="pill" data-hub="svc" href="#tab_content_1" aria-selected="true" role="tab">
                                <span class="nav-text fw-bold fs-4 mb-3">
                                    SVC <span class="svc-count d-none">0</span>
                                </span>
                                <span class="bullet-custom position-absolute z-index-2 w-100 h-2px top-100 bottom-n100 bg-primary rounded"></span>
                            </a>
                        </li>
                        <li class="nav-item " role="presentation">
                            <a class="nav-link btn btn-color-muted border-0 h-100 px-0 nav-tab"  data-hub="bvc"  data-bs-toggle="pill" href="#tab_content_2" aria-selected="false" role="tab" tabindex="-1">
                                <span class="nav-text fw-bold fs-4 mb-3">
                                    BVC <span class="bvc-count d-none">0</span>
                                </span>
                                <span class="bullet-custom position-absolute z-index-2 w-100 h-2px top-100 bottom-n100 bg-primary rounded"></span>
                            </a>
                        </li>
                        <li class="nav-item " role="presentation">
                            <a class="nav-link btn btn-color-muted border-0 h-100 px-0 nav-tab"  data-hub="others"  data-bs-toggle="pill" href="#tab_content_3" aria-selected="false" role="tab" tabindex="-1">
                                <span class="nav-text fw-bold fs-4 mb-3">
                                    Others <span class="others-count d-none">0</span>
                                </span>
                                <span class="bullet-custom position-absolute z-index-2 w-100 h-2px top-100 bottom-n100 bg-primary rounded"></span>
                            </a>
                        </li>
                    </ul>
                @if ($data['status'] ==2)
                    <div class="card-toolbar">
                        <button class="btn btn-sm btn-light-primary more-actions" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                            Actions
                            <i class="ki-duotone ki-down ms-1"></i>
                        </button>
                        <div class="menu menu-sub  more-actions-menu menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-semibold w-200px py-4"
                            data-kt-menu="true">
                            <div class="menu-item px-3">
                                <div class="menu-content text-muted pb-2 px-3 fs-7 text-uppercase">
                                    More Actions
                                </div>
                            </div>

                            @if ($data['status'] ==2)
                                <div class="menu-item px-3">
                                    <a href="#" class="menu-link px-3" data-bs-toggle="modal" data-bs-target="#modal_add_dealer_unit">
                                        Add New Unit
                                    </a>
                                </div>
                                @if($data['plan_type'] == 1)
                                    <div class="menu-item px-3">
                                        <a href="{{ route('cluster_b.download_tmp', ['id' => $data['encrypted_id'] ]) }}" class="menu-link px-3 download_tmp">
                                        Download TMP
                                        </a>
                                    </div>
                                    <div class="menu-item px-3">
                                        <a href="{{ route('cluster_b.download_vismin', ['id' => $data['encrypted_id'] ]) }}" class="menu-link px-3 download_vismin">
                                            Download Vismin
                                        </a>
                                    </div>
                                @endif
                                @if ($data['plan_type'] == 2 && count($data['filenames']) < 2)
                                    <div class="menu-item px-3">
                                        <a href="#" class="menu-link px-3" data-bs-toggle="modal" data-bs-target="#modal_fhauling_plan">
                                            Upload Hauling Plan
                                        </a>
                                    </div>
                                @elseif ($data['plan_type'] == 2 && count($data['filenames']) >=2)
                                    <div class="separator mt-3 opacity-75"></div>
                                    <div class="menu-item px-3 mt-2">
                                        <a href="#" class="menu-link px-3 reupload" form_id="form_hauling_plan" modal-title="Re-Upload Hauling Plan" modal-id="#modal_fhauling_plan" rq-url="/tms/cco-b/planner/haulage_info/reupload_hauling_plan">
                                            Re-Upload Hauling Plan
                                        </a>
                                    </div>
                                @endif
                                @if($data['plan_type'] == 1)
                                    <div class="separator mt-3 opacity-75"></div>
                                    <div class="menu-item px-3 mt-2">
                                        <a href="#" class="menu-link px-3" data-bs-toggle="modal" data-bs-target="#modal_upload_masterlist">
                                            Upload TMP
                                        </a>
                                    </div>
                                    <div class="menu-item px-3">
                                        <a href="#" class="menu-link px-3" data-bs-toggle="modal" data-bs-target="#modal_upload_vismin">
                                            Upload Vismin
                                        </a>
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>
                @endif
                </div>
                <div class="card-body card-scroll h-1000px p-0 px-2 rounded-0 bg-light-secondary">
                    <div class="position-relative w-100 mb-5 mt-5">
                        <i class="ki-duotone ki-magnifier fs-2 text-muted position-absolute top-50 translate-middle ms-8">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                        <input type="text" class="form-control fs-4 ps-14 text-gray-700 placeholder-gray-500 search-allocation" name="search" value="" placeholder="Search here . . .">
                    </div>
                    <div class="tab-content">
                        <div class="tab-pane active show" id="tab_content_1" role="tabpanel">
                            <div class="table-responsive pb-10">
                                <div class="svc_content for_allocation d-none">

                                </div>
                                <div class="empty_svc d-none">
                                    <div class="card-px text-center pt-20 pb-15">
                                        <h2 class="fs-5 mb-0">No Units Found</h2>
                                        <p class="text-gray-500 fs-6 fw-semibold py-7">
                                            <em> Click the button <span class="text-primary">Actions</span> and <br> select Upload @if ($data['plan_type'] == 2) Hauling Plan @else Masterlist @endif</em>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="tab_content_2" role="tabpanel">
                            <div class="table-responsive pb-10">
                                <div class="bvc_content for_allocation d-none">

                                </div>
                                <div class="empty_bvc d-none">
                                    <div class="card-px text-center pt-20 pb-15">
                                        <h2 class="fs-5 mb-0">No Units Found</h2>
                                        <p class="text-gray-500 fs-6 fw-semibold py-7">
                                            <em> Click the button <span class="text-primary">Actions</span> and <br> select Upload @if ($data['plan_type'] == 2) Hauling Plan @else Masterlist @endif</em>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="tab_content_3" role="tabpanel">
                            <div class="others_content for_allocation d-none">

                            </div>
                            <div class="empty_others d-none">
                                <div class="card-px text-center pt-20 pb-15">
                                    <h2 class="fs-5 mb-0">No Units Found</h2>
                                    <p class="text-gray-500 fs-6 fw-semibold py-7">
                                        <em> Click the button <span class="text-primary">Actions</span> and <br> select <a href="javascript:;" data-bs-toggle="modal" data-bs-target="#modal_add_dealer_unit" class="text-primary"> Add New Unit </a></em>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('layout.planner.shared.modal.modal_upload_hauling_plan')
</div>

