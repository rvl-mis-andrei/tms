<div class="haulage_info_page d-none">
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
                                data-hide-search="true" data-dropdown-css-class="w-150px" data-placeholder="Select a batch" data-minimum-results-for-search="Infinity"
                                 name="batch">
                                    <option value="1">Batch 1</option>
                                    <option value="2">Batch 2</option>
                                </select>

                            </div>
                            <button class="btn btn-sm btn-light-info add-block">
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
                                    <a href="#" class="menu-link px-3 save" data-status="2">
                                        Save Progress
                                    </a>
                                </div>
                                <div class="menu-item px-3">
                                    <a href="#" class="menu-link px-3 save" data-status="1">
                                        Finalize Planning
                                    </a>
                                </div>
                                <div class="separator"></div>
                                <div class="menu-item px-3">
                                    <div class="menu-content px-3">
                                        <label class="form-check form-switch form-check-custom form-check-solid">
                                            <input class="form-check-input w-30px h-20px auto-save" data-status="2" data-action="auto-save" type="checkbox" value="1" checked="checked" name="notifications">
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
                       
                    </div>
                    <div class="empty_hauling_list d-none">
                        <div class="card-px text-center pt-20 pb-15">
                            <h2 class="fs-2x mb-0">No Truck Trips Found</h2>
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
                            <a class="nav-link btn btn-color-muted border-0 h-100 px-0 active nav-tab" data-bs-toggle="pill" data-hub="svc" href="#tab_content_1" aria-selected="true" role="tab">
                                <span class="nav-text fw-bold fs-4 mb-3">
                                    SVC
                                </span>
                                <span class="bullet-custom position-absolute z-index-2 w-100 h-2px top-100 bottom-n100 bg-primary rounded"></span>
                            </a>
                        </li>
                        <li class="nav-item mx-0 px-0 w-50" role="presentation">
                            <a class="nav-link btn btn-color-muted border-0 h-100 px-0 nav-tab"  data-hub="bvc"  data-bs-toggle="pill" href="#tab_content_2" aria-selected="false" role="tab" tabindex="-1">
                                <span class="nav-text fw-bold fs-4 mb-3">
                                    BVC
                                </span>
                                <span class="bullet-custom position-absolute z-index-2 w-100 h-2px top-100 bottom-n100 bg-primary rounded"></span>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="card-body card-scroll h-1000px p-0 rounded-0 bg-light-secondary">
                    <div class="tab-content">
                        <div class="tab-pane blockui active show" id="tab_content_1" role="tabpanel">
                            <div class="table-responsive pb-10">
                                <div class="svc_content d-none">

                                </div>
                                <div class="empty_svc d-none">
                                    <div class="card-px text-center pt-20 pb-15">
                                        <h2 class="fs-5 mb-0">No Data Found</h2>
                                        <p class="text-gray-500 fs-6 fw-semibold py-7">
                                            <em> You can manually add units for allocation or upload a materlist</em>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane blockui" id="tab_content_2" role="tabpanel">
                            <div class="table-responsive pb-10">
                                <div class="bvc_content">

                                </div>
                                <div class="empty_bvc">
                                    <div class="card-px text-center pt-20 pb-15">
                                        <h2 class="fs-5 mb-0">No Underload Found</h2>
                                        <p class="text-gray-500 fs-6 fw-semibold py-7">
                                            <em> You can manually add units for underload or finalize the hauling plan to determine the underload</em>
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
    @include('layout.planner.shared.modal.modal_upload_hauling_plan')
</div>

