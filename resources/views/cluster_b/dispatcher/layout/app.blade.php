<!DOCTYPE html>
<html lang="en">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<head>
    <title>Trip Monitoring System | Dashboard</title>
    <meta charset="utf-8" />
    <meta content="{{ csrf_token() }}" name="csrf-token" id="csrf-token">
    <link rel="canonical" href="../demo1/index.html" />
    <link rel="shortcut icon" href="assets/media/logos/favicon.ico" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
    <link href="{{ asset('assets/plugins/custom/fullcalendar/fullcalendar.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />

    <script>
        var defaultThemeMode = "light";
	    var themeMode;

        if ( document.documentElement ) {
            if ( document.documentElement.hasAttribute("data-bs-theme-mode")) {
                themeMode = document.documentElement.getAttribute("data-bs-theme-mode");
            } else {
                if ( localStorage.getItem("data-bs-theme") !== null ) {
                    themeMode = localStorage.getItem("data-bs-theme");
                } else {
                    themeMode = defaultThemeMode;
                }
            }

            if (themeMode === "system") {
                themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light";
            }

            document.documentElement.setAttribute("data-bs-theme", themeMode);
        }
    </script>

</head>
<body id="kt_app_body" data-kt-app-header-fixed-mobile="true" data-kt-app-sidebar-enabled="true"
    data-kt-app-sidebar-fixed="false" data-kt-app-sidebar-push-header="true" data-kt-app-sidebar-push-toolbar="true"
    data-kt-app-sidebar-push-footer="true" data-kt-app-toolbar-enabled="true" class="app-default">
    <div class="d-flex flex-column flex-root app-root" id="kt_app_root">
        <!--begin::Page-->
        <div class="app-page  flex-column flex-column-fluid " id="kt_app_page">

            <!--begin::Header-->
           @include('cluster_b.dispatcher.layout.navbar');
            <!--end::Header-->

            <!--begin::Wrapper-->
            <div class="app-wrapper d-flex " id="kt_app_wrapper">
                <!--begin::Wrapper container-->
                <div class="app-container  container-fluid d-flex ">
                    <!--begin::Main-->
                    <div class="app-main flex-column flex-row-fluid " id="kt_app_main">
                        <!--begin::Content wrapper-->
                        <div class="d-flex flex-column flex-column-fluid">

                            <!--begin::Toolbar-->
                            <div id="kt_app_toolbar" class="app-toolbar  pt-7 pt-lg-10 ">

                                <!--begin::Toolbar wrapper-->
                                <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">



                                    <!--begin::Page title-->
                                    <div class="page-title d-flex flex-column justify-content-center gap-1 me-3">
                                        <!--begin::Breadcrumb-->
                                        <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7">

                                            <!--begin::Item-->
                                            <li class="breadcrumb-item text-gray-700 fw-bold lh-1 mx-n1">
                                                <a href="index.html" class="text-hover-primary">
                                                    <i class="ki-outline ki-home text-gray-700 fs-6"></i> </a>
                                            </li>
                                            <!--end::Item-->

                                            <!--begin::Item-->
                                            <li class="breadcrumb-item">
                                                <i class="ki-outline ki-right fs-7 text-gray-700"></i>
                                            </li>
                                            <!--end::Item-->


                                            <!--begin::Item-->
                                            <li class="breadcrumb-item text-gray-700 fw-bold lh-1 mx-n1">
                                                Account </li>
                                            <!--end::Item-->

                                            <!--begin::Item-->
                                            <li class="breadcrumb-item">
                                                <i class="ki-outline ki-right fs-7 text-gray-700"></i>
                                            </li>
                                            <!--end::Item-->


                                            <!--begin::Item-->
                                            <li class="breadcrumb-item text-gray-500 mx-n1">
                                                Overview </li>
                                            <!--end::Item-->


                                        </ul>
                                        <!--end::Breadcrumb-->

                                        <!--begin::Title-->
                                        <h1
                                            class="page-heading d-flex flex-column justify-content-center text-dark fw-bolder fs-3 m-0">
                                            eCommerce Dashboard
                                        </h1>
                                        <!--end::Title-->
                                    </div>
                                    <!--end::Page title-->
                                    <!--begin::Actions-->
                                    <div class="d-flex align-items-center gap-2 gap-lg-3">
                                        <a href="#"
                                            class="btn btn-flex btn-color-gray-700 btn-active-color-primary bg-light h-40px fs-7 fw-bold"
                                            data-bs-toggle="modal" data-bs-target="#kt_modal_view_users">
                                            History
                                        </a>

                                        <a href="#" class="btn btn-sm btn-flex btn-secondary align-self-center px-3"
                                            data-bs-toggle="modal" data-bs-target="#kt_modal_invite_friends">
                                            <i class="ki-outline ki-plus-square fs-3"></i> Invite
                                        </a>
                                    </div>
                                    <!--end::Actions-->
                                </div>
                                <!--end::Toolbar wrapper-->
                            </div>
                            <!--end::Toolbar-->

                            <!--begin::Content-->
                            <div id="kt_app_content" class="app-content  flex-column-fluid ">

                                <!--begin::Row-->
                                <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
                                    <!--begin::Col-->
                                    <div class="col-xl-4">

                                        <!--begin::Engage widget 15-->
                                        <div class="card h-md-100" dir="ltr">
                                            <!--begin::Body-->
                                            <div class="card-body d-flex flex-column flex-center">
                                                <!--begin::Heading-->
                                                <div class="mb-2">
                                                    <!--begin::Title-->
                                                    <h1 class="fw-semibold text-gray-800 text-center lh-lg">
                                                        Have you tried <br /> new
                                                        <span class="fw-bolder"> Mobile Application ?</span>
                                                    </h1>
                                                    <!--end::Title-->

                                                    <!--begin::Illustration-->
                                                    <div class="py-10 text-center">
                                                        <img src="assets/media/svg/illustrations/easy/1.svg"
                                                            class="theme-light-show w-200px" alt="" />
                                                        <img src="assets/media/svg/illustrations/easy/1-dark.svg"
                                                            class="theme-dark-show w-200px" alt="" />
                                                    </div>
                                                    <!--end::Illustration-->
                                                </div>
                                                <!--end::Heading-->

                                                <!--begin::Links-->
                                                <div class="text-center mb-1">
                                                    <!--begin::Link-->
                                                    <a class="btn btn-sm btn-dark me-2"
                                                        data-bs-target="#kt_modal_create_app" data-bs-toggle="modal">
                                                        Try now </a>
                                                    <!--end::Link-->
                                                </div>
                                                <!--end::Links-->
                                            </div>
                                            <!--end::Body-->
                                        </div>
                                        <!--end::Engage widget 15-->

                                    </div>
                                    <!--end::Col-->

                                    <!--begin::Col-->
                                    <div class="col-xl-8">

                                        <!--begin::Chart Widget 46-->
                                        <div class="card card-flush h-lg-100">
                                            <!--begin::Header-->
                                            <div class="card-header pt-5">
                                                <div class="d-flex flex-center">
                                                    <!--begin::Title-->
                                                    <h3 class="card-title align-items-start">
                                                        <span class="card-label fw-bold text-gray-800">Instagram
                                                            Subscribers</span>
                                                    </h3>
                                                    <!--end::Title-->

                                                    <!--begin::Info-->
                                                    <div class="d-flex align-items-center px-5">
                                                        <!--begin::Item-->
                                                        <div class="d-flex align-items-center me-6">
                                                            <!--begin::Bullet-->
                                                            <span
                                                                class="rounded-1 bg-gray-800 me-2 h-10px w-10px"></span>
                                                            <!--end::Bullet-->

                                                            <!--begin::Label-->
                                                            <span class="fw-semibold fs-6 text-gray-600">Gained</span>
                                                            <!--end::Label-->
                                                        </div>
                                                        <!--ed::Item-->

                                                        <!--begin::Item-->
                                                        <div class="d-flex align-items-center">
                                                            <!--begin::Bullet-->
                                                            <span
                                                                class="rounded-1 bg-gray-500 me-2 h-10px w-10px"></span>
                                                            <!--end::Bullet-->

                                                            <!--begin::Label-->
                                                            <span class="fw-semibold fs-6 text-gray-600">Lost</span>
                                                            <!--end::Label-->
                                                        </div>
                                                        <!--ed::Item-->
                                                    </div>
                                                    <!--ed::Info-->
                                                </div>

                                                <!--begin::Toolbar-->
                                                <div class="card-toolbar">
                                                    <!--begin::Menu-->
                                                    <button
                                                        class="btn btn-icon btn-color-gray-400 btn-active-color-primary justify-content-end"
                                                        data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end"
                                                        data-kt-menu-overflow="true">
                                                        <i
                                                            class="ki-outline ki-dots-square fs-1 text-gray-400 me-n1"></i>
                                                    </button>


                                                    <!--begin::Menu 2-->
                                                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-semibold w-200px"
                                                        data-kt-menu="true">
                                                        <!--begin::Menu item-->
                                                        <div class="menu-item px-3">
                                                            <div class="menu-content fs-6 text-dark fw-bold px-3 py-4">
                                                                Quick Actions</div>
                                                        </div>
                                                        <!--end::Menu item-->

                                                        <!--begin::Menu separator-->
                                                        <div class="separator mb-3 opacity-75"></div>
                                                        <!--end::Menu separator-->

                                                        <!--begin::Menu item-->
                                                        <div class="menu-item px-3">
                                                            <a href="#" class="menu-link px-3">
                                                                New Ticket
                                                            </a>
                                                        </div>
                                                        <!--end::Menu item-->

                                                        <!--begin::Menu item-->
                                                        <div class="menu-item px-3">
                                                            <a href="#" class="menu-link px-3">
                                                                New Customer
                                                            </a>
                                                        </div>
                                                        <!--end::Menu item-->

                                                        <!--begin::Menu item-->
                                                        <div class="menu-item px-3" data-kt-menu-trigger="hover"
                                                            data-kt-menu-placement="right-start">
                                                            <!--begin::Menu item-->
                                                            <a href="#" class="menu-link px-3">
                                                                <span class="menu-title">New Group</span>
                                                                <span class="menu-arrow"></span>
                                                            </a>
                                                            <!--end::Menu item-->

                                                            <!--begin::Menu sub-->
                                                            <div class="menu-sub menu-sub-dropdown w-175px py-4">
                                                                <!--begin::Menu item-->
                                                                <div class="menu-item px-3">
                                                                    <a href="#" class="menu-link px-3">
                                                                        Admin Group
                                                                    </a>
                                                                </div>
                                                                <!--end::Menu item-->

                                                                <!--begin::Menu item-->
                                                                <div class="menu-item px-3">
                                                                    <a href="#" class="menu-link px-3">
                                                                        Staff Group
                                                                    </a>
                                                                </div>
                                                                <!--end::Menu item-->

                                                                <!--begin::Menu item-->
                                                                <div class="menu-item px-3">
                                                                    <a href="#" class="menu-link px-3">
                                                                        Member Group
                                                                    </a>
                                                                </div>
                                                                <!--end::Menu item-->
                                                            </div>
                                                            <!--end::Menu sub-->
                                                        </div>
                                                        <!--end::Menu item-->

                                                        <!--begin::Menu item-->
                                                        <div class="menu-item px-3">
                                                            <a href="#" class="menu-link px-3">
                                                                New Contact
                                                            </a>
                                                        </div>
                                                        <!--end::Menu item-->

                                                        <!--begin::Menu separator-->
                                                        <div class="separator mt-3 opacity-75"></div>
                                                        <!--end::Menu separator-->

                                                        <!--begin::Menu item-->
                                                        <div class="menu-item px-3">
                                                            <div class="menu-content px-3 py-3">
                                                                <a class="btn btn-primary  btn-sm px-4" href="#">
                                                                    Generate Reports
                                                                </a>
                                                            </div>
                                                        </div>
                                                        <!--end::Menu item-->
                                                    </div>
                                                    <!--end::Menu 2-->

                                                    <!--end::Menu-->
                                                </div>
                                                <!--end::Toolbar-->
                                            </div>
                                            <!--end::Header-->

                                            <!--begin::Body-->
                                            <div class="card-body pt-0 px-0">
                                                <!--begin::Chart-->
                                                <div id="kt_charts_widget_46" class="min-h-auto ps-4 pe-6 mb-3"
                                                    style="height: 350px" </div>
                                                    <!--end::Chart-->
                                                </div>
                                                <!--end::Body-->
                                            </div>
                                            <!--end::Chart Widget 46-->
                                        </div>
                                        <!--end::Col-->
                                    </div>
                                    <!--end::Row-->

                                    <!--begin::Row-->
                                    <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
                                        <!--begin::Col-->
                                        <div class="col-xl-4">

                                            <!--begin::Chart widget 45-->
                                            <div class="card card-flush h-xl-100 mb-xl-8">
                                                <!--begin::Header-->
                                                <div class="card-header pt-5">
                                                    <!--begin::Title-->
                                                    <h3 class="card-title fw-bold text-dark">Trends</h3>
                                                    <!--end::Title-->

                                                    <!--begin::Toolbar-->
                                                    <div class="card-toolbar">
                                                        <!--begin::Menu-->
                                                        <button type="button"
                                                            class="btn btn-sm btn-icon btn-color-primary btn-active-light-primary"
                                                            data-kt-menu-trigger="click"
                                                            data-kt-menu-placement="bottom-end">
                                                            <i class="ki-outline ki-element-plus fs-2"></i> </button>

                                                        <!--begin::Menu 3-->
                                                        <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-semibold w-200px py-3"
                                                            data-kt-menu="true">
                                                            <!--begin::Heading-->
                                                            <div class="menu-item px-3">
                                                                <div
                                                                    class="menu-content text-muted pb-2 px-3 fs-7 text-uppercase">
                                                                    Payments
                                                                </div>
                                                            </div>
                                                            <!--end::Heading-->

                                                            <!--begin::Menu item-->
                                                            <div class="menu-item px-3">
                                                                <a href="#" class="menu-link px-3">
                                                                    Create Invoice
                                                                </a>
                                                            </div>
                                                            <!--end::Menu item-->

                                                            <!--begin::Menu item-->
                                                            <div class="menu-item px-3">
                                                                <a href="#" class="menu-link flex-stack px-3">
                                                                    Create Payment

                                                                    <span class="ms-2" data-bs-toggle="tooltip"
                                                                        title="Specify a target name for future usage and reference">
                                                                        <i class="ki-outline ki-information fs-6"></i>
                                                                    </span>
                                                                </a>
                                                            </div>
                                                            <!--end::Menu item-->

                                                            <!--begin::Menu item-->
                                                            <div class="menu-item px-3">
                                                                <a href="#" class="menu-link px-3">
                                                                    Generate Bill
                                                                </a>
                                                            </div>
                                                            <!--end::Menu item-->

                                                            <!--begin::Menu item-->
                                                            <div class="menu-item px-3" data-kt-menu-trigger="hover"
                                                                data-kt-menu-placement="right-end">
                                                                <a href="#" class="menu-link px-3">
                                                                    <span class="menu-title">Subscription</span>
                                                                    <span class="menu-arrow"></span>
                                                                </a>

                                                                <!--begin::Menu sub-->
                                                                <div class="menu-sub menu-sub-dropdown w-175px py-4">
                                                                    <!--begin::Menu item-->
                                                                    <div class="menu-item px-3">
                                                                        <a href="#" class="menu-link px-3">
                                                                            Plans
                                                                        </a>
                                                                    </div>
                                                                    <!--end::Menu item-->

                                                                    <!--begin::Menu item-->
                                                                    <div class="menu-item px-3">
                                                                        <a href="#" class="menu-link px-3">
                                                                            Billing
                                                                        </a>
                                                                    </div>
                                                                    <!--end::Menu item-->

                                                                    <!--begin::Menu item-->
                                                                    <div class="menu-item px-3">
                                                                        <a href="#" class="menu-link px-3">
                                                                            Statements
                                                                        </a>
                                                                    </div>
                                                                    <!--end::Menu item-->

                                                                    <!--begin::Menu separator-->
                                                                    <div class="separator my-2"></div>
                                                                    <!--end::Menu separator-->

                                                                    <!--begin::Menu item-->
                                                                    <div class="menu-item px-3">
                                                                        <div class="menu-content px-3">
                                                                            <!--begin::Switch-->
                                                                            <label
                                                                                class="form-check form-switch form-check-custom form-check-solid">
                                                                                <!--begin::Input-->
                                                                                <input
                                                                                    class="form-check-input w-30px h-20px"
                                                                                    type="checkbox" value="1"
                                                                                    checked="checked"
                                                                                    name="notifications" />
                                                                                <!--end::Input-->

                                                                                <!--end::Label-->
                                                                                <span
                                                                                    class="form-check-label text-muted fs-6">
                                                                                    Recuring
                                                                                </span>
                                                                                <!--end::Label-->
                                                                            </label>
                                                                            <!--end::Switch-->
                                                                        </div>
                                                                    </div>
                                                                    <!--end::Menu item-->
                                                                </div>
                                                                <!--end::Menu sub-->
                                                            </div>
                                                            <!--end::Menu item-->

                                                            <!--begin::Menu item-->
                                                            <div class="menu-item px-3 my-1">
                                                                <a href="#" class="menu-link px-3">
                                                                    Settings
                                                                </a>
                                                            </div>
                                                            <!--end::Menu item-->
                                                        </div>
                                                        <!--end::Menu 3-->
                                                        <!--end::Menu-->
                                                    </div>
                                                    <!--end::Toolbar-->
                                                </div>
                                                <!--end::Header-->

                                                <!--begin::Body-->
                                                <div class="card-body d-flex justify-content-between flex-column pt-0">
                                                    <!--begin::Chart-->
                                                    <div class="m-0" id="kt_charts_widget_45" data-kt-chart-color="dark"
                                                        style="height: 90px"></div>
                                                    <!--end::Chart-->

                                                    <!--begin::Items-->
                                                    <div class="m-0">
                                                        <!--begin::Item-->
                                                        <div class="d-flex flex-stack mb-9">
                                                            <!--begin::Section-->
                                                            <div class="d-flex align-items-center me-2">
                                                                <!--begin::Symbol-->
                                                                <div class="symbol symbol-50px me-5">
                                                                    <div class="symbol-label bg-light">
                                                                        <img src="assets/media/svg/brand-logos/plurk.svg"
                                                                            alt="" class="h-50" alt="" />
                                                                    </div>
                                                                </div>
                                                                <!--end::Symbol-->

                                                                <!--begin::Title-->
                                                                <div>
                                                                    <a href="#"
                                                                        class="fs-6 text-gray-800 text-hover-primary fw-bold">Top
                                                                        Authors</a>
                                                                    <div class="fs-7 text-muted fw-semibold mt-1">
                                                                        Successful Fellas</div>
                                                                </div>
                                                                <!--end::Title-->
                                                            </div>
                                                            <!--end::Section-->

                                                            <!--begin::Label-->
                                                            <div
                                                                class="badge badge-light badge-lg fw-bold p-2 text-gray-600">
                                                                +82$</div>
                                                            <!--end::Label-->
                                                        </div>
                                                        <!--end::Item-->
                                                        <!--begin::Item-->
                                                        <div class="d-flex flex-stack mb-9">
                                                            <!--begin::Section-->
                                                            <div class="d-flex align-items-center me-2">
                                                                <!--begin::Symbol-->
                                                                <div class="symbol symbol-50px me-5">
                                                                    <div class="symbol-label bg-light">
                                                                        <img src="assets/media/svg/brand-logos/telegram-2.svg"
                                                                            alt="" class="h-50" alt="" />
                                                                    </div>
                                                                </div>
                                                                <!--end::Symbol-->

                                                                <!--begin::Title-->
                                                                <div>
                                                                    <a href="#"
                                                                        class="fs-6 text-gray-800 text-hover-primary fw-bold">Binford
                                                                        Ltd.</a>
                                                                    <div class="fs-7 text-muted fw-semibold mt-1">Most
                                                                        Successful </div>
                                                                </div>
                                                                <!--end::Title-->
                                                            </div>
                                                            <!--end::Section-->

                                                            <!--begin::Label-->
                                                            <div
                                                                class="badge badge-light badge-lg fw-bold p-2 text-gray-600">
                                                                +280$</div>
                                                            <!--end::Label-->
                                                        </div>
                                                        <!--end::Item-->
                                                        <!--begin::Item-->
                                                        <div class="d-flex flex-stack ">
                                                            <!--begin::Section-->
                                                            <div class="d-flex align-items-center me-2">
                                                                <!--begin::Symbol-->
                                                                <div class="symbol symbol-50px me-5">
                                                                    <div class="symbol-label bg-light">
                                                                        <img src="assets/media/svg/brand-logos/vimeo.svg"
                                                                            alt="" class="h-50" alt="" />
                                                                    </div>
                                                                </div>
                                                                <!--end::Symbol-->

                                                                <!--begin::Title-->
                                                                <div>
                                                                    <a href="#"
                                                                        class="fs-6 text-gray-800 text-hover-primary fw-bold">Biffco
                                                                        Enterprises</a>
                                                                    <div class="fs-7 text-muted fw-semibold mt-1">Most
                                                                        Successful Fellas</div>
                                                                </div>
                                                                <!--end::Title-->
                                                            </div>
                                                            <!--end::Section-->

                                                            <!--begin::Label-->
                                                            <div
                                                                class="badge badge-light badge-lg fw-bold p-2 text-gray-600">
                                                                +4500$</div>
                                                            <!--end::Label-->
                                                        </div>
                                                        <!--end::Item-->

                                                    </div>
                                                    <!--end::Items-->
                                                </div>
                                                <!--end::Body-->
                                            </div>
                                            <!--end::Chart widget 45-->



                                        </div>
                                        <!--end::Col-->

                                        <!--begin::Col-->
                                        <div class="col-xl-8">

                                            <!--begin::Table widget 8-->
                                            <div class="card h-xl-100">
                                                <!--begin::Header-->
                                                <div class="card-header position-relative py-0 border-bottom-2">
                                                    <!--begin::Nav-->
                                                    <ul class="nav nav-stretch nav-pills nav-pills-custom d-flex mt-3">
                                                        <!--begin::Nav item-->
                                                        <li class="nav-item p-0 ms-0 me-8">
                                                            <!--begin::Nav link-->
                                                            <a class="nav-link btn btn-color-muted px-0 "
                                                                data-bs-toggle="tab"
                                                                href="#kt_table_widget_7_tab_content_1">
                                                                <!--begin::Title-->
                                                                <span class="nav-text fw-semibold fs-4 mb-3">
                                                                    Monday </span>
                                                                <!--end::Title-->

                                                                <!--begin::Bullet-->
                                                                <span
                                                                    class="bullet-custom position-absolute z-index-2 w-100 h-2px top-100 bottom-n100 bg-primary rounded"></span>
                                                                <!--end::Bullet-->
                                                            </a>
                                                            <!--end::Nav link-->
                                                        </li>
                                                        <!--end::Nav item-->
                                                        <!--begin::Nav item-->
                                                        <li class="nav-item p-0 ms-0 me-8">
                                                            <!--begin::Nav link-->
                                                            <a class="nav-link btn btn-color-muted px-0 "
                                                                data-bs-toggle="tab"
                                                                href="#kt_table_widget_7_tab_content_2">
                                                                <!--begin::Title-->
                                                                <span class="nav-text fw-semibold fs-4 mb-3">
                                                                    Tuesday </span>
                                                                <!--end::Title-->

                                                                <!--begin::Bullet-->
                                                                <span
                                                                    class="bullet-custom position-absolute z-index-2 w-100 h-2px top-100 bottom-n100 bg-primary rounded"></span>
                                                                <!--end::Bullet-->
                                                            </a>
                                                            <!--end::Nav link-->
                                                        </li>
                                                        <!--end::Nav item-->
                                                        <!--begin::Nav item-->
                                                        <li class="nav-item p-0 ms-0 me-8">
                                                            <!--begin::Nav link-->
                                                            <a class="nav-link btn btn-color-muted px-0 show active"
                                                                data-bs-toggle="tab"
                                                                href="#kt_table_widget_7_tab_content_3">
                                                                <!--begin::Title-->
                                                                <span class="nav-text fw-semibold fs-4 mb-3">
                                                                    Wednesday </span>
                                                                <!--end::Title-->

                                                                <!--begin::Bullet-->
                                                                <span
                                                                    class="bullet-custom position-absolute z-index-2 w-100 h-2px top-100 bottom-n100 bg-primary rounded"></span>
                                                                <!--end::Bullet-->
                                                            </a>
                                                            <!--end::Nav link-->
                                                        </li>
                                                        <!--end::Nav item-->
                                                        <!--begin::Nav item-->
                                                        <li class="nav-item p-0 ms-0 me-8">
                                                            <!--begin::Nav link-->
                                                            <a class="nav-link btn btn-color-muted px-0 "
                                                                data-bs-toggle="tab"
                                                                href="#kt_table_widget_7_tab_content_4">
                                                                <!--begin::Title-->
                                                                <span class="nav-text fw-semibold fs-4 mb-3">
                                                                    Thursday </span>
                                                                <!--end::Title-->

                                                                <!--begin::Bullet-->
                                                                <span
                                                                    class="bullet-custom position-absolute z-index-2 w-100 h-2px top-100 bottom-n100 bg-primary rounded"></span>
                                                                <!--end::Bullet-->
                                                            </a>
                                                            <!--end::Nav link-->
                                                        </li>
                                                        <!--end::Nav item-->
                                                        <!--begin::Nav item-->
                                                        <li class="nav-item p-0 ms-0 me-8">
                                                            <!--begin::Nav link-->
                                                            <a class="nav-link btn btn-color-muted px-0 "
                                                                data-bs-toggle="tab"
                                                                href="#kt_table_widget_7_tab_content_5">
                                                                <!--begin::Title-->
                                                                <span class="nav-text fw-semibold fs-4 mb-3">
                                                                    Friday </span>
                                                                <!--end::Title-->

                                                                <!--begin::Bullet-->
                                                                <span
                                                                    class="bullet-custom position-absolute z-index-2 w-100 h-2px top-100 bottom-n100 bg-primary rounded"></span>
                                                                <!--end::Bullet-->
                                                            </a>
                                                            <!--end::Nav link-->
                                                        </li>
                                                        <!--end::Nav item-->

                                                    </ul>
                                                    <!--end::Nav-->

                                                    <!--begin::Toolbar-->
                                                    <div class="card-toolbar">
                                                        <!--begin::Daterangepicker(defined in src/js/layout/app.js)-->
                                                        <div data-kt-daterangepicker="true"
                                                            data-kt-daterangepicker-opens="left"
                                                            class="btn btn-sm btn-light d-flex align-items-center px-4">
                                                            <!--begin::Display range-->
                                                            <div class="text-gray-600 fw-bold">
                                                                Loading date range...
                                                            </div>
                                                            <!--end::Display range-->

                                                            <i class="ki-outline ki-calendar-8 fs-1 ms-2 me-0"></i>
                                                        </div>
                                                        <!--end::Daterangepicker-->
                                                    </div>
                                                    <!--end::Toolbar-->
                                                </div>
                                                <!--end::Header-->

                                                <!--begin::Body-->
                                                <div class="card-body">
                                                    <!--begin::Tab Content (ishlamayabdi)-->
                                                    <div class="tab-content mb-2">
                                                        <!--begin::Tap pane-->
                                                        <div class="tab-pane fade "
                                                            id="kt_table_widget_7_tab_content_1">
                                                            <!--begin::Table container-->
                                                            <div class="table-responsive">
                                                                <!--begin::Table-->
                                                                <table class="table align-middle">
                                                                    <!--begin::Table head-->
                                                                    <thead>
                                                                        <tr>
                                                                            <th class="min-w-150px p-0"></th>
                                                                            <th class="min-w-200px p-0"></th>
                                                                            <th class="min-w-100px p-0"></th>
                                                                            <th class="min-w-80px p-0"></th>
                                                                        </tr>
                                                                    </thead>
                                                                    <!--end::Table head-->

                                                                    <!--begin::Table body-->
                                                                    <tbody>

                                                                        <tr>
                                                                            <td class="fs-6 fw-bold text-gray-800">
                                                                                11:43-09:43am </td>
                                                                            <td class="fs-6 fw-bold text-gray-400">
                                                                                lesson 1:
                                                                                <span class="text-gray-800">French
                                                                                    class</span>


                                                                            </td>
                                                                            <td class="fs-6 fw-bold text-gray-400">
                                                                                Cabinet:
                                                                                <span class="text-gray-800">5</span>

                                                                            </td>
                                                                            <td class="pe-0 text-end">
                                                                                <button class="btn btn-sm btn-light">
                                                                                    Skip
                                                                                </button>
                                                                            </td>
                                                                        </tr>

                                                                        <tr>
                                                                            <td class="bg-light rounded text-gray-600 fs-8 fw-bold px-3 py-2"
                                                                                colspan="4">
                                                                                Break 205min </td>
                                                                        </tr>

                                                                        <tr>
                                                                            <td class="fs-6 fw-bold text-gray-800">
                                                                                09:40-11:20am </td>
                                                                            <td class="fs-6 fw-bold text-gray-400">
                                                                                lesson 2:
                                                                                <span class="text-gray-800">Physics
                                                                                    class</span>


                                                                            </td>
                                                                            <td class="fs-6 fw-bold text-gray-400">
                                                                                Cabinet:
                                                                                <span class="text-gray-800">13</span>

                                                                            </td>
                                                                            <td class="pe-0 text-end">
                                                                                <button class="btn btn-sm btn-light">
                                                                                    Skip
                                                                                </button>
                                                                            </td>
                                                                        </tr>

                                                                        <tr>
                                                                            <td class="bg-light rounded text-gray-600 fs-8 fw-bold px-3 py-2"
                                                                                colspan="4">
                                                                                Break 25min </td>
                                                                        </tr>

                                                                        <tr>
                                                                            <td class="fs-6 fw-bold text-gray-800">
                                                                                10:35-43:09am </td>
                                                                            <td class="fs-6 fw-bold text-gray-400">
                                                                                lesson 3:
                                                                                <span class="text-gray-800">Chemistry
                                                                                    class</span>


                                                                            </td>
                                                                            <td class="fs-6 fw-bold text-gray-400">
                                                                                Court:
                                                                                <span class="text-gray-800">Main</span>

                                                                            </td>
                                                                            <td class="pe-0 text-end">
                                                                                <button class="btn btn-sm btn-light">
                                                                                    Skip
                                                                                </button>
                                                                            </td>
                                                                        </tr>

                                                                        <tr>
                                                                            <td class="bg-light rounded text-gray-600 fs-8 fw-bold px-3 py-2"
                                                                                colspan="4">
                                                                                Break 15min </td>
                                                                        </tr>

                                                                        <tr>
                                                                            <td class="fs-6 fw-bold text-gray-800">
                                                                                15:30-12:23pm </td>
                                                                            <td class="fs-6 fw-bold text-gray-400">
                                                                                lesson 4:
                                                                                <span class="text-gray-800">Biology
                                                                                    class</span>

                                                                                <!--begin::Icon-->
                                                                                <span class="cursor-pointer"
                                                                                    data-bs-toggle="tooltip"
                                                                                    data-bs-placement="top"
                                                                                    title="Held by Dr. Ana">
                                                                                    <i
                                                                                        class="ki-outline ki-information fs-1 text-warning"></i>
                                                                                </span>
                                                                                <!--end::Icon-->

                                                                            </td>
                                                                            <td class="fs-6 fw-bold text-gray-400">
                                                                                Cabinet:
                                                                                <span class="text-gray-800">23</span>

                                                                            </td>
                                                                            <td class="pe-0 text-end">
                                                                                <button class="btn btn-sm btn-light">
                                                                                    Skip
                                                                                </button>
                                                                            </td>
                                                                        </tr>
                                                                    </tbody>
                                                                    <!--end::Table body-->
                                                                </table>
                                                                <!--end::Table-->
                                                            </div>
                                                            <!--end::Table container-->
                                                        </div>
                                                        <!--end::Tap pane-->
                                                        <!--begin::Tap pane-->
                                                        <div class="tab-pane fade "
                                                            id="kt_table_widget_7_tab_content_2">
                                                            <!--begin::Table container-->
                                                            <div class="table-responsive">
                                                                <!--begin::Table-->
                                                                <table class="table align-middle">
                                                                    <!--begin::Table head-->
                                                                    <thead>
                                                                        <tr>
                                                                            <th class="min-w-150px p-0"></th>
                                                                            <th class="min-w-200px p-0"></th>
                                                                            <th class="min-w-100px p-0"></th>
                                                                            <th class="min-w-80px p-0"></th>
                                                                        </tr>
                                                                    </thead>
                                                                    <!--end::Table head-->

                                                                    <!--begin::Table body-->
                                                                    <tbody>

                                                                        <tr>
                                                                            <td class="fs-6 fw-bold text-gray-800">
                                                                                09:15-12:23am </td>
                                                                            <td class="fs-6 fw-bold text-gray-400">
                                                                                lesson 1:
                                                                                <span class="text-gray-800">Geography
                                                                                    class</span>


                                                                            </td>
                                                                            <td class="fs-6 fw-bold text-gray-400">
                                                                                Cabinet:
                                                                                <span class="text-gray-800">45</span>

                                                                            </td>
                                                                            <td class="pe-0 text-end">
                                                                                <button class="btn btn-sm btn-light">
                                                                                    Skip
                                                                                </button>
                                                                            </td>
                                                                        </tr>

                                                                        <tr>
                                                                            <td class="bg-light rounded text-gray-600 fs-8 fw-bold px-3 py-2"
                                                                                colspan="4">
                                                                                Break 20min </td>
                                                                        </tr>

                                                                        <tr>
                                                                            <td class="fs-6 fw-bold text-gray-800">
                                                                                08:30-09:30am </td>
                                                                            <td class="fs-6 fw-bold text-gray-400">
                                                                                lesson 2:
                                                                                <span class="text-gray-800">English
                                                                                    class</span>


                                                                            </td>
                                                                            <td class="fs-6 fw-bold text-gray-400">
                                                                                Cabinet:
                                                                                <span class="text-gray-800">9</span>

                                                                            </td>
                                                                            <td class="pe-0 text-end">
                                                                                <button class="btn btn-sm btn-light">
                                                                                    Skip
                                                                                </button>
                                                                            </td>
                                                                        </tr>

                                                                        <tr>
                                                                            <td class="bg-light rounded text-gray-600 fs-8 fw-bold px-3 py-2"
                                                                                colspan="4">
                                                                                Break 20min </td>
                                                                        </tr>

                                                                        <tr>
                                                                            <td class="fs-6 fw-bold text-gray-800">
                                                                                11:15-12:13am </td>
                                                                            <td class="fs-6 fw-bold text-gray-400">
                                                                                lesson 3:
                                                                                <span class="text-gray-800">Sports
                                                                                    class</span>


                                                                            </td>
                                                                            <td class="fs-6 fw-bold text-gray-400">
                                                                                Court:
                                                                                <span class="text-gray-800">Main</span>

                                                                            </td>
                                                                            <td class="pe-0 text-end">
                                                                                <button class="btn btn-sm btn-light">
                                                                                    Skip
                                                                                </button>
                                                                            </td>
                                                                        </tr>

                                                                        <tr>
                                                                            <td class="bg-light rounded text-gray-600 fs-8 fw-bold px-3 py-2"
                                                                                colspan="4">
                                                                                Break 25min </td>
                                                                        </tr>

                                                                        <tr>
                                                                            <td class="fs-6 fw-bold text-gray-800">
                                                                                14:10-15:35pm </td>
                                                                            <td class="fs-6 fw-bold text-gray-400">
                                                                                lesson 4:
                                                                                <span class="text-gray-800">Picture
                                                                                    class</span>

                                                                                <!--begin::Icon-->
                                                                                <span class="cursor-pointer"
                                                                                    data-bs-toggle="tooltip"
                                                                                    data-bs-placement="top"
                                                                                    title="Held by Dr. Lebron">
                                                                                    <i
                                                                                        class="ki-outline ki-information fs-1 text-warning"></i>
                                                                                </span>
                                                                                <!--end::Icon-->

                                                                            </td>
                                                                            <td class="fs-6 fw-bold text-gray-400">
                                                                                Cabinet:
                                                                                <span class="text-gray-800">12</span>

                                                                            </td>
                                                                            <td class="pe-0 text-end">
                                                                                <button class="btn btn-sm btn-light">
                                                                                    Skip
                                                                                </button>
                                                                            </td>
                                                                        </tr>
                                                                    </tbody>
                                                                    <!--end::Table body-->
                                                                </table>
                                                                <!--end::Table-->
                                                            </div>
                                                            <!--end::Table container-->
                                                        </div>
                                                        <!--end::Tap pane-->
                                                        <!--begin::Tap pane-->
                                                        <div class="tab-pane fade show active"
                                                            id="kt_table_widget_7_tab_content_3">
                                                            <!--begin::Table container-->
                                                            <div class="table-responsive">
                                                                <!--begin::Table-->
                                                                <table class="table align-middle">
                                                                    <!--begin::Table head-->
                                                                    <thead>
                                                                        <tr>
                                                                            <th class="min-w-150px p-0"></th>
                                                                            <th class="min-w-200px p-0"></th>
                                                                            <th class="min-w-100px p-0"></th>
                                                                            <th class="min-w-80px p-0"></th>
                                                                        </tr>
                                                                    </thead>
                                                                    <!--end::Table head-->

                                                                    <!--begin::Table body-->
                                                                    <tbody>

                                                                        <tr>
                                                                            <td class="fs-6 fw-bold text-gray-800">
                                                                                08:30-09:12am </td>
                                                                            <td class="fs-6 fw-bold text-gray-400">
                                                                                lesson 1:
                                                                                <span class="text-gray-800">Math
                                                                                    class</span>


                                                                            </td>
                                                                            <td class="fs-6 fw-bold text-gray-400">
                                                                                Cabinet:
                                                                                <span class="text-gray-800">45</span>

                                                                            </td>
                                                                            <td class="pe-0 text-end">
                                                                                <button class="btn btn-sm btn-light">
                                                                                    Skip
                                                                                </button>
                                                                            </td>
                                                                        </tr>

                                                                        <tr>
                                                                            <td class="bg-light rounded text-gray-600 fs-8 fw-bold px-3 py-2"
                                                                                colspan="4">
                                                                                Break 15min </td>
                                                                        </tr>

                                                                        <tr>
                                                                            <td class="fs-6 fw-bold text-gray-800">
                                                                                09:30-10:50am </td>
                                                                            <td class="fs-6 fw-bold text-gray-400">
                                                                                lesson 2:
                                                                                <span class="text-gray-800">History
                                                                                    class</span>


                                                                            </td>
                                                                            <td class="fs-6 fw-bold text-gray-400">
                                                                                Cabinet:
                                                                                <span class="text-gray-800">12</span>

                                                                            </td>
                                                                            <td class="pe-0 text-end">
                                                                                <button class="btn btn-sm btn-light">
                                                                                    Skip
                                                                                </button>
                                                                            </td>
                                                                        </tr>

                                                                        <tr>
                                                                            <td class="bg-light rounded text-gray-600 fs-8 fw-bold px-3 py-2"
                                                                                colspan="4">
                                                                                Break 20min </td>
                                                                        </tr>

                                                                        <tr>
                                                                            <td class="fs-6 fw-bold text-gray-800">
                                                                                10:35-11:20am </td>
                                                                            <td class="fs-6 fw-bold text-gray-400">
                                                                                lesson 3:
                                                                                <span class="text-gray-800">Sports
                                                                                    class</span>


                                                                            </td>
                                                                            <td class="fs-6 fw-bold text-gray-400">
                                                                                Court:
                                                                                <span class="text-gray-800">Main</span>

                                                                            </td>
                                                                            <td class="pe-0 text-end">
                                                                                <button class="btn btn-sm btn-light">
                                                                                    Skip
                                                                                </button>
                                                                            </td>
                                                                        </tr>

                                                                        <tr>
                                                                            <td class="bg-light rounded text-gray-600 fs-8 fw-bold px-3 py-2"
                                                                                colspan="4">
                                                                                Break 15min </td>
                                                                        </tr>

                                                                        <tr>
                                                                            <td class="fs-6 fw-bold text-gray-800">
                                                                                12:40-13:25pm </td>
                                                                            <td class="fs-6 fw-bold text-gray-400">
                                                                                lesson 4:
                                                                                <span class="text-gray-800">Chemistry
                                                                                    class</span>

                                                                                <!--begin::Icon-->
                                                                                <span class="cursor-pointer"
                                                                                    data-bs-toggle="tooltip"
                                                                                    data-bs-placement="top"
                                                                                    title="Held by Dr. Natali">
                                                                                    <i
                                                                                        class="ki-outline ki-information fs-1 text-warning"></i>
                                                                                </span>
                                                                                <!--end::Icon-->

                                                                            </td>
                                                                            <td class="fs-6 fw-bold text-gray-400">
                                                                                Cabinet:
                                                                                <span class="text-gray-800">19</span>

                                                                            </td>
                                                                            <td class="pe-0 text-end">
                                                                                <button class="btn btn-sm btn-light">
                                                                                    Skip
                                                                                </button>
                                                                            </td>
                                                                        </tr>
                                                                    </tbody>
                                                                    <!--end::Table body-->
                                                                </table>
                                                                <!--end::Table-->
                                                            </div>
                                                            <!--end::Table container-->
                                                        </div>
                                                        <!--end::Tap pane-->
                                                        <!--begin::Tap pane-->
                                                        <div class="tab-pane fade "
                                                            id="kt_table_widget_7_tab_content_4">
                                                            <!--begin::Table container-->
                                                            <div class="table-responsive">
                                                                <!--begin::Table-->
                                                                <table class="table align-middle">
                                                                    <!--begin::Table head-->
                                                                    <thead>
                                                                        <tr>
                                                                            <th class="min-w-150px p-0"></th>
                                                                            <th class="min-w-200px p-0"></th>
                                                                            <th class="min-w-100px p-0"></th>
                                                                            <th class="min-w-80px p-0"></th>
                                                                        </tr>
                                                                    </thead>
                                                                    <!--end::Table head-->

                                                                    <!--begin::Table body-->
                                                                    <tbody>

                                                                        <tr>
                                                                            <td class="fs-6 fw-bold text-gray-800">
                                                                                11:25-14:13am </td>
                                                                            <td class="fs-6 fw-bold text-gray-400">
                                                                                lesson 1:
                                                                                <span class="text-gray-800">Geography
                                                                                    class</span>


                                                                            </td>
                                                                            <td class="fs-6 fw-bold text-gray-400">
                                                                                Cabinet:
                                                                                <span class="text-gray-800">15</span>

                                                                            </td>
                                                                            <td class="pe-0 text-end">
                                                                                <button class="btn btn-sm btn-light">
                                                                                    Skip
                                                                                </button>
                                                                            </td>
                                                                        </tr>

                                                                        <tr>
                                                                            <td class="bg-light rounded text-gray-600 fs-8 fw-bold px-3 py-2"
                                                                                colspan="4">
                                                                                Break 25min </td>
                                                                        </tr>

                                                                        <tr>
                                                                            <td class="fs-6 fw-bold text-gray-800">
                                                                                08:30-09:30am </td>
                                                                            <td class="fs-6 fw-bold text-gray-400">
                                                                                lesson 2:
                                                                                <span class="text-gray-800">English
                                                                                    class</span>


                                                                            </td>
                                                                            <td class="fs-6 fw-bold text-gray-400">
                                                                                Cabinet:
                                                                                <span class="text-gray-800">9</span>

                                                                            </td>
                                                                            <td class="pe-0 text-end">
                                                                                <button class="btn btn-sm btn-light">
                                                                                    Skip
                                                                                </button>
                                                                            </td>
                                                                        </tr>

                                                                        <tr>
                                                                            <td class="bg-light rounded text-gray-600 fs-8 fw-bold px-3 py-2"
                                                                                colspan="4">
                                                                                Break 20min </td>
                                                                        </tr>

                                                                        <tr>
                                                                            <td class="fs-6 fw-bold text-gray-800">
                                                                                11:15-12:13am </td>
                                                                            <td class="fs-6 fw-bold text-gray-400">
                                                                                lesson 3:
                                                                                <span class="text-gray-800">Sports
                                                                                    class</span>


                                                                            </td>
                                                                            <td class="fs-6 fw-bold text-gray-400">
                                                                                Court:
                                                                                <span class="text-gray-800">Main</span>

                                                                            </td>
                                                                            <td class="pe-0 text-end">
                                                                                <button class="btn btn-sm btn-light">
                                                                                    Skip
                                                                                </button>
                                                                            </td>
                                                                        </tr>

                                                                        <tr>
                                                                            <td class="bg-light rounded text-gray-600 fs-8 fw-bold px-3 py-2"
                                                                                colspan="4">
                                                                                Break 25min </td>
                                                                        </tr>

                                                                        <tr>
                                                                            <td class="fs-6 fw-bold text-gray-800">
                                                                                14:10-15:35pm </td>
                                                                            <td class="fs-6 fw-bold text-gray-400">
                                                                                lesson 4:
                                                                                <span class="text-gray-800">Picture
                                                                                    class</span>

                                                                                <!--begin::Icon-->
                                                                                <span class="cursor-pointer"
                                                                                    data-bs-toggle="tooltip"
                                                                                    data-bs-placement="top"
                                                                                    title="Held by Dr. Kevin">
                                                                                    <i
                                                                                        class="ki-outline ki-information fs-1 text-warning"></i>
                                                                                </span>
                                                                                <!--end::Icon-->

                                                                            </td>
                                                                            <td class="fs-6 fw-bold text-gray-400">
                                                                                Cabinet:
                                                                                <span class="text-gray-800">12</span>

                                                                            </td>
                                                                            <td class="pe-0 text-end">
                                                                                <button class="btn btn-sm btn-light">
                                                                                    Skip
                                                                                </button>
                                                                            </td>
                                                                        </tr>
                                                                    </tbody>
                                                                    <!--end::Table body-->
                                                                </table>
                                                                <!--end::Table-->
                                                            </div>
                                                            <!--end::Table container-->
                                                        </div>
                                                        <!--end::Tap pane-->
                                                        <!--begin::Tap pane-->
                                                        <div class="tab-pane fade "
                                                            id="kt_table_widget_7_tab_content_5">
                                                            <!--begin::Table container-->
                                                            <div class="table-responsive">
                                                                <!--begin::Table-->
                                                                <table class="table align-middle">
                                                                    <!--begin::Table head-->
                                                                    <thead>
                                                                        <tr>
                                                                            <th class="min-w-150px p-0"></th>
                                                                            <th class="min-w-200px p-0"></th>
                                                                            <th class="min-w-100px p-0"></th>
                                                                            <th class="min-w-80px p-0"></th>
                                                                        </tr>
                                                                    </thead>
                                                                    <!--end::Table head-->

                                                                    <!--begin::Table body-->
                                                                    <tbody>

                                                                        <tr>
                                                                            <td class="fs-6 fw-bold text-gray-800">
                                                                                11:43-09:43am </td>
                                                                            <td class="fs-6 fw-bold text-gray-400">
                                                                                lesson 1:
                                                                                <span class="text-gray-800">French
                                                                                    class</span>


                                                                            </td>
                                                                            <td class="fs-6 fw-bold text-gray-400">
                                                                                Cabinet:
                                                                                <span class="text-gray-800">5</span>

                                                                            </td>
                                                                            <td class="pe-0 text-end">
                                                                                <button class="btn btn-sm btn-light">
                                                                                    Skip
                                                                                </button>
                                                                            </td>
                                                                        </tr>

                                                                        <tr>
                                                                            <td class="bg-light rounded text-gray-600 fs-8 fw-bold px-3 py-2"
                                                                                colspan="4">
                                                                                Break 205min </td>
                                                                        </tr>

                                                                        <tr>
                                                                            <td class="fs-6 fw-bold text-gray-800">
                                                                                09:40-11:20am </td>
                                                                            <td class="fs-6 fw-bold text-gray-400">
                                                                                lesson 2:
                                                                                <span class="text-gray-800">Physics
                                                                                    class</span>


                                                                            </td>
                                                                            <td class="fs-6 fw-bold text-gray-400">
                                                                                Cabinet:
                                                                                <span class="text-gray-800">13</span>

                                                                            </td>
                                                                            <td class="pe-0 text-end">
                                                                                <button class="btn btn-sm btn-light">
                                                                                    Skip
                                                                                </button>
                                                                            </td>
                                                                        </tr>

                                                                        <tr>
                                                                            <td class="bg-light rounded text-gray-600 fs-8 fw-bold px-3 py-2"
                                                                                colspan="4">
                                                                                Break 25min </td>
                                                                        </tr>

                                                                        <tr>
                                                                            <td class="fs-6 fw-bold text-gray-800">
                                                                                10:35-43:09am </td>
                                                                            <td class="fs-6 fw-bold text-gray-400">
                                                                                lesson 3:
                                                                                <span class="text-gray-800">Chemistry
                                                                                    class</span>


                                                                            </td>
                                                                            <td class="fs-6 fw-bold text-gray-400">
                                                                                Court:
                                                                                <span class="text-gray-800">Main</span>

                                                                            </td>
                                                                            <td class="pe-0 text-end">
                                                                                <button class="btn btn-sm btn-light">
                                                                                    Skip
                                                                                </button>
                                                                            </td>
                                                                        </tr>

                                                                        <tr>
                                                                            <td class="bg-light rounded text-gray-600 fs-8 fw-bold px-3 py-2"
                                                                                colspan="4">
                                                                                Break 15min </td>
                                                                        </tr>

                                                                        <tr>
                                                                            <td class="fs-6 fw-bold text-gray-800">
                                                                                15:30-12:23pm </td>
                                                                            <td class="fs-6 fw-bold text-gray-400">
                                                                                lesson 4:
                                                                                <span class="text-gray-800">Biology
                                                                                    class</span>

                                                                                <!--begin::Icon-->
                                                                                <span class="cursor-pointer"
                                                                                    data-bs-toggle="tooltip"
                                                                                    data-bs-placement="top"
                                                                                    title="Held by Dr. Emma">
                                                                                    <i
                                                                                        class="ki-outline ki-information fs-1 text-warning"></i>
                                                                                </span>
                                                                                <!--end::Icon-->

                                                                            </td>
                                                                            <td class="fs-6 fw-bold text-gray-400">
                                                                                Cabinet:
                                                                                <span class="text-gray-800">23</span>

                                                                            </td>
                                                                            <td class="pe-0 text-end">
                                                                                <button class="btn btn-sm btn-light">
                                                                                    Skip
                                                                                </button>
                                                                            </td>
                                                                        </tr>
                                                                    </tbody>
                                                                    <!--end::Table body-->
                                                                </table>
                                                                <!--end::Table-->
                                                            </div>
                                                            <!--end::Table container-->
                                                        </div>
                                                        <!--end::Tap pane-->

                                                    </div>
                                                    <!--end::Tab Content-->

                                                    <!--begin::Action-->
                                                    <div class="float-end">
                                                        <a href="#" class="btn btn-sm btn-light me-2"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#kt_modal_create_project">Add Lesson</a>

                                                        <a href="#" class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                                            data-bs-target="#kt_modal_create_app">Call Sick for
                                                            Today</a>
                                                    </div>
                                                    <!--end::Action-->
                                                </div>
                                                <!--end: Card Body-->
                                            </div>
                                            <!--end::Table widget 8-->

                                        </div>
                                        <!--end::Col-->
                                    </div>
                                    <!--end::Row-->

                                    <!--begin::Row-->
                                    <div class="row g-5 g-xl-10 g-xl-10">
                                        <!--begin::Col-->
                                        <div class="col-xl-4">

                                            <!--begin::List widget 11-->
                                            <div class="card card-flush h-xl-100">
                                                <!--begin::Header-->
                                                <div class="card-header pt-7 mb-3">
                                                    <!--begin::Title-->
                                                    <h3 class="card-title align-items-start flex-column">
                                                        <span class="card-label fw-bold text-gray-800">Our Fleet
                                                            Tonnage</span>
                                                        <span class="text-gray-400 mt-1 fw-semibold fs-6">Total 1,247
                                                            vehicles</span>
                                                    </h3>
                                                    <!--end::Title-->

                                                    <!--begin::Toolbar-->
                                                    <div class="card-toolbar">
                                                        <a href="#" class="btn btn-sm btn-light"
                                                            data-bs-toggle='tooltip' data-bs-dismiss='click'
                                                            data-bs-custom-class="tooltip-inverse"
                                                            title="Logistics App is coming soon">Review Fleet</a>
                                                    </div>
                                                    <!--end::Toolbar-->
                                                </div>
                                                <!--end::Header-->

                                                <!--begin::Body-->
                                                <div class="card-body pt-4">
                                                    <!--begin::Item-->
                                                    <div class="d-flex flex-stack">
                                                        <!--begin::Section-->
                                                        <div class="d-flex align-items-center me-5">
                                                            <!--begin::Symbol-->
                                                            <div class="symbol symbol-40px me-4">
                                                                <span class="symbol-label">
                                                                    <i
                                                                        class="ki-outline ki-ship text-gray-600 fs-1"></i>
                                                                </span>
                                                            </div>
                                                            <!--end::Symbol-->

                                                            <!--begin::Content-->
                                                            <div class="me-5">
                                                                <!--begin::Title-->
                                                                <a href="#"
                                                                    class="text-gray-800 fw-bold text-hover-primary fs-6">Ships</a>
                                                                <!--end::Title-->

                                                                <!--begin::Desc-->
                                                                <span
                                                                    class="text-gray-400 fw-semibold fs-7 d-block text-start ps-0">234
                                                                    Ships</span>
                                                                <!--end::Desc-->
                                                            </div>
                                                            <!--end::Content-->
                                                        </div>
                                                        <!--end::Section-->

                                                        <!--begin::Wrapper-->
                                                        <div class="text-gray-400 fw-bold fs-7 text-end">
                                                            <!--begin::Number-->
                                                            <span
                                                                class="text-gray-800 fw-bold fs-6 d-block">2,345,500</span>
                                                            <!--end::Number-->

                                                            Tons
                                                        </div>
                                                        <!--end::Wrapper-->
                                                    </div>
                                                    <!--end::Item-->

                                                    <!--begin::Separator-->
                                                    <div class="separator separator-dashed my-5"></div>
                                                    <!--end::Separator-->

                                                    <!--begin::Item-->
                                                    <div class="d-flex flex-stack">
                                                        <!--begin::Section-->
                                                        <div class="d-flex align-items-center me-5">
                                                            <!--begin::Symbol-->
                                                            <div class="symbol symbol-40px me-4">
                                                                <span class="symbol-label">
                                                                    <i
                                                                        class="ki-outline ki-truck text-gray-600 fs-1"></i>
                                                                </span>
                                                            </div>
                                                            <!--end::Symbol-->

                                                            <!--begin::Content-->
                                                            <div class="me-5">
                                                                <!--begin::Title-->
                                                                <a href="#"
                                                                    class="text-gray-800 fw-bold text-hover-primary fs-6">Trucks</a>
                                                                <!--end::Title-->

                                                                <!--begin::Desc-->
                                                                <span
                                                                    class="text-gray-400 fw-semibold fs-7 d-block text-start ps-0">1,460
                                                                    Trucks</span>
                                                                <!--end::Desc-->
                                                            </div>
                                                            <!--end::Content-->
                                                        </div>
                                                        <!--end::Section-->

                                                        <!--begin::Wrapper-->
                                                        <div class="text-gray-400 fw-bold fs-7 text-end">
                                                            <!--begin::Number-->
                                                            <span
                                                                class="text-gray-800 fw-bold fs-6 d-block">457,200</span>
                                                            <!--end::Number-->

                                                            Tons
                                                        </div>
                                                        <!--end::Wrapper-->
                                                    </div>
                                                    <!--end::Item-->

                                                    <!--begin::Separator-->
                                                    <div class="separator separator-dashed my-5"></div>
                                                    <!--end::Separator-->

                                                    <!--begin::Item-->
                                                    <div class="d-flex flex-stack">
                                                        <!--begin::Section-->
                                                        <div class="d-flex align-items-center me-5">
                                                            <!--begin::Symbol-->
                                                            <div class="symbol symbol-40px me-4">
                                                                <span class="symbol-label">
                                                                    <i
                                                                        class="ki-outline ki-airplane-square text-gray-600 fs-1"></i>
                                                                </span>
                                                            </div>
                                                            <!--end::Symbol-->

                                                            <!--begin::Content-->
                                                            <div class="me-5">
                                                                <!--begin::Title-->
                                                                <a href="#"
                                                                    class="text-gray-800 fw-bold text-hover-primary fs-6">Planes</a>
                                                                <!--end::Title-->

                                                                <!--begin::Desc-->
                                                                <span
                                                                    class="text-gray-400 fw-semibold fs-7 d-block text-start ps-0">8
                                                                    Aircrafts</span>
                                                                <!--end::Desc-->
                                                            </div>
                                                            <!--end::Content-->
                                                        </div>
                                                        <!--end::Section-->

                                                        <!--begin::Wrapper-->
                                                        <div class="text-gray-400 fw-bold fs-7 text-end">
                                                            <!--begin::Number-->
                                                            <span
                                                                class="text-gray-800 fw-bold fs-6 d-block">1,240</span>
                                                            <!--end::Number-->

                                                            Tons
                                                        </div>
                                                        <!--end::Wrapper-->
                                                    </div>
                                                    <!--end::Item-->

                                                    <!--begin::Separator-->
                                                    <div class="separator separator-dashed my-5"></div>
                                                    <!--end::Separator-->

                                                    <!--begin::Item-->
                                                    <div class="d-flex flex-stack">
                                                        <!--begin::Section-->
                                                        <div class="d-flex align-items-center me-5">
                                                            <!--begin::Symbol-->
                                                            <div class="symbol symbol-40px me-4">
                                                                <span class="symbol-label">
                                                                    <i class="ki-outline ki-bus text-gray-600 fs-1"></i>
                                                                </span>
                                                            </div>
                                                            <!--end::Symbol-->

                                                            <!--begin::Content-->
                                                            <div class="me-5">
                                                                <!--begin::Title-->
                                                                <a href="#"
                                                                    class="text-gray-800 fw-bold text-hover-primary fs-6">Trains</a>
                                                                <!--end::Title-->

                                                                <!--begin::Desc-->
                                                                <span
                                                                    class="text-gray-400 fw-semibold fs-7 d-block text-start ps-0">36
                                                                    Trains</span>
                                                                <!--end::Desc-->
                                                            </div>
                                                            <!--end::Content-->
                                                        </div>
                                                        <!--end::Section-->

                                                        <!--begin::Wrapper-->
                                                        <div class="text-gray-400 fw-bold fs-7 text-end">
                                                            <!--begin::Number-->
                                                            <span
                                                                class="text-gray-800 fw-bold fs-6 d-block">804,300</span>
                                                            <!--end::Number-->

                                                            Tons
                                                        </div>
                                                        <!--end::Wrapper-->
                                                    </div>
                                                    <!--end::Item-->




                                                    <div class="text-center pt-9">
                                                        <a href="apps/ecommerce/catalog/add-product.html"
                                                            class="btn btn-primary">Add Vehicle</a>
                                                    </div>
                                                </div>
                                                <!--end::Body-->
                                            </div>
                                            <!--end::List widget 11-->
                                        </div>
                                        <!--end::Col-->

                                        <!--begin::Col-->
                                        <div class="col-xxl-8 mb-5 mb-xl-10">
                                            <!--begin::Maps widget 1-->
                                            <div class="card card-flush h-md-100">
                                                <!--begin::Header-->
                                                <div class="card-header pt-7">
                                                    <!--begin::Title-->
                                                    <h3 class="card-title align-items-start flex-column">
                                                        <span class="card-label fw-bold text-dark">World Sales</span>

                                                        <span class="text-gray-400 pt-2 fw-semibold fs-6">Top Selling
                                                            Countries</span>
                                                    </h3>
                                                    <!--end::Title-->

                                                    <!--begin::Toolbar-->
                                                    <div class="card-toolbar">
                                                        <!--begin::Menu-->
                                                        <button
                                                            class="btn btn-icon btn-color-gray-400 btn-active-color-primary justify-content-end"
                                                            data-kt-menu-trigger="click"
                                                            data-kt-menu-placement="bottom-end"
                                                            data-kt-menu-overflow="true">
                                                            <i
                                                                class="ki-outline ki-dots-square fs-1 text-gray-400 me-n1"></i>
                                                        </button>


                                                        <!--begin::Menu 3-->
                                                        <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-semibold w-200px py-3"
                                                            data-kt-menu="true">
                                                            <!--begin::Heading-->
                                                            <div class="menu-item px-3">
                                                                <div
                                                                    class="menu-content text-muted pb-2 px-3 fs-7 text-uppercase">
                                                                    Payments
                                                                </div>
                                                            </div>
                                                            <!--end::Heading-->

                                                            <!--begin::Menu item-->
                                                            <div class="menu-item px-3">
                                                                <a href="#" class="menu-link px-3">
                                                                    Create Invoice
                                                                </a>
                                                            </div>
                                                            <!--end::Menu item-->

                                                            <!--begin::Menu item-->
                                                            <div class="menu-item px-3">
                                                                <a href="#" class="menu-link flex-stack px-3">
                                                                    Create Payment

                                                                    <span class="ms-2" data-bs-toggle="tooltip"
                                                                        title="Specify a target name for future usage and reference">
                                                                        <i class="ki-outline ki-information fs-6"></i>
                                                                    </span>
                                                                </a>
                                                            </div>
                                                            <!--end::Menu item-->

                                                            <!--begin::Menu item-->
                                                            <div class="menu-item px-3">
                                                                <a href="#" class="menu-link px-3">
                                                                    Generate Bill
                                                                </a>
                                                            </div>
                                                            <!--end::Menu item-->

                                                            <!--begin::Menu item-->
                                                            <div class="menu-item px-3" data-kt-menu-trigger="hover"
                                                                data-kt-menu-placement="right-end">
                                                                <a href="#" class="menu-link px-3">
                                                                    <span class="menu-title">Subscription</span>
                                                                    <span class="menu-arrow"></span>
                                                                </a>

                                                                <!--begin::Menu sub-->
                                                                <div class="menu-sub menu-sub-dropdown w-175px py-4">
                                                                    <!--begin::Menu item-->
                                                                    <div class="menu-item px-3">
                                                                        <a href="#" class="menu-link px-3">
                                                                            Plans
                                                                        </a>
                                                                    </div>
                                                                    <!--end::Menu item-->

                                                                    <!--begin::Menu item-->
                                                                    <div class="menu-item px-3">
                                                                        <a href="#" class="menu-link px-3">
                                                                            Billing
                                                                        </a>
                                                                    </div>
                                                                    <!--end::Menu item-->

                                                                    <!--begin::Menu item-->
                                                                    <div class="menu-item px-3">
                                                                        <a href="#" class="menu-link px-3">
                                                                            Statements
                                                                        </a>
                                                                    </div>
                                                                    <!--end::Menu item-->

                                                                    <!--begin::Menu separator-->
                                                                    <div class="separator my-2"></div>
                                                                    <!--end::Menu separator-->

                                                                    <!--begin::Menu item-->
                                                                    <div class="menu-item px-3">
                                                                        <div class="menu-content px-3">
                                                                            <!--begin::Switch-->
                                                                            <label
                                                                                class="form-check form-switch form-check-custom form-check-solid">
                                                                                <!--begin::Input-->
                                                                                <input
                                                                                    class="form-check-input w-30px h-20px"
                                                                                    type="checkbox" value="1"
                                                                                    checked="checked"
                                                                                    name="notifications" />
                                                                                <!--end::Input-->

                                                                                <!--end::Label-->
                                                                                <span
                                                                                    class="form-check-label text-muted fs-6">
                                                                                    Recuring
                                                                                </span>
                                                                                <!--end::Label-->
                                                                            </label>
                                                                            <!--end::Switch-->
                                                                        </div>
                                                                    </div>
                                                                    <!--end::Menu item-->
                                                                </div>
                                                                <!--end::Menu sub-->
                                                            </div>
                                                            <!--end::Menu item-->

                                                            <!--begin::Menu item-->
                                                            <div class="menu-item px-3 my-1">
                                                                <a href="#" class="menu-link px-3">
                                                                    Settings
                                                                </a>
                                                            </div>
                                                            <!--end::Menu item-->
                                                        </div>
                                                        <!--end::Menu 3-->

                                                        <!--end::Menu-->
                                                    </div>
                                                    <!--end::Toolbar-->
                                                </div>
                                                <!--end::Header-->

                                                <!--begin::Body-->
                                                <div class="card-body d-flex flex-center">
                                                    <!--begin::Map container-->
                                                    <div id="kt_maps_widget_1_map" class="w-100 h-350px"></div>
                                                    <!--end::Map container-->
                                                </div>
                                                <!--end::Body-->
                                            </div>
                                            <!--end::Maps widget 1-->
                                        </div>
                                        <!--end::Col-->
                                    </div>
                                    <!--end::Row-->


                                </div>
                                <!--end::Content-->

                            </div>
                            <!--end::Content wrapper-->

                            <!--begin::Footer-->
                            @include('cluster_b.dispatcher.layout.footer')
                            <!--end::Footer-->

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script> var hostUrl = "assets/index.html"; </script>
        <script src="{{ asset('assets/plugins/global/plugins.bundle.js') }}"></script>
        <script src="{{ asset('assets/js/scripts.bundle.js') }}"></script>
        <script type="text/javascript">$.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }});</script>
        <script>var assetUrl = "{{ asset('') }}";</script>
        {{-- <script src="{{ asset('js/system_admin/main.js') }}" type="module"></script> --}}
</body>
</html>
