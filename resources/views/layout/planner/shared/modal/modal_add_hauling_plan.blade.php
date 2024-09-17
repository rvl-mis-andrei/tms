{{-- MODAL ADD HAULING PLAN --}}
<div class="modal fade" id="modal_add_hauling_plan" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered mw-550px">
        <div class="modal-content">
            <div class="modal-header justify-content-center" id="kt_modal_add_user_header">
                <div class="text-center">
                    <h1 class="mb-3 modal_title">New Hauling Plan</h1>
                    <div class="text-muted fs-5">Fill-up the form and click
                        <a href="javascript:;" class="fw-bolder link-primary">Submit</a>.
                    </div>
                </div>
            </div>
            <div class="modal-body px-5 my-7">
                <form id="form" modal-id="#modal_add_hauling_plan" class="form fv-plugins-bootstrap5 fv-plugins-framework" action="/services/haulage/create">
                    <div class="d-flex flex-column px-5 px-lg-10" id="kt_modal_add_user_scroll" data-kt-scroll="true" data-kt-scroll-activate="true"
                     data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_modal_add_user_header" data-kt-scroll-wrappers="#kt_modal_add_user_scroll" data-kt-scroll-offset="300px" style="max-height: 670px;">
                        <div class="fv-row mb-7 fv-plugins-icon-container">
                            <label class="required fw-semibold fs-6 mb-2">Name</label>
                            <input type="text" name="name" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Name">
                            <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
                        </div>
                        <div class="fv-row mb-7 fv-plugins-icon-container">
                            <label class="required fs-6 fw-semibold mb-2">Date of Planning</label>
                            <div class="position-relative d-flex align-items-center">
                                <i class="ki-outline ki-calendar-8 fs-2 position-absolute mx-4"></i>
                                <input class="form-control form-control-solid ps-12" input-control="flatpicker" placeholder="Select a date" id="planning_date" name="planning_date" type="text">
                            </div>
                        </div>
                        <div class="fv-row mb-7 fv-plugins-icon-container select-status d-none">
                            <label class="required fs-6 fw-semibold form-label mb-2">Status:</label>
                            <select name="status" data-control="select2" data-placeholder="Select a status" data-hide-search="false" data-minimum-results-for-search="Infinity"
                            class="form-select form-select-solid fw-bold">
                                <option></option>
                                <option value="2" selected>On-Going</option>
                                <option value="1">Completed</option>
                            </select>
                            <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
                        </div>
                        <div class="fv-row mb-7 fv-plugins-icon-container select-plan-type d-none">
                            <label class="required fs-6 fw-semibold form-label mb-2">Plan Type:</label>
                            <select name="plan_type" data-control="select2" data-placeholder="Select a type" data-hide-search="false" data-minimum-results-for-search="Infinity"
                            class="form-select form-select-solid fw-bold">
                                <option></option>
                                <option value="1" selected>Masterlist</option>
                                {{-- <option value="2">Hauling Plan</option> --}}
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
                <button type="button" modal-id="#modal_add_hauling_plan" data-id="" class="btn btn-primary me-4 submit">
                    <span class="indicator-label">Submit</span>
                    <span class="indicator-progress">Please wait...
                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                </button>
                <button type="button" modal-id="#modal_add_hauling_plan" class="btn btn-light me-3 cancel">Cancel</button>
            </div>
        </div>
    </div>
</div>
