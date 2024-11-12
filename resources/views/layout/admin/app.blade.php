<!DOCTYPE html>
<html lang="en">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />

<head>
    <title>Trip Monitoring |</title>
    <meta charset="utf-8" />
    <meta content="{{ csrf_token() }}" name="csrf-token" id="csrf-token">
    <meta content="{{ url('assets') }}" name="asset-url">
    <link rel="canonical" href="index.html" />
    <link rel="shortcut icon" href="assets/media/logos/favicon.ico" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
    <link href="{{ asset('assets/admin/plugins/custom/fullcalendar/fullcalendar.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/admin/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/admin/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/admin/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />
</head>

<body id="kt_app_body" data-kt-app-layout="dark-sidebar" data-kt-app-header-fixed="true"
    data-kt-app-sidebar-enabled="true" data-kt-app-sidebar-fixed="true" data-kt-app-sidebar-hoverable="true"
    data-kt-app-sidebar-push-header="true" data-kt-app-sidebar-push-toolbar="true"
    data-kt-app-sidebar-push-footer="true" data-kt-app-toolbar-enabled="true" class="app-default">
    <script>
        var defaultThemeMode = "light";
        var themeMode;
        if (document.documentElement) {
            if (document.documentElement.hasAttribute("data-bs-theme-mode")) {
                themeMode = document.documentElement.getAttribute("data-bs-theme-mode");
            } else {
                if (localStorage.getItem("data-bs-theme") !== null) {
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
    <div class="d-flex flex-column flex-root app-root" id="kt_app_root">
        <div class="app-page  flex-column flex-column-fluid " id="kt_app_page">

            @include('layout.admin.navbar')

            <div class="app-wrapper  flex-column flex-row-fluid " id="kt_app_wrapper">

                @include('layout.admin.sidebar')

                <div class="app-main flex-column flex-row-fluid " id="kt_app_main">

                </div>
            </div>
        </div>

        @include('layout.admin.footer')
</body>
</html>
