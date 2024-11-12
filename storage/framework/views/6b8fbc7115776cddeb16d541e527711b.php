
<div class="modal fade" id="modal_add_tractor_trailer" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered mw-600px">
        <div class="modal-content">
            <div class="modal-header justify-content-center" id="kt_modal_add_user_header">
                <div class="text-center">
                    <h1 class="mb-3 modal_title">New Tractor Trailer</h1>
                    <div class="text-muted fs-5">Fill-up the form and click
                        <a href="javascript:;" class="fw-bolder link-primary">Submit</a>.
                    </div>
                </div>
            </div>
            <div class="modal-body px-5">
                <form id="form" modal-id="#modal_add_tractor_trailer" class="form fv-plugins-bootstrap5 fv-plugins-framework" action="/services/tractor_trailer/upsert">
                    <div class="d-flex flex-column px-5 px-lg-10" style="max-height: 670px;">
                        <div class="row">
                            <div class="d-flex flex-column col-6" id="kt_modal_add_user_scroll" style="max-height: 670px;">
                                <div class="fv-row mb-5 fv-plugins-icon-container">
                                    <label class="required fs-6 fw-semibold form-label mb-2">Tractor:</label>
                                    <select name="tractor" data-allow-clear="true" data-control="select2" data-placeholder="Select a tractor"
                                    class="form-select modal-select form-select-solid fw-bold select2-hidden-accessible">
                                        <option></option>
                                    </select>
                                    <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="d-flex flex-column col-6" id="kt_modal_add_user_scroll" style="max-height: 670px;">
                                <div class="fv-row mb-5 fv-plugins-icon-container">
                                    <label class="required fs-6 fw-semibold form-label mb-2">Trailer:</label>
                                    <select name="trailer" data-allow-clear="true" data-control="select2" data-placeholder="Select a trailer"
                                    class="form-select modal-select form-select-solid fw-bold select2-hidden-accessible">
                                        <option></option>
                                    </select>
                                    <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex flex-column" id="kt_modal_add_user_scroll" style="max-height: 670px;">
                            <div class="fv-row mb-5 fv-plugins-icon-container">
                                <label class="required fs-6 fw-semibold form-label mb-2">Driver 1:</label>
                                <select name="pdriver" data-allow-clear="true" data-control="select2" data-placeholder="Select a driver"
                                class="form-select modal-select form-select-solid fw-bold select2-hidden-accessible cluster_drivers">
                                    <option></option>
                                </select>
                                <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="d-flex flex-column" id="kt_modal_add_user_scroll" style="max-height: 670px;">
                            <div class="fv-row mb-5 fv-plugins-icon-container">
                                <label class="required fs-6 fw-semibold form-label mb-2">Driver 2:</label>
                                <select name="sdriver" data-allow-clear="true" data-control="select2" data-placeholder="Select a driver"
                                class="form-select modal-select form-select-solid fw-bold select2-hidden-accessible cluster_drivers">
                                    <option></option>
                                </select>
                                <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="d-flex flex-column mb-8">
                            <label class="fs-6 fw-semibold mb-2">Remarks</label>
                            <textarea class="form-control form-control-solid" rows="3" name="remarks" placeholder="Remarks"></textarea>
                        </div>

                        <div class="d-flex flex-column" id="kt_modal_add_user_scroll" data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_modal_add_user_header" data-kt-scroll-wrappers="#kt_modal_add_user_scroll" data-kt-scroll-offset="300px" style="max-height: 670px;">
                            <div class="fv-row mb-5 fv-plugins-icon-container">
                                <label class="required fs-6 fw-semibold form-label mb-2">Status:</label>
                                <select name="is_active" data-control="select2" data-placeholder="Select an option" data-hide-search="false" data-minimum-results-for-search="Infinity"
                                class="form-select form-select-solid fw-bold select2-hidden-accessible"
                                data-select2-id="select2-data-15-mta8" tabindex="-1" aria-hidden="true" data-kt-initialized="1" data-allow-clear="true">
                                    <option></option>
                                    <option value="1" >Available</option>
                                    <option value="2" >On Trip</option>
                                    <option value="3" >Absent Driver</option>
                                    <option value="4" >No Driver</option>
                                    <option value="5" >For PMS</option>
                                    <option value="6" >On-Going PMS</option>
                                    <option value="7" >Trailer Repair</option>
                                    <option value="8" >Tractor Repair</option>
                                    <option value="9" >Rehab/Recon</option>
                                    <option value="10">Others</option>
                                </select>
                                <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer flex-center">
                <button type="button" modal-id="#modal_add_tractor_trailer" data-id="" id="" class="btn btn-primary me-4 submit">
                    <span class="indicator-label">Submit</span>
                    <span class="indicator-progress">Please wait...
                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                </button>
                <button type="button" modal-id="#modal_add_tractor_trailer" id="" class="btn btn-light me-3 cancel">Cancel</button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="modal_add_trailer" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered mw-600px">
        <div class="modal-content">
            <div class="modal-header justify-content-center" id="kt_modal_add_user_header">
                <div class="text-center">
                    <h1 class="mb-3 modal_title">New Trailer</h1>
                    <div class="text-muted fs-5">Fill-up the form and click
                        <a href="javascript:;" class="fw-bolder link-primary">Submit</a>.
                    </div>
                </div>
            </div>
            <div class="modal-body px-5">
                <form id="form_add_trailer" modal-id="#modal_add_trailer" class="form fv-plugins-bootstrap5 fv-plugins-framework" action="/services/trailer/update">
                    <div class="d-flex flex-column px-5 px-lg-10" id="kt_modal_add_user_scroll" style="max-height: 670px;">
                        <div class="fv-row mb-7 fv-plugins-icon-container">
                            <label class="required fw-semibold fs-6 mb-2">Plate No.</label>
                            <input type="text" name="plate_no" class="form-control form-control-solid mb-3 mb-lg-0" data-id="" placeholder="Plate Number">
                            <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
                        </div>
                        <div class="fv-row mb-10 fv-plugins-icon-container">
                            <label class="required fs-6 fw-semibold form-label mb-2">Trailer Type:</label>
                            <select name="trailer_type" data-control="select2" data-placeholder="Select an option" class="form-select modal-select form-select-solid fw-bold">
                                <option></option>
                            </select>
                            <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
                        </div>
                        <div class="d-flex  fv-row flex-column mb-8">
                            <label class="fs-6 fw-semibold mb-2">Remarks</label>
                            <textarea class="form-control form-control-solid" rows="3" name="remarks" placeholder="Remarks"></textarea>
                        </div>
                        <div class="fv-row mb-10 fv-plugins-icon-container d-none">
                            <label class="required fs-6 fw-semibold form-label mb-2">Status:</label>
                            <select name="status" data-control="select2" data-placeholder="Select a status" data-hide-search="false" data-minimum-results-for-search="Infinity" class="form-select form-select-solid fw-bold select2-hidden-accessible"
                            data-select2-id="select2-data-15-mta8" tabindex="-1" aria-hidden="true" data-kt-initialized="1">
                                <option></option>
                                <option value="1" selected>Available</option>
                                <option value="2">Assigned</option>
                            </select>
                            <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer flex-center">
                <button type="button" modal-id="#modal_add_trailer" data-id="" id="" class="btn btn-primary me-4 submit">
                    <span class="indicator-label">Submit</span>
                    <span class="indicator-progress">Please wait...
                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                </button>
                <button type="button" modal-id="#modal_add_trailer" id="" class="btn btn-light me-3 cancel">Cancel</button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="modal_add_tractor" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered mw-600px">
        <div class="modal-content">
            <div class="modal-header justify-content-center" id="kt_modal_add_user_header">
                <div class="text-center">
                    <h1 class="mb-3 modal_title">New Tractor</h1>
                    <div class="text-muted fs-5">Fill-up the form and click
                        <a href="javascript:;" class="fw-bolder link-primary">Submit</a>.
                    </div>
                </div>
            </div>
            <div class="modal-body px-5">
                <form id="form_add_tractor" modal-id="#modal_add_tractor" class="form fv-plugins-bootstrap5 fv-plugins-framework" action="/services/tractor/update">
                    <div class="d-flex flex-column px-5 px-lg-10" id="kt_modal_add_user_scroll" style="max-height: 670px;">
                        <div class="fv-row mb-7 fv-plugins-icon-container">
                            <label class="required fw-semibold fs-6 mb-2">Plate No.</label>
                            <input type="text" name="plate_no" class="form-control form-control-solid mb-3 mb-lg-0" data-id="" placeholder="Plate Number">
                            <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
                        </div>
                        <div class="fv-row mb-7 fv-plugins-icon-container">
                            <label class="required fw-semibold fs-6 mb-2">Body No.</label>
                            <input type="text" name="body_no" class="form-control form-control-solid mb-3 mb-lg-0" data-id="" placeholder="Body Number">
                            <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
                        </div>
                        <div class="d-flex  fv-row flex-column mb-8">
                            <label class="fs-6 fw-semibold mb-2">Remarks</label>
                            <textarea class="form-control form-control-solid" rows="3" name="remarks" placeholder="Remarks"></textarea>
                        </div>
                        <div class="fv-row mb-10 fv-plugins-icon-container d-none">
                            <label class="required fs-6 fw-semibold form-label mb-2">Status:</label>
                            <select name="status" data-control="select2" data-placeholder="Select a status" data-hide-search="false" data-minimum-results-for-search="Infinity" class="form-select form-select-solid fw-bold select2-hidden-accessible"
                            data-select2-id="select2-data-15-mta8" tabindex="-1" aria-hidden="true" data-kt-initialized="1">
                                <option></option>
                                <option value="1" selected>Available</option>
                                <option value="2">Assigned</option>
                            </select>
                            <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer flex-center">
                <button type="button" modal-id="#modal_add_tractor" data-id="" id="" class="btn btn-primary me-4 submit">
                    <span class="indicator-label">Submit</span>
                    <span class="indicator-progress">Please wait...
                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                </button>
                <button type="button" modal-id="#modal_add_tractor" id="" class="btn btn-light me-3 cancel">Cancel</button>
            </div>
        </div>
    </div>
</div>
<?php /**PATH C:\Users\Andrei\Desktop\TMS_NEW\tms\resources\views/layout/dispatcher/shared/resources/modal/modal_add_tractor_trailer.blade.php ENDPATH**/ ?>