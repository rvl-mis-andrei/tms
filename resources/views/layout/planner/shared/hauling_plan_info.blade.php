<div class="haulage_info_page">
    <div class="card">
        <div class="card-header border-0 collapsible collapsed">
            <div class="d-flex justify-content-between flex-wrap mb-0">
                <div class="d-flex align-items-center mb-0">
                    <h2 class="card-title fw-bold">Final Hauling Plan - August 14, 2024</h2>
                    <span class="badge badge-light-success me-auto">On-Going</span>
                </div>
            </div>
            <div class="card-toolbar">
                <div class="rotate btn btn-icon btn-sm btn-active-color-info" data-kt-rotate="true" data-bs-toggle="collapse" data-bs-target="#kt_docs_card_collapsible">
                    <i class="ki-duotone ki-down fs-1  rotate-n180"></i>
                </div>
                <div class="me-0">
                    <button class="btn btn-sm btn-icon btn-bg-light btn-active-color-primary"
                        data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                        <i class="ki-solid ki-dots-horizontal fs-2x"></i>
                    </button>
                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-semibold w-200px py-3"
                        data-kt-menu="true">
                        <div class="menu-item px-3">
                            <div class="menu-content text-muted pb-2 px-3 fs-7 text-uppercase">
                                More Actions
                            </div>
                        </div>

                        <div class="menu-item px-3">
                            <a href="#" class="menu-link px-3">
                                Edit Details
                            </a>
                        </div>
                        <div class="separator"></div>
                        <div class="menu-item px-3 my-1">
                            <a href="#" class="menu-link px-3 text-danger">
                                Delete Hauling Plan
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="kt_docs_card_collapsible" class="collapse">
            <div class="card-body pt-0 pb-0">
                <div class="d-flex flex-wrap flex-sm-nowrap mb-6">
                    <div class="flex-grow-1">
                        <div class="d-flex flex-wrap justify-content-start">
                            <div class="d-flex flex-wrap">
                                <div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                                    <div class="fs-6 text-muted pb-1">Planning Date</div>
                                    <div class="d-flex align-items-center">
                                        <div class="">29 Jan, 2023</div>
                                    </div>
                                </div>
                                <div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                                    <div class=" fs-6 text-muted pb-1">Created By</div>
                                    <div class="d-flex align-items-center">
                                        Jhon Mark Cruz
                                    </div>
                                </div>
                            </div>
                            <div class="symbol-group symbol-hover mb-3">
                                <div class="symbol symbol-35px symbol-circle" data-bs-toggle="tooltip" title="Alan Warden">
                                    <span class="symbol-label bg-warning text-inverse-warning fw-bold">A</span>
                                </div>
                                <div class="symbol symbol-35px symbol-circle" data-bs-toggle="tooltip" title="Alan Warden">
                                    <span class="symbol-label bg-info text-inverse-warning fw-bold">D</span>
                                </div>
                                <div class="symbol symbol-35px symbol-circle" data-bs-toggle="tooltip" title="Alan Warden">
                                    <span class="symbol-label bg-success text-inverse-warning fw-bold">B</span>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer py-0 my-0">

                <ul class="nav nav-stretch nav-line-tabs nav-line-tabs-2x border-transparent fs-5 fw-bold">
                    <li class="nav-item">
                        <a class="nav-link text-active-primary py-5 me-6 nav-tab" href="javascript:;" data-tab="tab-content-1">Overview</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-active-primary py-5 me-6 nav-tab" href="javascript:;" data-tab="tab-content-2">Planning</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-active-primary py-5 me-6 nav-tab" href="javascript:;" data-tab="tab-content-3">Activity Logs</a>
                    </li>
                </ul>
            </div>
    </div>

    <div class="my-7">
        {{-- OVERVIEW --}}
        <div class="overview d-none tab-content tab-content-1">

            <div class="card">
                <div class="card-header">
                    <div class="card-title fs-3 fw-bold">Overview</div>
                </div>
                <form id="kt_project_settings_form" class="form fv-plugins-bootstrap5 fv-plugins-framework" novalidate="novalidate">
                    <div class="card-body p-9">
                        <div class="row mb-8">
                            <div class="col-xl-3">
                                <div class="fs-6 fw-semibold mt-2 mb-3">Hauling Plan</div>
                            </div>

                            <div class="col-xl-9 fv-row fv-plugins-icon-container">
                                <input type="text" class="form-control form-control-solid" name="name" value="9 Degree Award">
                                <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                </div>
                            </div>
                        </div>
                        <div class="row mb-8">
                            <div class="col-xl-3">
                                <div class="fs-6 fw-semibold mt-2 mb-3">Remarks</div>
                            </div>
                            <div class="col-xl-9 fv-row fv-plugins-icon-container">
                                <textarea name="description" class="form-control form-control-solid h-100px"></textarea>
                                <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                </div>
                            </div>
                        </div>
                        <div class="row mb-8">
                            <div class="col-xl-3">
                                <div class="fs-6 fw-semibold mt-2 mb-3">Planning Date</div>
                            </div>
                            <div class="col-xl-9 fv-row fv-plugins-icon-container">
                                <div class="position-relative d-flex align-items-center">
                                    <i class="ki-outline ki-calendar-8 position-absolute ms-4 mb-1 fs-2"></i> <input
                                        class="form-control form-control-solid ps-12 flatpickr-input" name="date"
                                        placeholder="Pick a date" id="kt_datepicker_1" type="text">
                                </div>
                                <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-3">
                                <div class="fs-6 fw-semibold mt-2 mb-3">Status</div>
                            </div>
                            <div class="col-xl-9">
                                <div class="form-check form-switch form-check-custom form-check-solid">
                                    <input class="form-check-input" type="checkbox" value="" id="status" name="status"
                                        checked="checked">
                                    <label class="form-check-label  fw-semibold text-gray-500 ms-3" for="status">
                                        Active
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-end py-6 px-9">
                        <button type="reset" class="btn btn-light btn-active-light-primary me-2">Discard</button>
                        <button type="submit" class="btn btn-primary" id="kt_project_settings_submit">Save Changes</button>
                    </div>
                    <input type="hidden">
                </form>
            </div>

        </div>

        {{-- PLANNING --}}
        <div class="planning d-none tab-content tab-content-2">
            <div class="row">
                <div class="col-lg-12 col-xl-9">
                    <div class="card pt-4 mb-6 mb-xl-9 ">
                        <div class="card-header border-0 ">
                            <div class="card-title flex-column">
                                <h3 class="fw-bold mb-1">Hauling Plan</h3>
                                <div class="fs-6 d-flex text-gray-500 fs-6 fw-semibold">
                                    <div class="d-flex align-items-center me-6">
                                        <span class="menu-bullet d-flex align-items-center me-2">
                                            <span class="bullet bg-success"></span>
                                        </span>
                                        Units : 250
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <span class="menu-bullet d-flex align-items-center me-2">
                                            <span class="bullet bg-primary"></span>
                                        </span>
                                        Trips : 50
                                    </div>
                                </div>
                            </div>
                            <div class="card-toolbar">
                                <div class="d-flex flex-stack flex-wrap gap-4">

                                    <div class="d-flex align-items-center fw-bold">
                                        <div class="text-muted fs-7 me-2">Batch:</div>
                                        <select class="form-select form-select-transparent text-dark fs-7 lh-1 fw-bold py-0 ps-3 w-auto" data-control="select2"
                                        data-hide-search="true" data-dropdown-css-class="w-150px" data-placeholder="Select a batch" name="batch">
                                            <option value="1">Batch 1</option>
                                            <option value="2">Batch 2</option>
                                        </select>

                                    </div>
                                    <button class="btn btn-sm btn-light-info">
                                        Add Block
                                        <i class="ki-duotone ki-plus ms-1"></i>
                                    </button>
                                    <button class="btn btn-sm btn-light-primary" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                        More Actions
                                        <i class="ki-duotone ki-down ms-1"></i>
                                    </button>
                                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-semibold w-200px py-3 pb-1"
                                        data-kt-menu="true">
                                        <div class="menu-item px-3">
                                            <div class="menu-content text-muted pb-2 px-3 fs-7 text-uppercase">
                                                More Actions
                                            </div>
                                        </div>
                                        <div class="menu-item px-3">
                                            <a href="#" class="menu-link px-3">
                                                Save Progress
                                            </a>
                                        </div>
                                        <div class="menu-item px-3">
                                            <a href="#" class="menu-link px-3">
                                                Finalize Planning
                                            </a>
                                        </div>
                                        <div class="separator"></div>
                                        <div class="menu-item px-3">
                                            <div class="menu-content px-3">
                                                <label class="form-check form-switch form-check-custom form-check-solid">
                                                    <input class="form-check-input w-30px h-20px" type="checkbox" value="1" checked="checked" name="notifications">
                                                    <span class=" ms-3 fs-7">
                                                        Auto Save
                                                    </span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="card-body card-scroll h-1000px bg-light-secondary">
                            <div class="hauling_list d-none">
                                <div class="card">
                                    <div class="card-header collapsible cursor-pointer rotate bg-light-secondary" data-bs-toggle="collapse" data-bs-target="#kt_docs_card_collapsible">
                                        <h3 class="card-title">Title</h3>
                                        <div class="card-toolbar">
                                            <div class="rotate btn btn-icon btn-sm btn-active-color-info" data-kt-rotate="true" >
                                                <i class="ki-duotone ki-down fs-1  rotate-n180"></i>
                                            </div>
                                            <a href="#" class="btn btn-icon btn-sm btn-active-color-danger" data-kt-card-action="remove" data-kt-card-confirm="true"
                                                data-kt-card-confirm-message="Are you sure to remove this card ?" data-bs-toggle="tooltip" title="Remove card" data-bs-dismiss="click">
                                                <i class="ki-duotone ki-cross fs-1">
                                                    <span class="path1"></span>
                                                    <span class="path2"></span>
                                                </i>
                                            </a>
                                        </div>
                                    </div>
                                    <div id="kt_docs_card_collapsible" class="collapse show">
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_customers_table">
                                                    <thead>
                                                        <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                                                            <th class="min-w-125px">#</th>
                                                            <th class="min-w-125px">Dealer</th>
                                                            <th class="min-w-125px">Cs No.</th>
                                                            <th class="min-w-125px">Model</th>
                                                            <th class="min-w-125px">Color</th>
                                                            <th class="min-w-125px">Invoice Date</th>
                                                            <th class="min-w-125px">Location</th>
                                                            <th class="min-w-125px">Inspection TIme</th>
                                                            <th class="text-end min-w-70px">Actions</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="fs-6 fw-semibold text-gray-600">
                                                    </tbody>
                                                    <tfoot></tfoot>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="empty_hauling_list d-none">
                                <div class="card-px text-center pt-20 pb-15">
                                    <h2 class="fs-2x mb-0">No Hauling Plan Found</h2>
                                    <p class="text-gray-500 fs-4 fw-semibold py-7">
                                        Click on the below buttons to upload a <br>
                                        <span class="text-primary">Masterlist</span>  or
                                        <span class="text-info">Final Hauling Plan.</span>
                                    </p>
                                    <div class="d-flex justify-content-center gap-2 gap-lg-3">
                                        <button class="btn btn-sm btn-primary er fs-6" data-bs-toggle="modal" data-bs-target="#modal_upload_masterlist">
                                            Upload Masterlist
                                        </button>
                                        <button class="btn btn-sm btn-info er fs-6" data-bs-toggle="modal" data-bs-target="#modal_fhauling_plan">
                                            Upload Final Hauling Plan
                                        </button>
                                    </div>
                                </div>
                                <div class="text-center pb-15 px-5">
                                    <img src="{{ asset('assets/media/illustrations/sketchy-1/16.png') }}" alt="" class="mw-100 h-200px h-sm-325px">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12 col-xl-3">
                    <div class="card">
                        <div class="card-header position-relative min-h-50px border-bottom-2">
                            <ul class="nav nav-pills nav-pills-custom d-flex position-relative w-100" role="tablist">
                                <li class="nav-item mx-0 p-0 w-50" role="presentation">
                                    <a class="nav-link btn btn-color-muted border-0 h-100 px-0 active" data-bs-toggle="pill" id="kt_forms_widget_1_tab_1" href="#kt_forms_widget_1_tab_content_1" aria-selected="true" role="tab">
                                        <span class="nav-text fw-bold fs-4 mb-3">
                                            For Allocation
                                        </span>
                                        <span class="bullet-custom position-absolute z-index-2 w-100 h-2px top-100 bottom-n100 bg-primary rounded"></span>
                                    </a>
                                </li>
                                <li class="nav-item mx-0 px-0 w-50" role="presentation">
                                    <a class="nav-link btn btn-color-muted border-0 h-100 px-0" data-bs-toggle="pill" id="kt_forms_widget_1_tab_2" href="#kt_forms_widget_1_tab_content_2" aria-selected="false" role="tab" tabindex="-1">
                                        <span class="nav-text fw-bold fs-4 mb-3">
                                            Underload
                                        </span>
                                        <span class="bullet-custom position-absolute z-index-2 w-100 h-2px top-100 bottom-n100 bg-primary rounded"></span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body card-scroll h-1000px p-0 rounded-0">
                            <div class="accordion" id="kt_accordion_1">
                                <div class="accordion-item rounded-0">
                                    <h2 class="accordion-header rounded-0" id="kt_accordion_1_header_1">
                                        <button class="accordion-button fs-4 fw-semibold rounded-0" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#kt_accordion_1_body_1"
                                            aria-expanded="true" aria-controls="kt_accordion_1_body_1">
                                            Accordion Item #1
                                        </button>
                                    </h2>
                                    <div id="kt_accordion_1_body_1" class="accordion-collapse collapse show"
                                        aria-labelledby="kt_accordion_1_header_1" data-bs-parent="#kt_accordion_1">
                                        <div class="accordion-body">
                                            ...
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ACTIVITY LOGS --}}
    </div>
    @include('layout.planner.shared.modal.modal_upload_hauling_plan')
</div>


