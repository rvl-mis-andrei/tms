
<div class="card h-xl-100">
    <div class="card-header py-5">
        <div class="card-toolbar">
            <div class="d-flex flex-stack flex-wrap gap-4">
                <div class="position-relative my-1 me-2">
                    <i class="ki-duotone ki-magnifier fs-2 position-absolute top-50 translate-middle-y ms-4">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                    <input type="text" class="form-control form-control-sm fs-7 ps-12 search" placeholder="Press Enter to search . . ." />
                </div>
                <div class="d-flex align-items-center fw-bold">
                <div class="text-gray-400 fs-7 me-2">Filter: </div>
                    <div class="px-4 text-dark">
                        <input class="form-control form-control-transparent form-control-sm date_filter cursor-pointer fw-bold" placeholder="Pick date rage" readonly/>
                    </div>
                </div>
                <div class="d-flex align-items-center fw-bold">
                    <div class="text-gray-400 fs-7 me-2">Status: </div>
                    <select class="form-select form-select-transparent text-dark fs-7 lh-1 fw-bold py-0 ps-3 w-auto select_filter"
                        data-control="select2" data-hide-search="true" data-placeholder="Show All" name="status_filter">
                        <option value="Show All" selected>Show All</option>
                        <option value="1">On Trip</option>
                        <option value="2">Loaded</option>
                        <option value="3">Delivered</option>
                        <option value="4">Downtruck</option>
                    </select>
                </div>
                <div class="d-flex align-items-center fw-bold">
                    <div class="text-gray-400 fs-7 me-2">Type: </div>
                    <select class="form-select form-select-transparent text-dark fs-7 lh-1 fw-bold py-0 ps-3 w-auto select_filter"
                        data-control="select2" data-hide-search="true" data-placeholder="Show All" name="type_filter">
                        <option value="Show All" selected>Show All</option>
                        <option value="SVC">SVC</option>
                        <option value="BVC">BVC</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="card-title">
        </div>
    </div>
    <div class="card-body">
        <!--begin::Table-->
        <?php if($data['truckTrips'] && $data['maxTrips']): ?>
            <table class="table align-middle table-row-dashed fs-6 gy-5">
                <thead>
                    <tr>
                        <th class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">TRACTOR</th>
                        <th class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">TRAILER</th>
                        <?php for($i = 1; $i <= $data['maxTrips']; $i++): ?>
                            <th class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">TRIP <?php echo e($i); ?></th>
                        <?php endfor; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $data['truckTrips']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td>
                                <div class="d-flex flex-column">
                                    <a href="javascript:;" class="text-gray-800 text-hover-primary mb-1">
                                        <?php echo e($data['tractor_plate_no']); ?>

                                    </a>
                                    <span><?php echo e($data['tractor_body_no']); ?></span>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex flex-column">
                                    <a href="javascript:;" class="text-gray-800 text-hover-primary mb-1">
                                        <?php echo e($data['trailer_plate_no']); ?>

                                    </a>
                                    <span><?php echo e($data['trailer_type']); ?></span>
                                </div>
                            </td>
                        <?php $__currentLoopData = $data['trips']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $truckTrips): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <td>
                                <?php $__currentLoopData = $truckTrips; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $trips): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="badge badge-success fw-bold mb-1"><?php echo e($trips); ?></div><br>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </td>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        <?php else: ?>
        <div id="empty_state_wrapper" >
            <div class="card-px text-center pt-15 pb-15">
                <h2 class="fs-2x fw-bold mb-0" id="empty_state_title">Nothing in here</h2>
                <p class="text-gray-400 fs-4 fw-semibold py-7" id="empty_state_subtitle">
                    No results found
                </p>

            </div>
            <div class="text-center pb-15 px-5">
                <img src="<?php echo e(asset('assets/media/illustrations/sketchy-1/16.png')); ?>" alt="" class="mw-100 h-200px h-sm-325px">
            </div>
        </div>
        <?php endif; ?>
        
        <!--end::Table-->
    </div>
</div>

<?php /**PATH C:\Users\Andrei\Desktop\TMS_NEW\tms\resources\views/cluster_b/planner/dashboard/trip_monitoring.blade.php ENDPATH**/ ?>