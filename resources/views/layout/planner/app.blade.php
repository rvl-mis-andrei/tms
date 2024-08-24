<!DOCTYPE html>
<html lang="en">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<head>
    <title>Trip Monitoring | </title>
    <meta charset="utf-8" />
    <meta content="{{ csrf_token() }}" name="csrf-token" id="csrf-token">
    <meta content="{{ url('assets') }}" name="asset-url">
    {{-- <link rel="shortcut icon" href="assets/media/logos/favicon.ico" /> --}}
    {{-- <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" /> --}}
    <link href="{{ asset('assets/plugins/custom/fullcalendar/fullcalendar.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />
    <style>
        .custom-file-upload {
            width: 100%;
            /* max-width: 500px; */
            margin: 0 auto;
            border: 2px dashed #ddd;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            background-color: #f9f9f9;
            transition: background-color 0.3s ease;
        }

        .custom-file-upload:hover {
            background-color: #f0f0f0;
        }

        .upload-area {
            padding: 40px 20px;
            background-color: #fff;
            border-radius: 10px;
            cursor: pointer;
        }

        .upload-area .icon {
            font-size: 50px;
            color: #007bff;
            margin-bottom: 10px;
        }

        .upload-area h3 {
            font-size: 20px;
            margin: 10px 0;
            color: #333;
        }

        .upload-area p {
            color: #666;
        }

        .upload-area button {
            padding: 10px 20px;
            border: none;
            background-color: #007bff;
            color: white;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        .upload-area button:hover {
            background-color: #0056b3;
        }

        .upload-area input[type="file"] {
            display: none;
        }

        .file-list {
            margin-top: 20px;
            text-align: left;
        }

        .file-list .file-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-bottom: 10px;
            background-color: #fff;
            transition: background-color 0.3s ease;
        }

        .file-list .file-item:hover {
            background-color: #f8f8f8;
        }

        .file-list .file-name {
            font-weight: bold;
            color: #333;
        }

        .file-list .progress-bar {
            width: 100px;
            height: 8px;
            background-color: #f0f0f0;
            border-radius: 5px;
            overflow: hidden;
            margin-left: 20px;
        }

        .file-list .progress-bar .progress {
            width: 0%;
            height: 100%;
            background-color: #007bff;
            transition: width 0.3s ease;
        }

        .file-list .remove-btn {
            background-color: transparent;
            border: none;
            color: red;
            cursor: pointer;
            font-size: 16px;
        }
    </style>
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
           @include('layout.planner.navbar')
            <div class="app-wrapper d-flex " id="kt_app_wrapper">
                <div class="app-container  container-fluid d-flex ">
                    <div class="app-main flex-column flex-row-fluid " id="kt_app_main">
                        <div class="d-flex flex-column flex-column-fluid">
                            <div id="kt_app_toolbar" class="app-toolbar pt-7 pt-lg-10 ">
                                <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
                                    <div class="page-title d-flex flex-column justify-content-center gap-1 me-3">
                                        <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7">
                                            <li class="breadcrumb-item text-gray-700 fw-bold lh-1 mx-n1">
                                                <a href="index.html" class="text-hover-primary">
                                                    <i class="ki-outline ki-home text-gray-700 fs-6"></i> </a>
                                            </li>
                                            <li class="breadcrumb-item">
                                                <i class="ki-outline ki-right fs-7 text-gray-700"></i>
                                            </li>
                                            <li class="breadcrumb-item text-gray-700 fw-bold lh-1 mx-n1">
                                                Account </li>
                                            <li class="breadcrumb-item">
                                                <i class="ki-outline ki-right fs-7 text-gray-700"></i>
                                            </li>
                                            <li class="breadcrumb-item text-gray-500 mx-n1">
                                                Overview </li>
                                        </ul>
                                        <h1 class="page-heading d-flex flex-column justify-content-center text-dark fw-bolder fs-3 m-0">
                                            eCommerce Dashboard
                                        </h1>
                                    </div>
                                </div>
                            </div>
                            <div id="kt_app_content" class="app-content  flex-column-fluid">
                                    <div id="Page"></div>
                            </div>
                            @include('layout.planner.footer')
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
        </script><script src="{{ asset('assets/plugins/custom/formrepeater/formrepeater.bundle.js') }}"></script>
        <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.5/xlsx.full.min.js"></script>
        <script src="{{ asset('js/cluster_b/planner/navbar.js') }}" type="module"></script>
</body>
</html>
