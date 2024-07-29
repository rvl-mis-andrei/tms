<div class="card">
    <div class="card-header border-1 py-3">
        <div class="card-title">
            <div class="d-flex align-items-center position-relative my-1">
                <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5">
                    <span class="path1"></span><span class="path2"></span>
                </i>
                <input type="text" data-kt-user-table-filter="search" id="search" class="form-control form-control-solid w-250px ps-13" placeholder="Search Client">
            </div>
        </div>
        <div class="card-toolbar">
            <div class="d-flex justify-content-end" data-kt-user-table-toolbar="base">
               <button type="button" class="btn btn-light-primary me-3 hover-elevate-up" data-kt-menu-trigger="click"data-kt-menu-placement="bottom-end">
                    <i class="ki-duotone ki-filter fs-2"><span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                    Filter
                </button>
                <div class="menu menu-sub menu-sub-dropdown w-300px w-md-325px" data-kt-menu="true">
                    <!--begin::Header-->
                    <div class="px-7 py-5">
                        <div class="fs-5 text-dark fw-bold">Filter Options</div>
                    </div>
                    <!--end::Header-->

                    <!--begin::Separator-->
                    <div class="separator border-gray-200"></div>
                    <!--end::Separator-->

                    <!--begin::Content-->
                    <div class="px-7 py-5" data-kt-user-table-filter="form">
                        <!--begin::Input group-->
                        <div class="mb-10">
                            <label class="form-label fs-6 fw-semibold">Role:</label>
                            <select class="form-select form-select-solid fw-bold select2-hidden-accessible" data-kt-select2="true"
                                data-placeholder="Select option" data-allow-clear="true" data-kt-user-table-filter="role"
                                data-hide-search="true" data-select2-id="select2-data-9-h1ei" tabindex="-1" aria-hidden="true"
                                data-kt-initialized="1">
                                <option data-select2-id="select2-data-11-xv5r"></option>
                                <option value="Administrator">Administrator</option>
                                <option value="Analyst">Analyst</option>
                                <option value="Developer">Developer</option>
                                <option value="Support">Support</option>
                                <option value="Trial">Trial</option>
                            </select><span class="select2 select2-container select2-container--bootstrap5" dir="ltr"
                                data-select2-id="select2-data-10-pemf" style="width: 100%;"><span class="selection"><span
                                        class="select2-selection select2-selection--single form-select form-select-solid fw-bold"
                                        role="combobox" aria-haspopup="true" aria-expanded="false" tabindex="0" aria-disabled="false"
                                        aria-labelledby="select2-kt8o-container" aria-controls="select2-kt8o-container"><span
                                            class="select2-selection__rendered" id="select2-kt8o-container" role="textbox"
                                            aria-readonly="true" title="Select option"><span
                                                class="select2-selection__placeholder">Select option</span></span><span
                                            class="select2-selection__arrow" role="presentation"><b
                                                role="presentation"></b></span></span></span><span class="dropdown-wrapper"
                                    aria-hidden="true"></span></span>
                        </div>
                        <!--end::Input group-->

                        <!--begin::Input group-->
                        <div class="mb-10">
                            <label class="form-label fs-6 fw-semibold">Two Step
                                Verification:</label>
                            <select class="form-select form-select-solid fw-bold select2-hidden-accessible" data-kt-select2="true"
                                data-placeholder="Select option" data-allow-clear="true" data-kt-user-table-filter="two-step"
                                data-hide-search="true" data-select2-id="select2-data-12-rabj" tabindex="-1" aria-hidden="true"
                                data-kt-initialized="1">
                                <option data-select2-id="select2-data-14-5q8z"></option>
                                <option value="Enabled">Enabled</option>
                            </select><span class="select2 select2-container select2-container--bootstrap5" dir="ltr"
                                data-select2-id="select2-data-13-s6s6" style="width: 100%;"><span class="selection"><span
                                        class="select2-selection select2-selection--single form-select form-select-solid fw-bold"
                                        role="combobox" aria-haspopup="true" aria-expanded="false" tabindex="0" aria-disabled="false"
                                        aria-labelledby="select2-d3ks-container" aria-controls="select2-d3ks-container"><span
                                            class="select2-selection__rendered" id="select2-d3ks-container" role="textbox"
                                            aria-readonly="true" title="Select option"><span
                                                class="select2-selection__placeholder">Select option</span></span><span
                                            class="select2-selection__arrow" role="presentation"><b
                                                role="presentation"></b></span></span></span><span class="dropdown-wrapper"
                                    aria-hidden="true"></span></span>
                        </div>
                        <!--end::Input group-->

                        <!--begin::Actions-->
                        <div class="d-flex justify-content-end">
                            <button type="reset" class="btn btn-light btn-active-light-primary fw-semibold me-2 px-6"
                                data-kt-menu-dismiss="true" data-kt-user-table-filter="reset">Reset</button>
                            <button type="submit" class="btn btn-primary fw-semibold px-6" data-kt-menu-dismiss="true"
                                data-kt-user-table-filter="filter">Apply</button>
                        </div>
                        <!--end::Actions-->
                    </div>
                    <!--end::Content-->
                </div>
                <button type="button" class="btn btn-light-primary me-3 hover-elevate-up" data-bs-toggle="modal" data-bs-target="#kt_modal_export_users">
                    <i class="ki-duotone ki-exit-up fs-2">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                    Export
                </button>
                <button type="button" class="btn btn-primary hover-elevate-up" data-bs-toggle="modal" data-bs-target="#kt_modal_add_client">
                    <i class="ki-duotone ki-plus fs-2"></i>
                    Add Client
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
                  <div class="table-responsive" id="client_list_wrapper">
                      <table class="table table-striped align-middle table-row-dashed fs-6 gy-5 dataTable no-footer"
                          id="client_list_table">
                      </table>
                  </div>
              </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="kt_modal_export_users" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="fw-bold">Export Users</h2>
                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-kt-users-modal-action="close">
                    <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                </div>
            </div>
            <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
                <form id="kt_modal_export_users_form" class="form fv-plugins-bootstrap5 fv-plugins-framework" action="#">
                    <div class="fv-row mb-10">
                        <label class="fs-6 fw-semibold form-label mb-2">Select Roles:</label>
                        <!--begin::Input-->
                        <select name="role" data-control="select2" data-placeholder="Select a role" data-hide-search="true" class="form-select form-select-solid fw-bold select2-hidden-accessible" data-select2-id="select2-data-15-mta8" tabindex="-1" aria-hidden="true" data-kt-initialized="1">
                            <option data-select2-id="select2-data-17-o2is"></option>
                            <option value="Administrator">Administrator
                            </option>
                            <option value="Analyst">Analyst</option>
                            <option value="Developer">Developer</option>
                            <option value="Support">Support</option>
                            <option value="Trial">Trial</option>
                        </select><span class="select2 select2-container select2-container--bootstrap5" dir="ltr" data-select2-id="select2-data-16-b2wh" style="width: 100%;"><span class="selection"><span class="select2-selection select2-selection--single form-select form-select-solid fw-bold" role="combobox" aria-haspopup="true" aria-expanded="false" tabindex="0" aria-disabled="false" aria-labelledby="select2-role-kz-container" aria-controls="select2-role-kz-container"><span class="select2-selection__rendered" id="select2-role-kz-container" role="textbox" aria-readonly="true" title="Select a role"><span class="select2-selection__placeholder">Select a role</span></span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span>
                        <!--end::Input-->
                    </div>
                    <!--end::Input group-->

                    <!--begin::Input group-->
                    <div class="fv-row mb-10 fv-plugins-icon-container">
                        <!--begin::Label-->
                        <label class="required fs-6 fw-semibold form-label mb-2">Select
                            Export Format:</label>
                        <!--end::Label-->

                        <!--begin::Input-->
                        <select name="format" data-control="select2" data-placeholder="Select a format" data-hide-search="true" class="form-select form-select-solid fw-bold select2-hidden-accessible" data-select2-id="select2-data-18-q8r8" tabindex="-1" aria-hidden="true" data-kt-initialized="1">
                            <option data-select2-id="select2-data-20-9qe4"></option>
                            <option value="excel">Excel</option>
                            <option value="pdf">PDF</option>
                            <option value="cvs">CVS</option>
                            <option value="zip">ZIP</option>
                        </select><span class="select2 select2-container select2-container--bootstrap5" dir="ltr" data-select2-id="select2-data-19-cd8k" style="width: 100%;"><span class="selection"><span class="select2-selection select2-selection--single form-select form-select-solid fw-bold" role="combobox" aria-haspopup="true" aria-expanded="false" tabindex="0" aria-disabled="false" aria-labelledby="select2-format-ld-container" aria-controls="select2-format-ld-container"><span class="select2-selection__rendered" id="select2-format-ld-container" role="textbox" aria-readonly="true" title="Select a format"><span class="select2-selection__placeholder">Select a format</span></span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span>
                        <!--end::Input-->
                    <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div></div>
                    <!--end::Input group-->

                    <!--begin::Actions-->
                    <div class="text-center">
                        <button type="reset" class="btn btn-light me-3" data-kt-users-modal-action="cancel">
                            Discard
                        </button>

                        <button type="submit" class="btn btn-primary" data-kt-users-modal-action="submit">
                            <span class="indicator-label">
                                Submit
                            </span>
                            <span class="indicator-progress">
                                Please wait... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                            </span>
                        </button>
                    </div>
                    <!--end::Actions-->
                </form>
                <!--end::Form-->
            </div>
            <!--end::Modal body-->
        </div>
        <!--end::Modal content-->
    </div>
    <!--end::Modal dialog-->
</div>
<div class="modal fade" id="kt_modal_add_client" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content">
            <div class="modal-header" id="kt_modal_add_user_header">
                <h2 class="fw-bold">Add Cient</h2>
                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                    <i class="ki-duotone ki-cross fs-1">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                </div>
            </div>
            <div class="modal-body px-5 my-7">
                <form id="kt_modal_add_user_form" class="form fv-plugins-bootstrap5 fv-plugins-framework" action="#">
                    <div class="d-flex flex-column scroll-y px-5 px-lg-10" id="kt_modal_add_user_scroll" data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_modal_add_user_header" data-kt-scroll-wrappers="#kt_modal_add_user_scroll" data-kt-scroll-offset="300px" style="max-height: 670px;">
                        <div class="fv-row mb-7 fv-plugins-icon-container">
                            <label class="required fw-semibold fs-6 mb-2">Client Name</label>
                            <input type="text" name="user_name" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Client Name">
                            <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
                        </div>
                        <div class="fv-row mb-10 fv-plugins-icon-container">
                            <label class="required fs-6 fw-semibold form-label mb-2">Status:</label>
                            <select name="role" data-control="select2" data-placeholder="Select a status" data-hide-search="false" data-minimum-results-for-search="Infinity" class="form-select form-select-solid fw-bold select2-hidden-accessible"
                            data-select2-id="select2-data-15-mta8" tabindex="-1" aria-hidden="true" data-kt-initialized="1">
                                <option></option>
                                <option value="1" selected>Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
                    </div>
                    </div>


                    <div class="text-center pt-10">
                        <button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal"> Discard </button>
                        <button type="button" class="btn btn-primary" id="submit">
                            <span class="indicator-label"> Submit </span>
                            <span class="indicator-progress">Please wait... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
