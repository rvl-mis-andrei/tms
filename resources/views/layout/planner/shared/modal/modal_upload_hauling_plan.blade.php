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
