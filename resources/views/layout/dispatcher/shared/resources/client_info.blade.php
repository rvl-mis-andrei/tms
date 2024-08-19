<div class="client_info">
    <div class="d-flex">
        <div class="app-main flex-column flex-row-fluid " id="kt_app_main">
            <div class="d-flex flex-column flex-lg-row">
                <div class="flex-column flex-lg-row-auto w-lg-250px w-xl-350px mb-10">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex flex-center flex-column py-5">
                                <a href="javascript:;" class="fs-3 text-gray-800 text-hover-primary fw-bold mb-3 text-center">
                                    {{ $data['name'] }}
                                </a>
                                <div class="mb-9">
                                    <div class="badge badge-lg badge-light-primary d-inline">Client</div>
                                </div>
                                <div class="d-flex flex-wrap flex-center">
                                    <div class="border border-gray-300 border-dashed rounded py-3 px-3 mb-3">
                                        <div class="fs-4 fw-bold text-gray-700">
                                            <span class="w-75px">{{ $data['active_dealership'] }}</span>
                                            <i class="ki-outline ki-abstract-7 fs-3 text-info"></i>
                                        </div>
                                        <div class="fw-semibold text-muted fs-7">Dealers</div>
                                    </div>
                                    <div
                                        class="border border-gray-300 border-dashed rounded py-3 px-3 mx-4 mb-3">
                                        <div class="fs-4 fw-bold text-gray-700">
                                            <span class="w-50px">{{ $data['active_dealership'] }}</span>
                                            <i
                                                class="ki-outline ki-arrow-up fs-3 text-success"></i>
                                        </div>
                                        <div class="fw-semibold text-muted fs-7">Active</div>
                                    </div>
                                    <div
                                        class="border border-gray-300 border-dashed rounded py-3 px-3 mb-3">
                                        <div class="fs-4 fw-bold text-gray-700">
                                            <span class="w-50px">{{ $data['inactive_dealership'] }}</span>
                                            <i class="ki-outline ki-arrow-down fs-3 text-danger"></i>
                                        </div>
                                        <div class="fw-semibold text-muted fs-7">Inactive</div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex flex-stack fs-4 py-3">
                                <div class="fw-bold rotate collapsible" data-bs-toggle="collapse"
                                    href="#kt_user_view_details" role="button" aria-expanded="false"
                                    aria-controls="kt_user_view_details">
                                    Details
                                    <span class="ms-2 rotate-180">
                                        <i class="ki-outline ki-down fs-3"></i> </span>
                                </div>

                                <span data-bs-toggle="tooltip" data-bs-trigger="hover"
                                    title="Edit customer details">
                                    <a href="#" class="btn btn-sm btn-light-primary"
                                        data-bs-toggle="modal"
                                        data-bs-target="#kt_modal_update_details">
                                        Edit
                                    </a>
                                </span>
                            </div>
                            <!--end::Details toggle-->

                            <div class="separator"></div>

                            <!--begin::Details content-->
                            <div id="kt_user_view_details" class="collapse show">
                                <div class="pb-5 fs-6">

                                    <div class="fw-bold mt-5">Client</div>
                                    <div class="text-gray-600">{{ $data['name'] }}</div>

                                    <div class="fw-bold mt-5">Description</div>
                                    <div class="text-gray-600">
                                        {{ $data['description'] }}
                                    </div>

                                    <div class="fw-bold mt-5">Status</div>
                                    <div class="text-gray-600">
                                        <div class="badge badge-light-{{ $data['is_active']? 'success':'danger' }}">{{ $data['is_active']? 'Active':'Inactive' }}</div>
                                    </div>

                                </div>
                            </div>
                            <!--end::Details content-->
                        </div>
                        <!--end::Card body-->
                    </div>
                </div>

                <div class="flex-lg-row-fluid ms-lg-10">
                    <ul class="nav nav-custom nav-tabs nav-line-tabs nav-line-tabs-2x border-0 fs-4 fw-semibold mb-4">
                        <li class="nav-item">
                            <a class="nav-link text-active-primary pb-2 active" data-bs-toggle="tab"
                                href="#tab_dealership_lisiting">Dealership Listing</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-active-primary pb-2" data-bs-toggle="tab"
                                href="#tab_events_logs">Events & Logs</a>
                        </li>
                    </ul>

                    <div class="tab-content" id="myTabContent">

                        <div class="tab-pane fade show active" id="tab_dealership_lisiting" role="tabpanel">
                            <div class="card card-flush mb-6 mb-xl-9">
                                <div class="card-header border-1 py-3">
                                    <div class="card-title">
                                        <div class="d-flex align-items-center position-relative my-1">
                                            <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5">
                                                <span class="path1"></span><span class="path2"></span>
                                            </i>
                                            <input type="text" data-kt-user-table-filter="search" id="search" class="form-control form-control-sm form-control-solid w-250px ps-13"
                                            placeholder="Search Dealer">
                                        </div>
                                    </div>
                                    <div class="card-toolbar">
                                        <div class="d-flex justify-content-end" data-kt-user-table-toolbar="base">
                                            <div class="w-150px me-3">
                                                <!--begin::Select2-->
                                                <select class="form-select form-select-sm form-select-solid status" id="status" name="status" data-minimum-results-for-search="Infinity" data-control="select2" data-hide-search="true" data-placeholder="Status">
                                                    <option value="all">All</option>
                                                    <option value="1">Active</option>
                                                    <option value="0">Inactive</option>
                                                </select>
                                                <!--end::Select2-->
                                            </div>
                                            <button type="button" class="btn btn-sm  btn-light-primary me-3 hover-elevate-up" data-bs-toggle="modal" data-bs-target="#kt_modal_export_users">
                                                <i class="ki-duotone ki-exit-up fs-2">
                                                    <span class="path1"></span>
                                                    <span class="path2"></span>
                                                </i>
                                                Export
                                            </button>
                                            <button type="button" class="btn btn-sm  btn-primary hover-elevate-up" data-bs-toggle="modal" data-bs-target="#modal_add_dealership">
                                                <i class="ki-duotone ki-plus fs-2"></i>
                                                Add New Dealer
                                            </button>
                                        </div>
                                        <div class="d-flex justify-content-end align-items-center d-none" data-kt-user-table-toolbar="selected">
                                            <div class="fw-bold me-5">
                                                <span class="me-2" data-kt-user-table-select="selected_count"></span> Selected
                                            </div>

                                            <button type="button" class="btn btn-sm btn-danger" data-kt-user-table-select="delete_selected">
                                                Delete Selected
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex flex-column flex-xl-row p-7">
                                        <div class="flex-lg-row-fluid">
                                        <div class="dataTables_wrapper dt-bootstrap4 no-footer">
                                            <div class="table-responsive" id="dealership_list_wrapper">
                                                <table class="table table-striped align-middle table-row-dashed fs-6 gy-5 dataTable no-footer"
                                                    id="dealership_list_table">
                                                </table>
                                            </div>
                                        </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="tab_events_logs"  role="tabpanel">
                            <div class="card pt-4 mb-6 mb-xl-9">
                                <!--begin::Card header-->
                                <div class="card-header border-0">
                                    <!--begin::Card title-->
                                    <div class="card-title">
                                        <h2>Events</h2>
                                    </div>
                                    <!--end::Card title-->

                                    <!--begin::Card toolbar-->
                                    <div class="card-toolbar">
                                        <!--begin::Button-->
                                        <button type="button" class="btn btn-sm btn-light-primary">
                                            <i class="ki-outline ki-cloud-download fs-3"></i>
                                            Download Report
                                        </button>
                                        <!--end::Button-->
                                    </div>
                                    <!--end::Card toolbar-->
                                </div>
                                <!--end::Card header-->

                                <!--begin::Card body-->
                                <div class="card-body py-0">
                                    <!--begin::Table-->
                                    <table
                                        class="table align-middle table-row-dashed fs-6 text-gray-600 fw-semibold gy-5"
                                        id="kt_table_customers_events">
                                        <tbody>
                                            <tr>
                                                <td class="min-w-400px">
                                                    <a href="#"
                                                        class="text-gray-600 text-hover-primary me-1">Sean
                                                        Bean</a> has made payment to <a href="#"
                                                        class="fw-bold text-gray-900 text-hover-primary">#XRS-45670</a>
                                                </td>
                                                <td class="pe-0 text-gray-600 text-end min-w-200px">
                                                    20 Dec 2023, 9:23 pm </td>
                                            </tr>
                                            <tr>
                                                <td class="min-w-400px">
                                                    <a href="#"
                                                        class="text-gray-600 text-hover-primary me-1">Emma
                                                        Smith</a> has made payment to <a href="#"
                                                        class="fw-bold text-gray-900 text-hover-primary">#XRS-45670</a>
                                                </td>
                                                <td class="pe-0 text-gray-600 text-end min-w-200px">
                                                    10 Mar 2023, 11:05 am </td>
                                            </tr>
                                            <tr>
                                                <td class="min-w-400px">
                                                    <a href="#"
                                                        class="text-gray-600 text-hover-primary me-1">Max
                                                        Smith</a> has made payment to <a href="#"
                                                        class="fw-bold text-gray-900 text-hover-primary">#SDK-45670</a>
                                                </td>
                                                <td class="pe-0 text-gray-600 text-end min-w-200px">
                                                    15 Apr 2023, 6:43 am </td>
                                            </tr>
                                            <tr>
                                                <td class="min-w-400px">
                                                    <a href="#"
                                                        class="text-gray-600 text-hover-primary me-1">Sean
                                                        Bean</a> has made payment to <a href="#"
                                                        class="fw-bold text-gray-900 text-hover-primary">#XRS-45670</a>
                                                </td>
                                                <td class="pe-0 text-gray-600 text-end min-w-200px">
                                                    15 Apr 2023, 11:30 am </td>
                                            </tr>
                                            <tr>
                                                <td class="min-w-400px">
                                                    Invoice <a href="#"
                                                        class="fw-bold text-gray-900 text-hover-primary me-1">#DER-45645</a>
                                                    status has changed from <span
                                                        class="badge badge-light-info me-1">In
                                                        Progress</span> to <span
                                                        class="badge badge-light-primary">In
                                                        Transit</span>
                                                </td>
                                                <td class="pe-0 text-gray-600 text-end min-w-200px">
                                                    21 Feb 2023, 10:30 am </td>
                                            </tr>
                                            <tr>
                                                <td class="min-w-400px">
                                                    Invoice <a href="#"
                                                        class="fw-bold text-gray-900 text-hover-primary me-1">#KIO-45656</a>
                                                    status has changed from <span
                                                        class="badge badge-light-succees me-1">In
                                                        Transit</span> to <span
                                                        class="badge badge-light-success">Approved</span>
                                                </td>
                                                <td class="pe-0 text-gray-600 text-end min-w-200px">
                                                    22 Sep 2023, 11:30 am </td>
                                            </tr>
                                            <tr>
                                                <td class="min-w-400px">
                                                    Invoice <a href="#"
                                                        class="fw-bold text-gray-900 text-hover-primary me-1">#LOP-45640</a>
                                                    has been <span
                                                        class="badge badge-light-danger">Declined</span>
                                                </td>
                                                <td class="pe-0 text-gray-600 text-end min-w-200px">
                                                    24 Jun 2023, 11:05 am </td>
                                            </tr>
                                            <tr>
                                                <td class="min-w-400px">
                                                    Invoice <a href="#"
                                                        class="fw-bold text-gray-900 text-hover-primary me-1">#WER-45670</a>
                                                    is <span class="badge badge-light-info">In
                                                        Progress</span>
                                                </td>
                                                <td class="pe-0 text-gray-600 text-end min-w-200px">
                                                    24 Jun 2023, 2:40 pm </td>
                                            </tr>
                                            <tr>
                                                <td class="min-w-400px">
                                                    Invoice <a href="#"
                                                        class="fw-bold text-gray-900 text-hover-primary me-1">#LOP-45640</a>
                                                    has been <span
                                                        class="badge badge-light-danger">Declined</span>
                                                </td>
                                                <td class="pe-0 text-gray-600 text-end min-w-200px">
                                                    10 Mar 2023, 9:23 pm </td>
                                            </tr>
                                            <tr>
                                                <td class="min-w-400px">
                                                    Invoice <a href="#"
                                                        class="fw-bold text-gray-900 text-hover-primary me-1">#KIO-45656</a>
                                                    status has changed from <span
                                                        class="badge badge-light-succees me-1">In
                                                        Transit</span> to <span
                                                        class="badge badge-light-success">Approved</span>
                                                </td>
                                                <td class="pe-0 text-gray-600 text-end min-w-200px">
                                                    22 Sep 2023, 5:20 pm </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <!--end::Table-->
                                </div>
                                <!--end::Card body-->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL ADD DEALERSHIP --}}
    <div class="modal fade" id="modal_add_dealership" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered mw-600px">
            <div class="modal-content">
                <div class="modal-header justify-content-center" id="kt_modal_add_user_header">
                    <div class="text-center">
                        <h1 class="mb-3 modal_title">New Dealership</h1>
                        <div class="text-muted fs-5">Fill-up the form and click
                            <a href="javascript:;" class="fw-bolder link-primary">Submit</a>.
                        </div>
                    </div>
                </div>
                <div class="modal-body px-5 my-7">
                    <form id="form" modal-id="#modal_add_dealership" class="form fv-plugins-bootstrap5 fv-plugins-framework" action="/services/client_dealership/create">
                        <div class="d-flex flex-column px-5 px-lg-10" id="kt_modal_add_user_scroll" style="max-height: 670px;">
                            <div class="row">
                                <div class="fv-row mb-7 col-7 fv-plugins-icon-container">
                                    <label class="required fw-semibold fs-6 mb-2">Dealership Name</label>
                                    <input type="text" name="name" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Dealership Name">
                                    <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
                                </div>
                                <div class="fv-row mb-7 col-5 fv-plugins-icon-container">
                                    <label class="required fw-semibold fs-6 mb-2">Code</label>
                                    <input type="text" name="code" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Code">
                                    <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="fv-row mb-7 fv-plugins-icon-container">
                                <label class="required fs-6 fw-semibold form-label mb-2">Location</label>
                                <select name="location" data-control="select2" data-placeholder="Select a Location" data-hide-search="false"
                                class="form-select form-select-solid fw-bold select2-hidden-accessible">
                                <option></option>
                                </select>
                            <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
                            </div>
                            @if(Auth::user()->emp_cluster->cluster_id = 2)
                                <div class="fv-row mb-7 fv-plugins-icon-container">
                                    <label class="fw-semibold fs-6 mb-2">PV Lead Time</label>
                                    <input type="text" name="pv_lead_time" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="PV Lead Time">
                                    <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
                                </div>
                            @endif
                            <div class="fv-row mb-10 fv-plugins-icon-container">
                                <label class="required fs-6 fw-semibold form-label mb-2">Status:</label>
                                <select name="is_active" data-control="select2" data-placeholder="Select a status" data-hide-search="false" data-minimum-results-for-search="Infinity" class="form-select form-select-solid fw-bold select2-hidden-accessible"
                                data-select2-id="select2-data-15-mta8" tabindex="-1" aria-hidden="true" data-kt-initialized="1">
                                    <option></option>
                                    <option value="1" selected>Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                                <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
                            </div>
                            <div class="separator separator-dashed mb-7"></div>
                            <div class="repeater">
                                <div class="fs-5 fw-bold form-label mb-3">
                                    Receiving Personnel
                                    <span class="ms-2 cursor-pointer" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-html="true"
                                    data-bs-content="Add receiving personnel " data-kt-initialized="1">
                                        <i class="ki-outline ki-information fs-7"></i>
                                    </span>
                                    <button type="button" class="float-end btn btn-sm btn-light-primary me-auto add-field" data-repeater-create>Add field</button>
                                </div>
                                <div data-repeater-list="personnel_list">
                                    <div data-repeater-item>
                                        <div class="row mt-5">
                                            <div class="fv-row mb-7 fv-plugins-icon-container col-5">
                                                <label class="fw-semibold fs-6 required mb-2">Name</label>
                                                <input type="text" name="name" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Receiving Personnel">
                                                <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
                                            </div>
                                            <div class="fv-row mb-7 fv-plugins-icon-container col-5">
                                                <label class="fw-semibold fs-6 required mb-2">Contact Number</label>
                                                <input type="text" name="contact_number" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Contact Number">
                                                <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
                                            </div>
                                            <div class="col-2 mb-7 text-center mt-10">
                                                <button type="button" data-repeater-delete class="btn btn-icon btn-flex btn-active-light-primary w-30px h-30px me-3 remove-field" data-bs-toggle="popover"
                                                data-bs-trigger="hover" data-bs-html="true" data-bs-content="Remove this field" data-kt-initialized="1">
                                                    <i class="ki-outline ki-trash fs-3 text-danger "></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="separator separator-dashed"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer flex-center">
                    <button type="button" modal-id="#modal_add_client" data-id="" id="submit" class="btn btn-primary me-4">
                        <span class="indicator-label">Submit</span>
                        <span class="indicator-progress">Please wait...
                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                    </button>
                    <button type="button" modal-id="#modal_add_client" id="cancel" class="btn btn-light me-3">Cancel</button>
                </div>
            </div>
        </div>
    </div>
</div>

