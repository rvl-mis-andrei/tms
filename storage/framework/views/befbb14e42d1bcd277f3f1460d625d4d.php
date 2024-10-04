<div class="tractor_trailer_listing">
    <div class="row g-5 g-xl-10 mb-5 mb-xl-10 px-5">
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
                            <select class="form-select form-select-solid status" id="" name="status" data-minimum-results-for-search="Infinity" data-control="select2" data-hide-search="true" data-placeholder="Status">
                                <option value="all">All</option>
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
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
                        <button type="button" class="btn btn-primary hover-elevate-up" data-bs-toggle="modal" data-bs-target="#modal_add_tractor_trailer">
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
    <?php echo $__env->make('layout.dispatcher.shared.resources.modal.modal_add_tractor_trailer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
</div>
<?php /**PATH C:\Users\Andrei\Desktop\TMS_NEW\tms\resources\views/cluster_b/dispatcher/resources/tractor_trailer/tractor_trailer_list.blade.php ENDPATH**/ ?>