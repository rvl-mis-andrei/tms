<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Trip Monitoring System</title>
    <link href="{{ asset('assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />

    <style>
        body {
            background-image: url('/assets/media/illustrations/misc/Wallpaper.jpg');
        }

        [data-bs-theme="dark"] body {
            background-image: url('/assets/media/illustrations/misc/Wallpaper.jpg');
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

<body id="kt_body" class="app-blank bgi-size-cover bgi-position-center bgi-no-repeat">
    <div class="d-flex flex-column flex-center flex-column-fluid">
        <div class="row g-5 text-center">
            <div class="col-6">
                <div class="card border border-3 border-hover-primary cursor-pointer card-flush py-5">
                    <div class="card-body py-15 py-lg-20">
                        <h1 class="fw-bold fs-2hx text-gray-900 mb-4">CLUSTER A</h1>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="card border border-3 border-hover-primary cursor-pointer card-flush py-5">
                    <div class="card-body py-15 py-lg-20">
                        <h1 class="fw-bold fs-2hx text-gray-900 mb-4">CLUSTER B</h1>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="card border border-3 border-hover-primary cursor-pointer card-flush py-5">
                    <div class="card-body py-15 py-lg-20">
                        <h1 class="fw-bold fs-2hx text-gray-900 mb-4">CLUSTER C</h1>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="card border border-3 border-hover-primary cursor-pointer card-flush py-5">
                    <div class="card-body py-15 py-lg-20">
                        <h1 class="fw-bold fs-2hx text-gray-900 mb-4">CLUSTER D</h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script> var hostUrl = "../../../assets/index.html"; </script>
    <script src="{{ asset('assets/plugins/global/plugins.bundle.js') }}"></script>
    <script src="{{ asset('assets/js/scripts.bundle.js') }}"></script>
</body>

</html>
