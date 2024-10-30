<div class="page-car-list">
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
                        <select class="form-select form-select-solid filter_table" id="" name="filter_car_status"
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
                    <button type="button" class="btn btn-primary hover-elevate-up" data-bs-toggle="modal" data-bs-target="#modal_add_cycletime">
                        <i class="ki-duotone ki-plus fs-2"></i>
                        Add New Cycle Time
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
            <div class="d-flex flex-column flex-xl-row">
                <div class="flex-lg-row-fluid">
                    <div class="table-responsive" id="cycletime_wrapper">
                        <table class="table table-bordered align-middle table-row-dashed fs-6"
                            id="cycletime_table">
                            <thead>
                                <tr>
                                    <th class="text-gray-400 fw-bold text-uppercase text-center fs-6" colspan="2">Dealer</th>
                                    <th class="text-gray-400 fw-bold text-uppercase text-start fs-6">Departure <br> Garage</th>
                                    <th class="text-gray-400 fw-bold text-uppercase text-start fs-6">Garage to Pickup <br>-SVC (HRS)</th>
                                    <th class="text-gray-400 fw-bold text-uppercase text-start fs-6">Garage to Pickup <br>-BVC (HRS)</th>
                                    <th class="text-gray-400 fw-bold text-uppercase text-start fs-6">Total <br>Loading Time</th>
                                    <th class="text-gray-400 fw-bold text-uppercase text-start fs-6">Departure at <br> Pickup Point to Dealer</th>
                                    <th class="text-gray-400 fw-bold text-uppercase text-start fs-6">Dealer to <br> Garage</th>
                                    <th class="text-gray-400 fw-bold text-uppercase text-start fs-6">Total Cycle Time <br>-SVC (HRS)</th>
                                    <th class="text-gray-400 fw-bold text-uppercase text-start fs-6">Total Cycle Time <br>-BVC (HRS)</th>
                                    <th class="text-gray-400 fw-bold text-uppercase text-start fs-6">Additional <br> Day</th>
                                    <th class="text-gray-400 fw-bold text-uppercase text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal_add_cycletime" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered mw-600px">
            <div class="modal-content">
                <div class="modal-header justify-content-center" id="kt_modal_add_user_header">
                    <div class="text-center">
                        <h1 class="mb-3 modal_title">New Cycle Time</h1>
                        <div class="text-muted fs-5">Fill-up the form and click
                            <a href="javascript:;" class="fw-bolder link-primary">Submit</a>.
                        </div>
                    </div>
                </div>
                <div class="modal-body px-5">
                    {{-- <form id="form_add_car" modal-id="#modal_add_cycletime" class="form fv-plugins-bootstrap5 fv-plugins-framework" action="/services/cluster_car/update">
                        <div class="d-flex flex-column px-5 px-lg-10" id="kt_modal_add_user_scroll" style="max-height: 670px;">
                            <div class="fv-row mb-7 fv-plugins-icon-container">
                                <label class="required fw-semibold fs-6 mb-2">Car Model</label>
                                <input type="text" name="car_model" class="form-control form-control-solid mb-3 mb-lg-0" data-id="" placeholder="Car model">
                                <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
                            </div>
                            <div class="fv-row mb-7 fv-plugins-icon-container">
                                <label class="required fw-semibold fs-6 mb-2">Short Name</label>
                                <input type="text" name="short_name" class="form-control form-control-solid mb-3 mb-lg-0" data-id="" placeholder="Short">
                                <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
                            </div>
                            <div class="fv-row mb-10 fv-plugins-icon-container d-none">
                                <label class="required fs-6 fw-semibold form-label mb-2">Status:</label>
                                <select name="status" data-control="select2" data-placeholder="Select a status" data-hide-search="false" data-minimum-results-for-search="Infinity" class="form-select form-select-solid fw-bold select2-hidden-accessible"
                                data-select2-id="select2-data-15-mta8" tabindex="-1" aria-hidden="true" data-kt-initialized="1">
                                    <option></option>
                                    <option value="1" selected>Active</option>
                                    <option value="2">Inactive</option>
                                </select>
                                <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
                            </div>
                        </div>
                    </form> --}}
                </div>
                <div class="modal-footer flex-center">
                    <button type="button" modal-id="#modal_add_cycletime" data-id="" id="" class="btn btn-primary me-4 submit">
                        <span class="indicator-label">Submit</span>
                        <span class="indicator-progress">Please wait...
                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                    </button>
                    <button type="button" modal-id="#modal_add_cycletime" id="" class="btn btn-light me-3 cancel">Cancel</button>
                </div>
            </div>
        </div>
    </div>
</div>
