<!DOCTYPE html>
<html lang="en">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<head>
    <title>Trip Monitoring | </title>
    <meta charset="utf-8" />
    <meta content="{{ csrf_token() }}" name="csrf-token" id="csrf-token">
    <meta content="{{ url('assets') }}" name="asset-url">
    {{-- <link rel="shortcut icon" href="assets/media/logos/favicon.ico" /> --}}
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
        <div class="app-page  flex-column flex-column-fluid " id="kt_app_page">
           @include('layout.dispatcher.navbar')
            <div class="app-wrapper d-flex " id="kt_app_wrapper">
                <!--begin::Wrapper container-->
                <div class="app-container  container-fluid d-flex ">
                    <!--begin::Main-->
                    <div class="app-main flex-column flex-row-fluid " id="kt_app_main">
                        <!--begin::Content wrapper-->
                        <div class="d-flex flex-column flex-column-fluid">

                            <!--begin::Toolbar-->
                            <div id="kt_app_toolbar" class="app-toolbar pt-7 pt-lg-10 ">

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
                                        </ul>
                                        <h1 class="page-heading  d-flex flex-column justify-content-center text-dark fw-bolder fs-3 m-0">

                                        </h1>
                                    </div>
                                </div>
                            </div>
                            <div id="kt_app_content" class="app-content  flex-column-fluid">
                                    <div id="Page"></div>
                            </div>
                            @include('layout.dispatcher.footer')
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script> var hostUrl = "assets/index.html"; </script>
        <script src="{{ asset('assets/plugins/global/plugins.bundle.js') }}"></script>
        <script src="{{ asset('assets/js/scripts.bundle.js') }}"></script>
        <script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
        <script type="text/javascript">
            var asset_url = $('meta[name="asset-url"]').attr("content");
            var csrf_token = $('meta[name="csrf-token"]').attr("content");
            var app = $("#Page");
            $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }});
            var page_block = new KTBlockUI(document.querySelector('.app-page'), {
                message: `<div class="blockui-message"><span class="spinner-border text-primary"></span>Loading. . .</div>`,
            });
        </script>
        <script src="{{ asset('assets/plugins/custom/formrepeater/formrepeater.bundle.js') }}"></script>
        <script src="{{ asset('js/cluster_b/dispatcher/navbar.js') }}" type="module"></script>
</body>
</html>
