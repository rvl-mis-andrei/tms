<div class="page-settings">
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
                            <a class="nav-link text-dark text-active-light fw-bold menu-tab" data-tab="garage" data-bs-toggle="tab" href="#tab1">
                                Garage
                            </a>
                        </li>
                        <li class="nav-item w-md-200px me-0 mb-2">
                            <a class="nav-link text-dark text-active-light fw-bold menu-tab" data-tab="location" data-bs-toggle="tab" href="#tab2">
                                Location
                            </a>
                        </li>
                        <li class="nav-item w-md-200px me-0 mb-2">
                            <a class="nav-link text-dark text-active-light fw-bold menu-tab" data-tab="trailer_type" data-bs-toggle="tab" href="#tab3">
                                Trailer Type
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="flex-xl-row-fluid ms-5">
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade garage" id="tab1" role="tabpanel">
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
                                        <select class="form-select form-select-solid filter_table" id="" name="filter_garage_status"
                                        data-minimum-results-for-search="Infinity" data-control="select2" data-hide-search="true" data-placeholder="Status">
                                            <option value="all">Show All</option>
                                            <option value="1">Active</option>
                                            <option value="2">Inactive</option>
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
                                    <button type="button" class="btn btn-primary hover-elevate-up add-garage" data-bs-toggle="modal" data-bs-target="#modal_add_garage">
                                        <i class="ki-duotone ki-plus fs-2"></i>
                                        Add New Garage
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
                                    <div class="table-responsive" id="garage_wrapper">
                                        <table class="table table-striped align-middle table-row-dashed fs-6 gy-5 dataTable no-footer"
                                            id="garage_table">
                                        </table>
                                    </div>
                                </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade location" id="tab2" role="tabpanel">
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
                                        <select class="form-select form-select-solid status filter_table" name="filter_location_status"
                                        data-minimum-results-for-search="Infinity" data-control="select2" data-hide-search="true" data-placeholder="Status">
                                            <option value="all">Show All</option>
                                            <option value="1">Active</option>
                                            <option value="2">Inactive</option>
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
                                    <button type="button" class="btn btn-primary hover-elevate-up"  data-bs-toggle="modal" data-bs-target="#modal_add_location">
                                        <i class="ki-duotone ki-plus fs-2"></i>
                                        Add New Location
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
                                    <div class="table-responsive" id="location_wrapper">
                                        <table class="table table-striped table-sm align-middle table-row-dashed fs-6 gy-5 dataTable no-footer"
                                            id="location_table">
                                        </table>
                                    </div>
                                </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade trailer_type" id="tab3" role="tabpanel">
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
                                        <select class="form-select form-select-solid filter_table" id="" name="filter_trailer_type_status"
                                        data-minimum-results-for-search="Infinity" data-control="select2" data-hide-search="true" data-placeholder="Status">
                                            <option value="all">Show All</option>
                                            <option value="1" >Active</option>
                                            <option value="2" >Inactive</option>
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
                                    <button type="button" class="btn btn-primary hover-elevate-up" data-bs-toggle="modal" data-bs-target="#modal_add_trailer_type">
                                        <i class="ki-duotone ki-plus fs-2"></i>
                                        Add New Trailer Type
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
                                    <div class="table-responsive" id="trailer_type_wrapper">
                                        <table class="table table-striped table-sm align-middle table-row-dashed fs-6 gy-5 dataTable no-footer"
                                            id="trailer_type_table">
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

{{-- MODAL ADD GARAGE --}}
<div class="modal fade" id="modal_add_garage" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered mw-600px">
        <div class="modal-content">
            <div class="modal-header justify-content-center" id="kt_modal_add_user_header">
                <div class="text-center">
                    <h1 class="mb-3 modal_title">New Garage</h1>
                    <div class="text-muted fs-5">Fill-up the form and click
                        <a href="javascript:;" class="fw-bolder link-primary">Submit</a>.
                    </div>
                </div>
            </div>
            <div class="modal-body px-5">
                <form id="form_add_garage" modal-id="#modal_add_garage" class="form fv-plugins-bootstrap5 fv-plugins-framework" action="/services/settings/garage/update">
                    <div class="d-flex flex-column px-5 px-lg-10" id="kt_modal_add_user_scroll" style="max-height: 670px;">
                        <div class="fv-row mb-7 fv-plugins-icon-container">
                            <label class="required fw-semibold fs-6 mb-2">Garage</label>
                            <input type="text" name="garage_name" class="form-control form-control-solid mb-3 mb-lg-0" data-id="" placeholder="Garage">
                            <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
                        </div>
                        <div class="d-flex  fv-row flex-column mb-8">
                            <label class="fs-6 fw-semibold mb-2">Remarks</label>
                            <textarea class="form-control form-control-solid" rows="3" name="remarks" placeholder="Remarks"></textarea>
                        </div>
                        <div class="fv-row mb-10 fv-plugins-icon-container d-none">
                            <label class="required fs-6 fw-semibold form-label mb-2">Status:</label>
                            <select name="status" data-control="select2" data-placeholder="Select an option"
                            data-minimum-results-for-search="Infinity" class="form-select form-select-solid fw-bold select2-hidden-accessible">
                                <option></option>
                                <option value="1" selected>Active</option>
                                <option value="2">Inactive</option>
                            </select>
                            <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer flex-center">
                <button type="button" modal-id="#modal_add_garage" data-id="" id="" class="btn btn-primary me-4 submit">
                    <span class="indicator-label">Submit</span>
                    <span class="indicator-progress">Please wait...
                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                </button>
                <button type="button" modal-id="#modal_add_garage" id="" class="btn btn-light me-3 cancel">Cancel</button>
            </div>
        </div>
    </div>
</div>

{{-- MODAL ADD LOCATION --}}
<div class="modal fade" id="modal_add_location" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered mw-600px">
        <div class="modal-content">
            <div class="modal-header justify-content-center" id="kt_modal_add_user_header">
                <div class="text-center">
                    <h1 class="mb-3 modal_title">New Location</h1>
                    <div class="text-muted fs-5">Fill-up the form and click
                        <a href="javascript:;" class="fw-bolder link-primary">Submit</a>.
                    </div>
                </div>
            </div>
            <div class="modal-body px-5">
                <form id="form_add_location" modal-id="#modal_add_location" class="form fv-plugins-bootstrap5 fv-plugins-framework" action="/services/settings/location/update">
                    <div class="d-flex flex-column px-5 px-lg-10" id="kt_modal_add_user_scroll" style="max-height: 670px;">
                        <div class="fv-row mb-7 fv-plugins-icon-container">
                            <label class="required fw-semibold fs-6 mb-2">Location</label>
                            <input type="text" name="location_name" class="form-control form-control-solid mb-3 mb-lg-0" data-id="" placeholder="Location">
                            <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
                        </div>
                        <div class="d-flex  fv-row flex-column mb-8">
                            <label class="fs-6 fw-semibold mb-2">Remarks</label>
                            <textarea class="form-control form-control-solid" rows="3" name="remarks" placeholder="Remarks"></textarea>
                        </div>
                        <div class="fv-row mb-10 fv-plugins-icon-container d-none">
                            <label class="required fs-6 fw-semibold form-label mb-2">Status:</label>
                            <select name="status" data-control="select2" data-placeholder="Select an option"
                            data-minimum-results-for-search="Infinity" class="form-select form-select-solid fw-bold select2-hidden-accessible">
                                <option></option>
                                <option value="1" selected>Active</option>
                                <option value="2">Inactive</option>
                            </select>
                            <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer flex-center">
                <button type="button" modal-id="#modal_add_location" data-id="" id="" class="btn btn-primary me-4 submit">
                    <span class="indicator-label">Submit</span>
                    <span class="indicator-progress">Please wait...
                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                </button>
                <button type="button" modal-id="#modal_add_location" id="" class="btn btn-light me-3 cancel">Cancel</button>
            </div>
        </div>
    </div>
</div>

{{-- MODAL ADD TRAILER TYPE --}}
<div class="modal fade" id="modal_add_trailer_type" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered mw-600px">
        <div class="modal-content">
            <div class="modal-header justify-content-center" id="kt_modal_add_user_header">
                <div class="text-center">
                    <h1 class="mb-3 modal_title">New Trailer Type</h1>
                    <div class="text-muted fs-5">Fill-up the form and click
                        <a href="javascript:;" class="fw-bolder link-primary">Submit</a>.
                    </div>
                </div>
            </div>
            <div class="modal-body px-5">
                <form id="form_add_trailer_type" modal-id="#modal_add_trailer_type" class="form fv-plugins-bootstrap5 fv-plugins-framework" action="/services/settings/trailer_type/update">
                    <div class="d-flex flex-column px-5 px-lg-10" id="kt_modal_add_user_scroll" style="max-height: 670px;">
                        <div class="fv-row mb-7 fv-plugins-icon-container">
                            <label class="required fw-semibold fs-6 mb-2">Trailer Type</label>
                            <input type="text" name="trailer_type_name" class="form-control form-control-solid mb-3 mb-lg-0" data-id="" placeholder="Trailer Type">
                            <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
                        </div>
                        <div class="d-flex  fv-row flex-column mb-8">
                            <label class="fs-6 fw-semibold mb-2">Desccription</label>
                            <textarea class="form-control form-control-solid" rows="3" name="description" placeholder="Desccription"></textarea>
                        </div>
                        <div class="fv-row mb-10 fv-plugins-icon-container d-none">
                            <label class="required fs-6 fw-semibold form-label mb-2">Status:</label>
                            <select name="status" data-control="select2" data-placeholder="Select an option"
                            data-minimum-results-for-search="Infinity" class="form-select form-select-solid fw-bold select2-hidden-accessible">
                                <option></option>
                                <option value="1" selected>Active</option>
                                <option value="2">Inactive</option>
                            </select>
                            <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer flex-center">
                <button type="button" modal-id="#modal_add_trailer_type" data-id="" id="" class="btn btn-primary me-4 submit">
                    <span class="indicator-label">Submit</span>
                    <span class="indicator-progress">Please wait...
                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                </button>
                <button type="button" modal-id="#modal_add_trailer_type" id="" class="btn btn-light me-3 cancel">Cancel</button>
            </div>
        </div>
    </div>
</div>
