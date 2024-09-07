{{-- MODAL UPLOAD HAULING PLAN --}}
<div class="modal fade" id="modal_fhauling_plan" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header justify-content-center" id="kt_modal_add_user_header">
                <div class="text-center">
                    <h1 class="mb-3 modal_title">Upload Hauling Plan</h1>
                    <div class="text-muted fs-5">Fill-up the form and click
                        <a href="javascript:;" class="fw-bolder link-primary">Submit</a>.
                    </div>
                </div>
            </div>
            <div class="modal-body">
            <div class="alert alert-dismissible bg-light-primary d-flex flex-column flex-sm-row p-5 mb-5">
                <i class="ki-duotone ki-notification-bing fs-2hx text-primary me-4 mb-5 mb-sm-0">
                    <span class="path1"></span>
                    <span class="path2"></span>
                    <span class="path3"></span>
                </i>
                <div class="d-flex flex-column pe-0 pe-sm-10">
                    <h4 class="fw-semibold">Gentle Reminder</h4>
                    <span>You're uploading the hauling plan in this batch</span>
                </div>
                <button type="button" class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto" data-bs-dismiss="alert">
                    <i class="ki-duotone ki-cross fs-1 text-primary">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                </button>
            </div>
                <form id="form_hauling_plan" modal-id="#modal_fhauling_plan" class="form fv-plugins-bootstrap5 fv-plugins-framework" action="/tms/cco-b/planner/haulage_info/hauling_plan">
                    <div class="custom-file-upload">
                        <div class="upload-area uploadArea" id="">
                            <div class="fv-row">
                                <div class="icon" id="uploadIcon">
                                    <i class="bi bi-cloud-arrow-up-fill"></i>
                                </div>
                                <h3 id="" class="uploadText">Click here to upload file</h3>
                                <input type="file" class="fileInput" name="hauling_plan">
                                <div id="" class="file-name fileName"></div>
                                <a href="#" id="" class="text-active-primary mt-3 removeFile" style="display: none;">Remove File</a>
                                <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer flex-center">
                <button type="button" modal-id="#modal_fhauling_plan" data-id="" class="btn btn-primary me-4 submit">
                    <span class="indicator-label">Submit</span>
                    <span class="indicator-progress">Please wait...
                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                </button>
                <button type="button" modal-id="#modal_fhauling_plan" class="btn btn-light me-3 cancel">Cancel</button>
            </div>
        </div>
    </div>
</div>


{{-- MODAL UPLOAD MASTER LIST --}}
<div class="modal fade" id="modal_upload_masterlist" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header justify-content-center" id="kt_modal_add_user_header">
                <div class="text-center">
                    <h1 class="mb-3 modal_title">Upload Masterlist</h1>
                    <div class="text-muted fs-5">Fill-up the form and click
                        <a href="javascript:;" class="fw-bolder link-primary">Submit</a>.
                    </div>
                </div>
            </div>
            <div class="modal-body px-5 my-7">
                <form id="form_masterlist" modal-id="#modal_upload_masterlist" class="form fv-plugins-bootstrap5 fv-plugins-framework" action="/tms/cco-b/planner/haulage_info/masterlist">
                    <div class="custom-file-upload">
                        <div class="upload-area uploadArea">
                            <div class="fv-row">
                                <div class="icon" id="uploadIcon">
                                    <i class="bi bi-cloud-arrow-up-fill"></i>
                                </div>
                                <h3 id="" class="uploadText">Drag & Drop files here</h3>
                                <input type="file" class="fileInput" name="masterlist">
                                <div id="" class="file-name fileName"></div>
                                <a href="#" id="" class="text-active-primary mt-3 removeFile" style="display: none;">Remove File</a>
                                <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer flex-center">
                <button type="button" modal-id="#modal_upload_masterlist" data-id="" class="btn btn-primary me-4 submit">
                    <span class="indicator-label">Submit</span>
                    <span class="indicator-progress">Please wait...
                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                </button>
                <button type="button" modal-id="#modal_upload_masterlist" class="btn btn-light me-3 cancel">Cancel</button>
            </div>
        </div>
    </div>
</div>

{{-- MODAL NEW DEALER UNIT --}}
<div class="modal fade" id="modal_add_dealer_unit" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered mw-600px">
        <div class="modal-content">
            <div class="modal-header justify-content-center" id="kt_modal_add_user_header">
                <div class="text-center">
                    <h1 class="mb-3 modal_title">Add New Unit</h1>
                    <div class="text-muted fs-5">Fill-up the form and click
                        <a href="javascript:;" class="fw-bolder link-primary">Submit</a>.
                    </div>
                </div>
            </div>
            <div class="modal-body my-7 pt-0">
                <form id="form_new_unit" modal-id="#modal_add_dealer_unit" class="form fv-plugins-bootstrap5 fv-plugins-framework" action="/tms/cco-b/planner/haulage_info/add_block_unit">
                    <div class="d-flex flex-column px-5 px-lg-10" style="max-height: 670px;">
                        <div class="fv-row mb-7 fv-plugins-icon-container">
                            <label class="required fs-6 fw-semibold form-label mb-2">Dealer:</label>
                            <select name="dealer" data-control="select2" data-placeholder="Select dealer" data-hide-search="false"  class="form-select modal-select form-select-solid fw-bold">
                                <option></option>
                                <option value="2" selected>On-Going</option>
                                <option value="1">Completed</option>
                            </select>
                            <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
                        </div>
                        <div class="row">
                            <div class="fv-row mb-7 fv-plugins-icon-container col-7">
                                <label class="required fs-6 fw-semibold form-label mb-2">Model:</label>
                                <select name="model" data-control="select2" class="form-select ajax-select form-select-solid fw-bold" >
                                    <option></option>
                                </select>
                                <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
                            </div>
                            <div class="fv-row mb-7 fv-plugins-icon-container col-5">
                                <label class="required fw-semibold fs-6 mb-2">CS No.</label>
                                <input type="text" name="cs_no" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="CS No.">
                                <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="fv-row mb-7 fv-plugins-icon-container">
                            <label class="required fw-semibold fs-6 mb-2">Color Description</label>
                            <input type="text" name="color_description" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Color Description">
                            <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
                        </div>
                        <div class="fv-row mb-7 fv-plugins-icon-container">
                            <label class="required fw-semibold fs-6 mb-2">Location</label>
                            <input type="text" name="location" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Location">
                            <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
                        </div>
                        <div class="fv-row mb-7 fv-plugins-icon-container">
                            <label class="required fs-6 fw-semibold mb-2">Invoice Date</label>
                            <div class="position-relative d-flex align-items-center">
                                <i class="ki-outline ki-calendar-8 fs-2 position-absolute mx-4"></i>
                                <input class="form-control form-control-solid ps-12" input-control="flatpicker" placeholder="Select a date" id="planning_date" name="invoice_date" type="text">
                            </div>
                        </div>
                        <div class="fv-row mb-7 fv-plugins-icon-container">
                            <label class="required fs-6 fw-semibold form-label mb-2">Hub:</label>
                            <select name="hub" data-control="select2" data-placeholder="Select a hub" data-hide-search="false" data-minimum-results-for-search="Infinity"
                            class="form-select form-select-solid fw-bold select2-hidden-accessible"
                            data-select2-id="select2-data-15-mta8" tabindex="-1" aria-hidden="true" data-kt-initialized="1">
                                <option></option>
                                <option value="SVC">SVC</option>
                                <option value="BVC">BVC</option>
                            </select>
                            <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
                        </div>
                        <div class="d-flex flex-column">
                            <label class="fs-6 fw-semibold mb-2">Remarks</label>
                            <textarea class="form-control form-control-solid" rows="3" name="remarks" placeholder="Remarks"></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer flex-center">
                <button type="button" modal-id="#modal_add_dealer_unit" data-id="" class="btn btn-primary me-4 submit">
                    <span class="indicator-label">Submit</span>
                    <span class="indicator-progress">Please wait...
                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                </button>
                <button type="button" modal-id="#modal_add_dealer_unit" class="btn btn-light me-3 cancel">Cancel</button>
            </div>
        </div>
    </div>
</div>
