<div class="page-tractor-trailer">
    <div class="d-flex flex-column flex-lg-row">
        <div class="d-none d-lg-flex flex-column flex-lg-row-auto w-100 w-lg-275px">
            <div class="card card-flush mb-0">
                <div class="card-body pt-5">
                    <ul class="nav nav-tabs nav-pills border-0 flex-row flex-md-column me-5 mb-3 mb-md-0 fs-6">
                        <li>
                            <div class="menu-item">
                                <h4 class="menu-content text-muted mb-0 fs-7 text-uppercase">
                                    Menu Options
                                </h4>
                            </div>
                        </li>
                        <li class="nav-item w-md-200px me-0 mb-2">
                            <a class="nav-link text-dark text-active-light fw-bold menu-tab" data-tab="tractor_trailer" data-bs-toggle="tab" href="#tab1">
                                Tractor Trailer
                            </a>
                        </li>
                        <li class="nav-item w-md-200px me-0 mb-2">
                            <a class="nav-link text-dark text-active-light fw-bold menu-tab" data-tab="tractor" data-bs-toggle="tab" href="#tab2">
                                Tractor List
                            </a>
                        </li>
                        <li class="nav-item w-md-200px me-0 mb-2">
                            <a class="nav-link text-dark text-active-light fw-bold menu-tab" data-tab="trailer" data-bs-toggle="tab" href="#tab3">
                                Trailer List
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="flex-xl-row-fluid ms-5">
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade tractor_trailer" id="tab1" role="tabpanel">
                    <div class="card">
                        <div class="card-header border-1 py-3">
                            <div class="card-title">
                                <div class="d-flex align-items-center position-relative my-1">
                                    <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5">
                                        <span class="path1"></span><span class="path2"></span>
                                    </i>
                                    <input type="text" data-kt-user-table-filter="search" id="" class="form-control form-control-solid w-250px ps-13 search"
                                    placeholder="Search here . . .">
                                </div>
                            </div>
                            <div class="card-toolbar">
                                <div class="d-flex justify-content-end" data-kt-user-table-toolbar="base">
                                    <div class="w-150px me-3">
                                        <!--begin::Select2-->
                                        <select class="form-select form-select-solid filter_table" id="" name="filter_tractor_trailer_status"
                                        data-minimum-results-for-search="Infinity" data-control="select2" data-hide-search="true" data-placeholder="Status">
                                            <option value="all">Show All</option>
                                            <option value="1" >Available</option>
                                            <option value="2" >On Trip</option>
                                            <option value="3" >Absent Driver</option>
                                            <option value="4" >No Driver</option>
                                            <option value="5" >For PMS</option>
                                            <option value="6" >On-Going PMS</option>
                                            <option value="7" >Trailer Repair</option>
                                            <option value="8" >Tractor Repair</option>
                                            <option value="9" >Rehab/Recon</option>
                                            <option value="10" >Others</option>
                                        </select>
                                        <!--end::Select2-->
                                    </div>
                                    <button type="button" class="btn btn-light-primary me-3 hover-elevate-up" data-bs-toggle="modal" data-bs-target="#kt_modal_export_users">
                                        <i class="ki-duotone ki-exit-up fs-2">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                        Export
                                    </button>
                                    <button type="button" class="btn btn-primary hover-elevate-up add-tractor-trailer">
                                        <i class="ki-duotone ki-plus fs-2"></i>
                                        Add New Tractor Trailer
                                    </button>
                                </div>
                                <div class="d-flex justify-content-end align-items-center d-none" data-kt-user-table-toolbar="selected">
                                    <div class="fw-bold me-5">
                                        <span class="me-2" data-kt-user-table-select="selected_count"></span> Selected
                                    </div>

                                    <button type="button" class="btn btn-danger" data-kt-user-table-select="delete_selected">
                                        Delete Selected
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="d-flex flex-column flex-xl-row p-7">
                                <div class="flex-lg-row-fluid">
                                <div class="dataTables_wrapper dt-bootstrap4 no-footer">
                                    <div class="table-responsive" id="tractor_trailer_wrapper">
                                        <table class="table table-striped align-middle table-row-dashed fs-6 gy-5 dataTable no-footer"
                                            id="tractor_trailer_table">
                                        </table>
                                    </div>
                                </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade tractor" id="tab2" role="tabpanel">
                    <div class="card">
                        <div class="card-header border-1 py-3">
                            <div class="card-title">
                                <div class="d-flex align-items-center position-relative my-1">
                                    <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5">
                                        <span class="path1"></span><span class="path2"></span>
                                    </i>
                                    <input type="text" data-kt-user-table-filter="search" id="" class="form-control form-control-solid w-250px ps-13 search"
                                    placeholder="Search here . . .">
                                </div>
                            </div>
                            <div class="card-toolbar">
                                <div class="d-flex justify-content-end" data-kt-user-table-toolbar="base">
                                    <div class="w-150px me-3">
                                        <!--begin::Select2-->
                                        <select class="form-select form-select-solid status filter_table" name="filter_tractor_status"
                                        data-minimum-results-for-search="Infinity" data-control="select2" data-hide-search="true" data-placeholder="Status">
                                            <option value="all">Show All</option>
                                            <option value="1">Available</option>
                                            <option value="2">Assigned</option>
                                        </select>
                                        <!--end::Select2-->
                                    </div>
                                    <button type="button" class="btn btn-light-primary me-3 hover-elevate-up" data-bs-toggle="modal" data-bs-target="#kt_modal_export_users">
                                        <i class="ki-duotone ki-exit-up fs-2">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                        Export
                                    </button>
                                    <button type="button" class="btn btn-primary hover-elevate-up"  data-bs-toggle="modal" data-bs-target="#modal_add_tractor">
                                        <i class="ki-duotone ki-plus fs-2"></i>
                                        Add New Tractor
                                    </button>
                                </div>
                                <div class="d-flex justify-content-end align-items-center d-none" data-kt-user-table-toolbar="selected">
                                    <div class="fw-bold me-5">
                                        <span class="me-2" data-kt-user-table-select="selected_count"></span> Selected
                                    </div>

                                    <button type="button" class="btn btn-danger" data-kt-user-table-select="delete_selected">
                                        Delete Selected
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="d-flex flex-column flex-xl-row p-7">
                                <div class="flex-lg-row-fluid">
                                <div class="dataTables_wrapper dt-bootstrap4 no-footer">
                                    <div class="table-responsive" id="tractor_wrapper">
                                        <table class="table table-striped table-sm align-middle table-row-dashed fs-6 gy-5 dataTable no-footer"
                                            id="tractor_table">
                                        </table>
                                    </div>
                                </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade trailer" id="tab3" role="tabpanel">
                    <div class="card">
                        <div class="card-header border-1 py-3">
                            <div class="card-title">
                                <div class="d-flex align-items-center position-relative my-1">
                                    <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5">
                                        <span class="path1"></span><span class="path2"></span>
                                    </i>
                                    <input type="text" data-kt-user-table-filter="search" id="" class="form-control form-control-solid w-250px ps-13 search"
                                    placeholder="Search here . . .">
                                </div>
                            </div>
                            <div class="card-toolbar">
                                <div class="d-flex justify-content-end" data-kt-user-table-toolbar="base">
                                    <div class="w-150px me-3">
                                        <!--begin::Select2-->
                                        <select class="form-select form-select-solid filter_table" id="" name="filter_trailer_status"
                                        data-minimum-results-for-search="Infinity" data-control="select2" data-hide-search="true" data-placeholder="Status">
                                            <option value="all">Show All</option>
                                            <option value="1" >Available</option>
                                            <option value="2" >Assigned</option>
                                        </select>
                                        <!--end::Select2-->
                                    </div>
                                    <button type="button" class="btn btn-light-primary me-3 hover-elevate-up" data-bs-toggle="modal" data-bs-target="#kt_modal_export_users">
                                        <i class="ki-duotone ki-exit-up fs-2">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                        Export
                                    </button>
                                    <button type="button" class="btn btn-primary hover-elevate-up" data-bs-toggle="modal" data-bs-target="#modal_add_trailer">
                                        <i class="ki-duotone ki-plus fs-2"></i>
                                        Add New Trailer
                                    </button>
                                </div>
                                <div class="d-flex justify-content-end align-items-center d-none" data-kt-user-table-toolbar="selected">
                                    <div class="fw-bold me-5">
                                        <span class="me-2" data-kt-user-table-select="selected_count"></span> Selected
                                    </div>

                                    <button type="button" class="btn btn-danger" data-kt-user-table-select="delete_selected">
                                        Delete Selected
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="d-flex flex-column flex-xl-row p-7">
                                <div class="flex-lg-row-fluid">
                                <div class="dataTables_wrapper dt-bootstrap4 no-footer">
                                    <div class="table-responsive" id="trailer_wrapper">
                                        <table class="table table-striped table-sm align-middle table-row-dashed fs-6 gy-5 dataTable no-footer"
                                            id="trailer_table">
                                        </table>
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
