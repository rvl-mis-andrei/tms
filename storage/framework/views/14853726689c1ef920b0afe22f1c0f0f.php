<div id="kt_app_header" class="app-header " data-kt-sticky="true" data-kt-sticky-activate="{default: false, lg: true}" data-kt-sticky-name="app-header-sticky" data-kt-sticky-offset="{default: false, lg: '300px'}">

    <div class="app-container  container-fluid d-flex align-items-stretch justify-content-between border border-bottom-1"
    id="kt_app_header_container">
    <div class="app-header-logo d-flex align-items-center me-lg-9">
        <div class="btn btn-icon btn-color-gray-500 btn-active-color-primary w-35px h-35px ms-n2 me-2 d-flex d-lg-none"
            id="kt_app_header_menu_toggle">
            <i class="bi bi-list fs-1"></i>
        </div>
        <a href="index.html">
            
        </a>
    </div>
    <div class="d-flex align-items-stretch justify-content-between flex-lg-grow-1">
        <div class="d-flex align-items-stretch" id="kt_app_header_menu_wrapper">
            <div class="app-header-menu app-header-mobile-drawer align-items-stretch"
                data-kt-drawer="true" data-kt-drawer-name="app-header-menu"
                data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true"
                data-kt-drawer-width="{default:'200px', '300px': '250px'}"
                data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_app_header_menu_toggle"
                data-kt-swapper="true" data-kt-swapper-mode="prepend"
                data-kt-swapper-parent="{default: '#kt_app_body', lg: '#kt_app_header_menu_wrapper'}">
                <div class="menu menu-rounded menu-column menu-lg-row menu-active-bg menu-title-gray-600 menu-state-dark menu-arrow-gray-400 fw-semibold fw-semibold fs-6 align-items-stretch my-5 my-lg-0 px-2 px-lg-0"
                    id="#kt_app_header_menu" data-kt-menu="true">
                    <?php $__currentLoopData = $result; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if(count($data['file_layer']) == 0): ?>
                            <div class="menu-item me-0 me-lg-2">
                                <span class="menu-link navbar" id="<?php echo e($data['href']); ?>" data-page="<?php echo e($data['href']); ?>" data-link="dispatcher/<?php echo e($data['href']); ?>">
                                    <span class="menu-title"><?php echo e($data['name']); ?></span>
                                </span>
                            </div>
                        <?php else: ?>
                            <div data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="bottom-start"
                                class="menu-item menu-lg-down-accordion menu-sub-lg-down-indention me-0 me-lg-2">
                                <span class="menu-link">
                                    <span class="menu-title"><?php echo e($data['name']); ?></span>
                                    <span class="menu-arrow d-lg-none"></span>
                                </span>
                                <div class="menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown px-lg-2 py-lg-4 w-lg-200px">
                                    <?php $__currentLoopData = $data['file_layer']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $layer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="menu-item">
                                            <a class="menu-link navbar" id="<?php echo e($layer['href']); ?>" data-page="<?php echo e($layer['href']); ?>" data-link="dispatcher/<?php echo e($layer['href']); ?>" href="script:;">
                                                <span class="menu-icon">
                                                    <i class="ki-outline ki-rocket fs-2"></i>
                                                </span>
                                                <span class="menu-title"><?php echo e($layer['name']); ?></span>
                                            </a>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>
        <div class="app-navbar flex-shrink-0">
            <div class="app-navbar-item ms-1 ms-lg-4" id="kt_header_user_menu_toggle">
                <div class="symbol symbol-circle symbol-40px overflow-hidden text-center rounded-1"
                data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-attach="parent"
                data-kt-menu-placement="bottom-end">
                    <a href="javascript:;">
                        <div class="symbol-label fs-3 bg-light-success text-success rounded-0">
                                M
                        </div>
                     </a>
                </div>
                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-color fw-semibold py-4 fs-6 w-275px"
                    data-kt-menu="true">
                    <div class="menu-item px-3">
                        <div class="menu-content d-flex align-items-center px-3">
                            <div class="symbol symbol-50px me-5">
                                
                            </div>
                            <div class="d-flex flex-column">
                                <div class="fw-bold d-flex align-items-center fs-5">
                                    <?php echo e(Auth::user()->employee->fullname()); ?>

                                    <span class="badge badge-light-success fw-bold fs-8 px-2 py-1 ms-2">
                                        Planner
                                    </span>
                                </div>
                                <a href="#" class="fw-semibold text-muted text-hover-primary fs-7">
                                    <?php echo e(Auth::user()->username); ?>

                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="separator my-2"></div>
                    <div class="menu-item px-5">
                        <a href="account/overview.html" class="menu-link px-5">
                            My Profile
                        </a>
                    </div>
                    <div class="menu-item px-5" data-kt-menu-trigger="{default: 'click', lg: 'hover'}"
                        data-kt-menu-placement="left-start" data-kt-menu-offset="-15px, 0">
                        <a href="#" class="menu-link px-5">
                            <span class="menu-title position-relative">
                                Mode
                                <span class="ms-5 position-absolute translate-middle-y top-50 end-0">
                                    <i class="ki-outline ki-night-day theme-light-show fs-2"></i>
                                    <i class="ki-outline ki-moon theme-dark-show fs-2"></i>
                                </span>
                            </span>
                        </a>
                        <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-title-gray-700 menu-icon-gray-500 menu-active-bg menu-state-color fw-semibold py-4 fs-base w-150px"
                            data-kt-menu="true" data-kt-element="theme-mode-menu">
                            <div class="menu-item px-3 my-0">
                                <a href="#" class="menu-link px-3 py-2" data-kt-element="mode"
                                    data-kt-value="light">
                                    <span class="menu-icon" data-kt-element="icon">
                                        <i class="ki-outline ki-night-day fs-2"></i> </span>
                                    <span class="menu-title">
                                        Light
                                    </span>
                                </a>
                            </div>
                            <div class="menu-item px-3 my-0">
                                <a href="#" class="menu-link px-3 py-2" data-kt-element="mode"
                                    data-kt-value="dark">
                                    <span class="menu-icon" data-kt-element="icon">
                                        <i class="ki-outline ki-moon fs-2"></i> </span>
                                    <span class="menu-title">
                                        Dark
                                    </span>
                                </a>
                            </div>
                            <div class="menu-item px-3 my-0">
                                <a href="#" class="menu-link px-3 py-2" data-kt-element="mode"
                                    data-kt-value="system">
                                    <span class="menu-icon" data-kt-element="icon">
                                        <i class="ki-outline ki-screen fs-2"></i> </span>
                                    <span class="menu-title">
                                        System
                                    </span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="menu-item px-5">
                        <a target="_blank" class="menu-link px-5" onclick="event.preventDefault();
                            document.getElementById('logout').submit();">
                                <?php echo e(__('Sign Out')); ?>

                        </a>
                        <form id="logout" action="<?php echo e(route('cco-b.logout')); ?>" method="POST" class="d-none">
                            <?php echo csrf_field(); ?>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<?php /**PATH C:\Users\Andrei\Desktop\TMS_NEW\tms\resources\views/layout/planner/navbar.blade.php ENDPATH**/ ?>