<div class="haulage_info_page">

    <ul class="nav nav-custom nav-tabs nav-line-tabs nav-line-tabs-2x border-0 fs-4 fw-semibold mb-5" role="tablist">
        <li class="nav-item" role="presentation">
            <a class="nav-link text-active-primary tab pb-4" data-bs-toggle="tab" href="#tractor_driver_details_tab" aria-selected="true" role="tab">
                Tractor Trailer / Driver Details
            </a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link text-active-primary tab pb-4" data-bs-toggle="tab" href="#dispatching_tab" aria-selected="false" tabindex="-1" role="tab">Dispatching</a>
        </li>
    </ul>
    @if($pendingAttendance)
        <div class="alert alert-dismissible bg-light-info d-flex flex-column flex-sm-row p-5 mb-5">
            <i class="ki-duotone ki-notification-bing fs-2hx text-info me-4 mb-5 mb-sm-0"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
            <div class="d-flex flex-column pe-0 pe-sm-10">
                <h4 class="fw-semibold">Important Notice</h4>
                <span>There is a pending attendance on other Hauling Plan. Finish the Attendance first.</span>
            </div>

            <button type="button" class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto" data-bs-dismiss="alert">
                <i class="ki-duotone ki-cross fs-1 text-info"><span class="path1"></span><span class="path2"></span></i>
            </button>
        </div>
    @endif
    <div class="finalize-notif"></div>
    <div class="tab-content">
        <div class="tab-pane fade" id="tractor_driver_details_tab" role="tab-panel">
            <div class="card pt-0 mb-6 mb-xl-9">
                <div class="card-header border-1 py-3">
                    <div class="card-title">
                        <div class="d-flex align-items-center position-relative my-1">
                            <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5">
                                <span class="path1"></span><span class="path2"></span>
                            </i>
                            <input type="text" id="" class="form-control form-control-solid w-250px ps-13 search" placeholder="Search here . . .">
                        </div>
                    </div>
                    <div class="d-flex flex-stack flex-wrap gap-4">
                        <div class="d-flex align-items-center fw-bold">
                            <div class="text-muted fs-7 me-2">Status:</div>
                            <select class="form-select form-select-transparent text-dark fs-7 lh-1 fw-bold py-0 ps-1 w-auto filter_table" data-control="select2"
                            data-hide-search="true" data-dropdown-css-class="w-150px" data-placeholder="Select a batch" data-minimum-results-for-search="Infinity" name="filter_status">
                            <option value="all">Show All</option>
                            <option value="1">Available</option>
                            <option value="2">On Trip</option>
                            <option value="3">Absent Driver</option>
                            <option value="4">No Driver</option>
                            <option value="5">For PMS</option>
                            <option value="6">On-Going PMS</option>
                            <option value="7">Trailer Repair</option>
                            <option value="8">Tractor Repair</option>
                            <option value="9">Rehab/Recon</option>
                            <option value="10">Others</option>
                            </select>
                        </div>
                        <div class="d-flex align-items-center fw-bold">
                            <div class="text-muted fs-7 me-2">Attendance:</div>
                            <select class="form-select form-select-transparent text-dark fs-7 lh-1 fw-bold py-0 ps-1 w-auto filter_table" data-control="select2"
                            data-hide-search="true" data-dropdown-css-class="w-150px" data-placeholder="Select a batch" data-minimum-results-for-search="Infinity"  name="filter_attendance">
                            <option value="all">Show All</option>
                            <option value="present"> Present</option>
                            <option value="absent"> Absent</option>
                            </select>
                        </div>
                        <div class="card-toolbar">
                            @if($data->is_final_attendance == 0 && !$pendingAttendance)
                                @if($data->haulage_attendance->isEmpty())
                                    <button class="btn btn-sm btn-light-primary start-attendance" rq-url="start_attendance">
                                        Start Attendance
                                    </button>
                                @else
                                <button class="btn btn-sm btn-light-success finish-attendance" rq-url="finish_attendance">
                                    Finish Attendance
                                </button>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="dataTables_wrapper dt-bootstrap4 no-footer">
                        <div class="table-responsive" id="tractor_trailer_driver_wrapper" data-url=@if($data->haulage_attendance->isEmpty())"tms/cco-b/dispatcher/tractor_trailer_driver/dt" @else "tms/cco-b/dispatcher/haulage_attendance/dt" @endif>
                            <table class="table align-middle table-row-bordered fs-6 gy-5 dataTable no-footer"
                                id="tractor_trailer_driver_table">
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="dispatching_tab" role="tab-panel">
            @if ($data->status ==1)
                <div class="alert alert-dismissible bg-light-primary d-flex flex-column flex-sm-row p-5 mb-5 complete-haulage">
                    <i class="ki-duotone ki-notification-bing fs-2hx text-primary me-4 mb-5 mb-sm-0"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                    <div class="d-flex flex-column pe-0 pe-sm-10">
                        <h4 class="fw-semibold">Hauling Plan Complete</h4>
                        <span>This is to notify you that '{{ optional($data->updated_by)->fullname() ?? '' }}' set the status of hauling plan as complete.</span>
                    </div>

                    <button type="button" class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto" data-bs-dismiss="alert">
                        <i class="ki-duotone ki-cross fs-1 text-primary"><span class="path1"></span><span class="path2"></span></i>
                    </button>
                </div>
            @endif
            <div class="page-alert"> </div>
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
                                        name="batch">
                                            <option value="1">Batch 1</option>
                                            <option value="2">Batch 2</option>
                                        </select>
                                    </div>
                                    @if ($data->status ==2)
                                        <button class="btn btn-sm btn-light-success finalize-dispatch d-none" data-status="1" rq-url="/tms/cco-b/planner/haulage_info/finalize_plan">
                                            Finalize Dispatch
                                        </button>
                                    @elseif ($data->status ==1)
                                        <div class="card-toolbar">
                                            <button class="btn btn-sm btn-light-primary export-menu" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                                More Actions
                                                <i class="ki-duotone ki-down ms-1"></i>
                                            </button>
                                            <div class="menu menu-sub  more-actions-menu menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-semibold w-200px py-4"
                                                data-kt-menu="true">
                                                <div class="menu-item px-3">
                                                    <div class="menu-content text-muted pb-2 px-3 fs-7 text-uppercase">
                                                        More Actions
                                                    </div>
                                                </div>
                                                <div class="menu-item px-3">
                                                    <a href="#" class="menu-link px-3" data-bs-toggle="modal" data-bs-target="#">
                                                        Print Report
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="card-body card-scroll h-1000px bg-light-secondary p-5">
                            <div class="hauling_list">
                            </div>
                            <div class="empty_hauling_list">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12 col-xl-3">
                    <div class="card pt-0 mb-6 mb-xl-9 for_allocation_card">
                        <div class="card-header position-relative min-h-50px border-bottom-2 py-3">
                            <ul class="nav nav-pills nav-pills-custom gap-7" role="tablist">
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
                            </ul>
                        @if ($data->status ==2)
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

                                    @if ($data->status ==2)
                                        <div class="menu-item px-3">
                                            <a href="#" class="menu-link px-3" data-bs-toggle="modal" data-bs-target="#modal_add_dealer_unit">
                                                Add New Unit
                                            </a>
                                        </div>
                                        @if ($data->plan_type == 2 && count($data->filenames) < 2)
                                            <div class="menu-item px-3">
                                                <a href="#" class="menu-link px-3" data-bs-toggle="modal" data-bs-target="#modal_fhauling_plan">
                                                    Upload Hauling Plan
                                                </a>
                                            </div>
                                        @elseif ($data->plan_type == 2 && count($data->filenames) >=2)
                                            <div class="separator mt-3 opacity-75"></div>
                                            <div class="menu-item px-3 mt-2">
                                                <a href="#" class="menu-link px-3 reupload" form_id="form_hauling_plan" modal-title="Re-Upload Hauling Plan" modal-id="#modal_fhauling_plan" rq-url="/tms/cco-b/planner/haulage_info/reupload_hauling_plan">
                                                    Re-Upload Hauling Plan
                                                </a>
                                            </div>
                                        @endif
                                        @if($data->plan_type == 1 && count($data->filenames) <1)
                                            <div class="menu-item px-3">
                                                <a href="#" class="menu-link px-3" data-bs-toggle="modal" data-bs-target="#modal_upload_masterlist">
                                                    Upload Masterlist
                                                </a>
                                            </div>
                                        @elseif($data->plan_type == 1 && count($data->filenames) >=1)
                                            <div class="separator mt-3 opacity-75"></div>
                                            <div class="menu-item px-3 mt-2">
                                                <a href="#" class="menu-link px-3 reupload" form_id="form_masterlist" modal-title="Re-Upload Masterlist" modal-id="#modal_upload_masterlist" rq-url="/tms/cco-b/planner/haulage_info/reupload_masterlist">
                                                    Re-Upload Masterlist
                                                </a>
                                            </div>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        @endif
                        </div>
                        <div class="card-body card-scroll h-1000px p-0 rounded-0 bg-light-secondary">
                            <div class="tab-content ">
                                <div class="tab-pane active show" id="tab_content_1" role="tabpanel">
                                    <div class="table-responsive pb-10">
                                        <div class="svc_content for_allocation d-none">

                                        </div>
                                        <div class="empty_svc d-none">
                                            <div class="card-px text-center pt-20 pb-15">
                                                <h2 class="fs-5 mb-0">No Units Found</h2>
                                                <p class="text-gray-500 fs-6 fw-semibold py-7">
                                                    <em> Click the button <span class="text-primary">Actions</span> and <br> select Upload @if ($data->plan_type == 2) Hauling Plan @else Masterlist @endif</em>
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
                                                    <em> Click the button <span class="text-primary">Actions</span> and <br> select Upload @if ($data->plan_type == 2) Hauling Plan @else Masterlist @endif</em>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('layout.dispatcher.shared.resources.modal.modal_add_tractor_trailer')

</div>

