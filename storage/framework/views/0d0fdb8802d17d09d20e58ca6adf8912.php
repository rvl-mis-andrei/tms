<div class="tractor_trailer_info">
    <div class="d-flex flex-column flex-lg-row">
        <div class="flex-column flex-lg-row-auto w-lg-250px w-xl-350px mb-10">
            <div class="card mb-5 mb-xl-8">
                <div class="card-body">
                    <div class="d-flex flex-stack fs-4 mb-5">
                        <div class="fw-bold rotate collapsible" data-bs-toggle="collapse" role="button" aria-expanded="false">
                            Summary
                            <span class="ms-2 rotate-180">
                                <i class="ki-duotone ki-down fs-3"></i>
                            </span>
                        </div>

                        <span data-bs-toggle="tooltip" data-bs-trigger="hover"
                            data-bs-original-title="Other Actions" data-kt-initialized="1">
                            <a href="#" class="btn btn-light-primary btn-sm ps-7" data-kt-menu-trigger="click" data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end">
                        Actions
                        <i class="ki-duotone ki-down fs-2 me-0"></i> </a>
                        <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-semibold py-4 w-200px fs-6"
                            data-kt-menu="true">
                            <div class="menu-item px-5">
                                <div class="menu-content text-muted pb-2 px-5 fs-7 text-uppercase">
                                    Actions
                                </div>
                            </div>
                            <div class="menu-item px-5">
                                <a href="#" class="menu-link px-5 remarks">
                                    Remarks
                                </a>
                                <a href="#" class="menu-link px-5 delete text-danger">
                                    Delete tractor trailer
                                </a>
                                <div class="separator my-2"></div>
                                <label class="form-check form-switch form-check-custom form-check-solid">
                                    <input class="form-check-input w-35px h-25px" type="checkbox" value="" name="notifications" checked="" id="kt_user_menu_notifications">
                                    <span class="form-check-label text-muted fs-6 " for="kt_user_menu_notifications">
                                    Tractor Trailer Status
                                    </span>
                                </label>
                            </div>
                        </div>
                        </span>
                    </div>

                    <div class="separator"></div>

                    <div id="kt_user_view_details" class="collapse show">
                        <div class="pb-5 fs-6">

                            <div class="fw-bold mt-5">Tractor</div>
                            <div class="text-gray-600"><?php echo e(isset($data['tractor'])? $data['tractor'].' ['.$data['tractor_plate_no'].']':'--'); ?></div>

                            <div class="fw-bold mt-5">Trailer</div>
                            <div class="text-gray-600">
                                <a href="#" class="text-gray-600 text-hover-primary"><?php echo e(isset($data['trailer'])?  $data['trailer'].' ['.$data['trailer_type'].']' :'--'); ?></a>
                            </div>

                            <div class="fw-bold mt-5">Driver #1</div>
                            <div class="text-gray-600">
                                <a href="#" class="text-gray-600 text-hover-primary"><?php echo e(isset($data['drivers'][0])? $data['drivers'][0]['name'] :'--'); ?></a>
                            </div>

                            <div class="fw-bold mt-5">Driver #2</div>
                            <div class="text-gray-600">
                                <a href="#" class="text-gray-600 text-hover-primary"><?php echo e(isset($data['drivers'][1])? $data['drivers'][1]['name'] :'--'); ?></a>
                            </div>

                            <div class="fw-bold mt-5">Status</div>
                            <div class="text-gray-600">
                                <div class="badge badge-light-<?php echo e($data['status'][1]); ?>"><?php echo e($data['status'][0]); ?></div>

                            </div>

                            <div class="fw-bold mt-5">Remarks</div>
                            <div class="text-gray-600"><?php echo e($data['remarks']??'--'); ?></div>

                            <div class="fw-bold mt-5">Date Modified</div>
                            <div class="text-gray-600"><?php echo e($data['last_updated_at']); ?></div>

                            <div class="fw-bold mt-5">Modified By</div>
                            <div class="text-gray-600"><?php echo e($data['last_updated_by']); ?></div>
                        </div>
                    </div>

                </div>
            </div>

        </div>

        <div class="flex-lg-row-fluid ms-lg-10">
            <ul class="nav nav-custom nav-tabs nav-line-tabs nav-line-tabs-2x border-0 fs-4 fw-semibold mb-8"
                role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link text-active-primary pb-3 lh-1" data-bs-toggle="tab" href="#tab-1"
                        aria-selected="true" role="tab" data-tab="tab-content-1">Tractor Trailer & Driver</a>
                </li>

                <li class="nav-item" role="presentation">
                    <a class="nav-link text-active-primary pb-3 lh-1" data-bs-toggle="tab" href="#tab-2"
                        aria-selected="false" role="tab" data-tab="tab-content-2" tabindex="-1">Events &amp; Logs</a>
                </li>
            </ul>

            <div class="tab-content" id="myTabContent">

                <div class="tab-pane fade tab-content-1 d-none" role="tabpanel">
                    <div class="card card-flush pt-3 mb-5 mb-lg-10" data-kt-subscriptions-form="pricing">
                        <div class="card-header">
                            <div class="card-title">
                                <h2 class="fw-bold">Tractor &  Trailer Details</h2>
                            </div>
                            <div class="card-toolbar">
                                <?php if(!$data['tractor']): ?>
                                    <button class="btn btn-sm btn-flex btn-light-primary update_column" data-url="/services/tractor_trailer/update_column" data-column="tractor_id" data-search="tractor" modal-id="#modal_search">
                                        <i class="ki-outline ki-plus fs-3"></i>
                                        Add Tractor
                                    </button>
                                <?php elseif(!$data['trailer']): ?>
                                    <button class="btn btn-sm btn-flex btn-light-primary update_column" data-url="/services/tractor_trailer/update_column" data-column="trailer_id" data-search="trailer" modal-id="#modal_search">
                                        <i class="ki-outline ki-plus fs-3"></i>
                                        Add Trailer
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="card-body pt-0">

                            <?php if($data['tractor'] !=null): ?>
                                
                                <div class="py-1">
                                    <div class="py-2 d-flex flex-stack flex-wrap">
                                        <div class="py-3 d-flex align-items-center collapsible toggle "
                                            data-bs-toggle="collapse" data-bs-target="#collapse-content-1">
                                            <div class="btn btn-sm btn-icon btn-active-color-primary ms-n3 me-2">
                                                <i class="ki-duotone ki-minus-square toggle-on text-primary fs-2">
                                                    <span class="path1"></span>
                                                    <span class="path2"></span>
                                                </i>
                                                <i class="ki-duotone ki-plus-square toggle-off fs-2">
                                                    <span class="path1"></span>
                                                    <span class="path2"></span>
                                                    <span class="path3"></span>
                                                </i>
                                            </div>
                                            <div class="me-3">
                                                <div class="d-flex align-items-center fw-bold"><?php echo e($data['tractor']); ?>

                                                </div>
                                                <div class="text-muted">Tractor</div>
                                            </div>
                                        </div>
                                        <div class="d-flex my-0 ms-9">
                                            <a href="#" class="btn btn-icon btn-light-primary w-30px h-30px me-3" data-bs-toggle="modal"
                                                data-bs-target="#kt_modal_new_card">
                                                <span data-bs-toggle="tooltip" data-bs-trigger="hover" aria-label="Edit" data-bs-original-title="Edit Details"
                                                    data-kt-initialized="1">
                                                    <i class="ki-outline ki-pencil fs-3"></i> </span>
                                            </a>
                                            <?php if($data['trailer']): ?>
                                                <a href="#" class="btn btn-icon btn-light-danger w-30px h-30px me-3 remove" data-url="/services/tractor_trailer/remove"
                                                    data-action="Disconnect this tractor to trailer?" data-column="tractor_id"  data-bs-toggle="tooltip"
                                                    data-bs-original-title="Decouple"
                                                    data-kt-initialized="1">
                                                    <i class="ki-outline ki-trash fs-3"></i>
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div id="collapse-content-1" class="collapse show fs-6 ps-10">
                                        <div class="d-flex flex-wrap py-3">
                                            <div class="flex-equal me-5">
                                                <table class="table table-flush fw-semibold gy-1">
                                                    <tr class="pb-3">
                                                        <td class="text-muted min-w-125px w-125px">Body No</td>
                                                        <td class="text-gray-800"><?php echo e($data['tractor_body_no']); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-muted min-w-125px w-125px">Plate No</td>
                                                        <td class="text-gray-800"><?php echo e($data['tractor_plate_no']); ?></td>
                                                    </tr>
                                                </table>
                                            </div>
                                            <div class="flex-equal ">
                                                <table class="table table-flush fw-semibold gy-1">
                                                    <tr class="pb-3">
                                                        <td class="text-muted min-w-125px w-125px">Status</td>
                                                        <td class="text-gray-800">
                                                            <div class="badge badge-light-<?php echo e($data['tractor_status'][1]); ?>"><?php echo e($data['tractor_status'][0]); ?></div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-muted min-w-125px w-125px">Remarks</td>
                                                        <td class="text-gray-800"><?php echo e($data['tractor_remarks']??'--'); ?></td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="separator separator-dashed"></div>
                            <?php endif; ?>

                            <?php if($data['trailer']): ?>
                                
                                <div class="py-1">
                                    <div class="py-2 d-flex flex-stack flex-wrap">
                                        <div class="d-flex align-items-center collapsible toggle active" data-bs-toggle="collapse" data-bs-target="#collapse-content-2">
                                            <div class="btn btn-sm btn-icon btn-active-color-primary ms-n3 me-2">
                                                <i class="ki-duotone ki-minus-square toggle-on text-primary fs-2">
                                                    <span class="path1"></span>
                                                    <span class="path2"></span>
                                                </i>
                                                <i class="ki-duotone ki-plus-square toggle-off fs-2">
                                                    <span class="path1"></span>
                                                    <span class="path2"></span>
                                                    <span class="path3"></span>
                                                </i>
                                            </div>
                                            <div class="me-3">
                                                <div class="d-flex align-items-center fw-bold"><?php echo e($data['trailer_plate_no']); ?>

                                                </div>
                                                <div class="text-muted">Trailer</div>
                                            </div>
                                        </div>
                                        <div class="d-flex my-0 ms-9">
                                            <a href="#" class="btn btn-icon btn-light-primary w-30px h-30px me-3 edit" data-bs-toggle="modal"
                                                data-bs-target="#kt_modal_new_card">
                                                <span data-bs-toggle="tooltip" data-bs-trigger="hover" aria-label="Edit Tractor" data-bs-original-title="Edit Details"
                                                    data-kt-initialized="1">
                                                    <i class="ki-outline ki-pencil fs-3"></i> </span>
                                            </a>
                                            <?php if($data['tractor']): ?>
                                                <a href="#" class="btn btn-icon btn-light-danger w-30px h-30px me-3 remove" data-url="/services/tractor_trailer/remove"
                                                    data-action="Disconnect this trailer to tractor?" data-column="trailer_id" data-bs-toggle="tooltip"
                                                    data-bs-original-title="Decouple"
                                                    data-kt-initialized="1">
                                                    <i class="ki-outline ki-trash fs-3"></i>
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div id="collapse-content-2" class="collapse fs-6 ps-10 collapse show">
                                        <div class="d-flex flex-wrap py-3">

                                            <div class="flex-equal">
                                                <table class="table table-flush fw-semibold gy-1">
                                                    <tr class="pb-3">
                                                        <td class="text-muted min-w-125px w-125px">Trailer Type</td>
                                                        <td class="text-gray-800"><?php echo e($data['trailer_type']); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-muted min-w-125px w-125px">Plate No</td>
                                                        <td class="text-gray-800"><?php echo e($data['trailer_plate_no']); ?></td>
                                                    </tr>
                                                </table>
                                            </div>

                                            <div class="flex-equal">
                                                <table class="table table-flush fw-semibold gy-1">
                                                    <tr class="pb-3">
                                                        <td class="text-muted min-w-125px w-125px">Status</td>
                                                        <td class="text-gray-800">
                                                            <div class="badge badge-light-<?php echo e($data['trailer_status'][1]); ?>"><?php echo e($data['trailer_status'][0]); ?></div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-muted min-w-125px w-125px">Remarks</td>
                                                        <td class="text-gray-800"><?php echo e($data['trailer_remarks'] ??'--'); ?></td>
                                                    </tr>
                                                </table>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>

                        </div>
                    </div>

                    
                    <div class="card card-flush pt-3 mb-5 mb-xl-10">
                        <div class="card-header">
                            <div class="card-title">
                                <h2>Driver's Details</h2>
                            </div>
                            <div class="card-toolbar">
                                <?php if(!isset($data['drivers'][0]) || !isset($data['drivers'][1])): ?>
                                    <button class="btn btn-sm btn-flex btn-light-primary update_column" data-url="/services/tractor_trailer/update_column" data-column=<?php if($data['drivers'][0]['column']=='sdriver'): ?><?php echo e('pdriver'); ?> <?php else: ?> <?php echo e('sdriver'); ?> <?php endif; ?>  data-search="driver" modal-id="#modal_search">
                                        <i class="ki-outline ki-plus fs-4"></i>
                                        Add Driver
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table id=""
                                    class="table align-middle table-row-dashed fs-6 fw-bold gs-0 gy-4 p-0 m-0">
                                    <thead class="border-bottom border-gray-200 fs-7 text-uppercase fw-bold">
                                        <tr class="text-start text-gray-500">
                                            <th class="">#</th>
                                            <th class="">Employee No.</th>
                                            <th class="">Drivers</th>
                                            <th class="">Mobile No.</th>
                                            <th class="">License No.</th>
                                            <th class="">Remarks</th>
                                            <th class="">Status</th>
                                            <th class="min-w-100px text-end pe-7 dt-orderable-none">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="fs-6 fw-semibold text-gray-600">
                                        <?php $__currentLoopData = $data['drivers']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $driver): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td>
                                                <?php echo e($key+1); ?>

                                            </td>
                                            <td>
                                                <a href="#" class="text-gray-600 text-hover-primary mb-1">
                                                    <?php echo e($driver['emp_no']); ?>

                                                </a>
                                            </td>
                                            <td>
                                               <div class="d-flex align-items-center">
                                                    <div class="text-gray-800">
                                                        <?php echo e($driver['name']); ?>

                                                    </div>
                                                    <div class="badge badge-light-<?php echo e($driver['column']=='pdriver'?'primary':'info'); ?> ms-5">Driver <?php echo e($driver['column']=='pdriver'?'1':'2'); ?></div>
                                                </div>
                                            </td>
                                            <td>
                                                <?php echo e($driver['mobile_no']); ?>

                                            </td>
                                            <td>
                                                <?php echo e($driver['license_no']); ?>

                                            </td>
                                            <td>
                                                <?php echo e($driver['remarks']); ?>

                                            </td>
                                            <td>
                                                <div class="badge badge-light-<?php echo e($driver['status'][1]); ?>"><?php echo e($driver['status'][0]); ?></div>
                                            </td>
                                            <td class="text-end">
                                                <div class="">
                                                    <a href="#" class="btn btn-icon btn-light-primary w-30px h-30px" data-bs-toggle="tooltip"
                                                        data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end" aria-label="More Options"
                                                        data-bs-original-title="More Options" data-kt-initialized="1">
                                                        <i class="ki-outline ki-setting-3 fs-3"></i>
                                                    </a>
                                                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold w-150px py-3"
                                                        data-kt-menu="true">
                                                        <div class="menu-item px-3">
                                                            <a href="#" class="menu-link px-3" data-kt-payment-mehtod-action="set_as_primary">
                                                                Set as Primary
                                                            </a>
                                                        </div>
                                                    </div>
                                                    <?php if(count($data['drivers'])==2): ?>
                                                        <a href="#" class="btn btn-icon btn-light-danger w-30px h-30px me-3 remove" data-url="/services/tractor_trailer/remove"
                                                            data-action="Do you want to remove this driver ?" data-column="<?php echo e($driver['column']); ?>"  data-bs-toggle="tooltip"
                                                            data-bs-original-title="Remove Driver"
                                                            data-kt-initialized="1">
                                                            <i class="ki-outline ki-trash fs-3"></i>
                                                        </a>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade tab-content-2 d-none" role="tabpanel">
                    <div class="card pt-4 mb-6 mb-xl-9">
                        <div class="card-header border-0">
                            <div class="card-title">
                                <h2>Events</h2>
                            </div>
                            <div class="card-toolbar">
                                <button type="button" class="btn btn-sm btn-light-primary">
                                    <i class="ki-duotone ki-cloud-download fs-3"><span class="path1"></span><span
                                            class="path2"></span></i>
                                    Download Report
                                </button>
                            </div>
                        </div>
                        <div class="card-body py-0">
                            <table class="table align-middle table-row-dashed fs-6 text-gray-600 fw-semibold gy-5"
                                id="kt_table_customers_events">
                                <tbody>
                                    <tr>
                                        <td class="min-w-400px">
                                            Invoice <a href="#"
                                                class="fw-bold text-gray-900 text-hover-primary me-1">#DER-45645</a>
                                            status has changed from <span class="badge badge-light-info me-1">In
                                                Progress</span> to <span class="badge badge-light-primary">In
                                                Transit</span>
                                        </td>
                                        <td class="pe-0 text-gray-600 text-end min-w-200px">
                                            10 Nov 2023, 6:05 pm </td>
                                    </tr>
                                    <tr>
                                        <td class="min-w-400px">
                                            <a href="#" class="text-gray-600 text-hover-primary me-1">Max Smith</a> has
                                            made payment to <a href="#"
                                                class="fw-bold text-gray-900 text-hover-primary">#SDK-45670</a>
                                        </td>
                                        <td class="pe-0 text-gray-600 text-end min-w-200px">
                                            19 Aug 2023, 5:30 pm </td>
                                    </tr>
                                    <tr>
                                        <td class="min-w-400px">
                                            <a href="#" class="text-gray-600 text-hover-primary me-1">Emma Smith</a> has
                                            made payment to <a href="#"
                                                class="fw-bold text-gray-900 text-hover-primary">#XRS-45670</a>
                                        </td>
                                        <td class="pe-0 text-gray-600 text-end min-w-200px">
                                            05 May 2023, 5:20 pm </td>
                                    </tr>
                                    <tr>
                                        <td class="min-w-400px">
                                            Invoice <a href="#"
                                                class="fw-bold text-gray-900 text-hover-primary me-1">#LOP-45640</a> has
                                            been <span class="badge badge-light-danger">Declined</span>
                                        </td>
                                        <td class="pe-0 text-gray-600 text-end min-w-200px">
                                            15 Apr 2023, 9:23 pm </td>
                                    </tr>
                                    <tr>
                                        <td class="min-w-400px">
                                            <a href="#" class="text-gray-600 text-hover-primary me-1">Melody Macy</a>
                                            has made payment to <a href="#"
                                                class="fw-bold text-gray-900 text-hover-primary">#XRS-45670</a>
                                        </td>
                                        <td class="pe-0 text-gray-600 text-end min-w-200px">
                                            19 Aug 2023, 10:10 pm </td>
                                    </tr>
                                    <tr>
                                        <td class="min-w-400px">
                                            Invoice <a href="#"
                                                class="fw-bold text-gray-900 text-hover-primary me-1">#DER-45645</a>
                                            status has changed from <span class="badge badge-light-info me-1">In
                                                Progress</span> to <span class="badge badge-light-primary">In
                                                Transit</span>
                                        </td>
                                        <td class="pe-0 text-gray-600 text-end min-w-200px">
                                            25 Jul 2023, 5:20 pm </td>
                                    </tr>
                                    <tr>
                                        <td class="min-w-400px">
                                            Invoice <a href="#"
                                                class="fw-bold text-gray-900 text-hover-primary me-1">#LOP-45640</a> has
                                            been <span class="badge badge-light-danger">Declined</span>
                                        </td>
                                        <td class="pe-0 text-gray-600 text-end min-w-200px">
                                            24 Jun 2023, 8:43 pm </td>
                                    </tr>
                                    <tr>
                                        <td class="min-w-400px">
                                            <a href="#" class="text-gray-600 text-hover-primary me-1">Sean Bean</a> has
                                            made payment to <a href="#"
                                                class="fw-bold text-gray-900 text-hover-primary">#XRS-45670</a>
                                        </td>
                                        <td class="pe-0 text-gray-600 text-end min-w-200px">
                                            10 Mar 2023, 10:10 pm </td>
                                    </tr>
                                    <tr>
                                        <td class="min-w-400px">
                                            Invoice <a href="#"
                                                class="fw-bold text-gray-900 text-hover-primary me-1">#DER-45645</a>
                                            status has changed from <span class="badge badge-light-info me-1">In
                                                Progress</span> to <span class="badge badge-light-primary">In
                                                Transit</span>
                                        </td>
                                        <td class="pe-0 text-gray-600 text-end min-w-200px">
                                            05 May 2023, 10:10 pm </td>
                                    </tr>
                                    <tr>
                                        <td class="min-w-400px">
                                            Invoice <a href="#"
                                                class="fw-bold text-gray-900 text-hover-primary me-1">#LOP-45640</a> has
                                            been <span class="badge badge-light-danger">Declined</span>
                                        </td>
                                        <td class="pe-0 text-gray-600 text-end min-w-200px">
                                            25 Jul 2023, 11:05 am </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <?php echo $__env->make('layout.dispatcher.shared.resources.modal.modal_tractor_trailer_info', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
</div>


<?php /**PATH C:\Users\Andrei\Desktop\TMS_NEW\tms\resources\views/layout/dispatcher/shared/resources/tractor_trailer_info.blade.php ENDPATH**/ ?>