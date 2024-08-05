{{-- MODAL ADD CLIENT --}}
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
                <form id="form" modal-id="#modal_add_tractor_trailer" class="form fv-plugins-bootstrap5 fv-plugins-framework" action="/services/tractor_trailer/create">
                    <div class="d-flex flex-column px-5 px-lg-10" id="kt_modal_add_user_scroll" style="max-height: 670px;">
                        <div class="fv-row mb-5 fv-plugins-icon-container">
                            <label class="required fs-6 fw-semibold form-label mb-2">Tractor:</label>
                            <select name="tractor" data-control="select2" data-placeholder="Select a tractor" class="form-select form-select-solid fw-bold select2-hidden-accessible">
                                <option></option>
                            </select>
                            <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
                        </div>
                    </div>
                    <div class="d-flex flex-column px-5 px-lg-10" id="kt_modal_add_user_scroll" style="max-height: 670px;">
                        <div class="fv-row mb-5 fv-plugins-icon-container">
                            <label class="required fs-6 fw-semibold form-label mb-2">Trailer:</label>
                            <select name="trailer" data-control="select2" data-placeholder="Select a trailer" class="form-select form-select-solid fw-bold select2-hidden-accessible">
                                <option></option>
                            </select>
                            <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
                        </div>
                    </div>
                    <div class="d-flex flex-column px-5 px-lg-10 mb-8">
                        <label class="fs-6 fw-semibold mb-2">Remarks</label>
                        <textarea class="form-control form-control-solid" rows="3" name="remarks" placeholder="Remarks"></textarea>
                    </div>

                    <div class="d-flex flex-column px-5 px-lg-10" id="kt_modal_add_user_scroll" data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_modal_add_user_header" data-kt-scroll-wrappers="#kt_modal_add_user_scroll" data-kt-scroll-offset="300px" style="max-height: 670px;">
                        <div class="fv-row mb-5 fv-plugins-icon-container">
                            <label class="required fs-6 fw-semibold form-label mb-2">Status:</label>
                            <select name="is_active" data-control="select2" data-placeholder="Select a status" data-hide-search="false" data-minimum-results-for-search="Infinity" class="form-select form-select-solid fw-bold select2-hidden-accessible"
                            data-select2-id="select2-data-15-mta8" tabindex="-1" aria-hidden="true" data-kt-initialized="1">
                                <option></option>
                                <option value="1" selected>Active</option>
                                <option value="0">Inactive</option>
                            </select>
                            <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer flex-center">
                <button type="button" modal-id="#modal_add_tractor_trailer" data-id="" id="submit" class="btn btn-primary me-4">
                    <span class="indicator-label">Submit</span>
                    <span class="indicator-progress">Please wait...
                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                </button>
                <button type="button" modal-id="#modal_add_tractor_trailer" id="cancel" class="btn btn-light me-3">Cancel</button>
            </div>
        </div>
    </div>
</div>
