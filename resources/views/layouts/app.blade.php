<!DOCTYPE html>

<!-- =========================================================
* Sneat - Bootstrap 5 HTML Admin Template - Pro | v1.0.0
==============================================================

* Product Page: https://themeselection.com/products/sneat-bootstrap-html-admin-template/
* Created by: ThemeSelection
* License: You must have a valid license purchased in order to legally use the theme for your project.
* Copyright ThemeSelection (https://themeselection.com)

=========================================================
 -->
<!-- beautify ignore:start -->
<html
  lang="en"
  class="light-style layout-menu-fixed"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="{{ asset('assets/') }}/"
  data-template="vertical-menu-template-free"
>
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"
    />

    <title>@yield('title', 'Halaman') | SIAKAD SMKN 1 Curugbitung</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('assets/img/logo-smkn1curugbitung.png') }}" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
      rel="stylesheet"
    />

    <!-- Icons. Uncomment required icon fonts -->
    <link rel="stylesheet" href="{{ asset('assets/') }}/vendor/fonts/boxicons.css" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('assets/') }}/vendor/css/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ asset('assets/') }}/vendor/css/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{ asset('assets/') }}/css/demo.css" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{ asset('assets/') }}/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />

    <link rel="stylesheet" href="{{ asset('assets/') }}/vendor/libs/apex-charts/apex-charts.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.8/css/dataTables.dataTables.min.css">

    <!-- Page CSS -->

    <!-- Helpers -->
    <script src="{{ asset('assets/') }}/vendor/js/helpers.js"></script>

    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="{{ asset('assets/') }}/js/config.js"></script>

    <style>
      /* Custom Green Theme Overrides */
      .text-primary {
        color: #198754 !important;
      }

      .bg-primary {
        background-color: #198754 !important;
      }

      .btn-primary {
        background-color: #198754 !important;
        border-color: #198754 !important;
        box-shadow: 0 0.125rem 0.25rem 0 rgba(25, 135, 84, 0.4) !important;
      }

      .btn-primary:hover, .btn-primary:focus, .btn-primary:active {
        background-color: #157347 !important;
        border-color: #146c43 !important;
      }

      .bg-label-primary {
        background-color: #e8f5e9 !important;
        color: #198754 !important;
      }

      .btn-outline-primary {
        color: #198754 !important;
        border-color: #198754 !important;
      }

      .btn-outline-primary:hover {
        background-color: #198754 !important;
        border-color: #198754 !important;
        color: #fff !important;
      }

      .bg-menu-theme .menu-inner > .menu-item.active > .menu-link {
        color: #198754 !important;
        background-color: rgba(25, 135, 84, 0.16) !important;
      }

      .bg-menu-theme .menu-inner > .menu-item.active:before {
        background: #198754 !important;
      }
      
      .bg-menu-theme .menu-sub > .menu-item.active > .menu-link:not(.menu-toggle):before {
        background-color: #198754 !important;
        border: 3px solid #e8f5e9 !important;
      }

      .app-brand .layout-menu-toggle {
        background-color: #198754 !important;
      }

      .form-control:focus, .form-select:focus {
        border-color: #198754 !important;
      }

      .page-item.active .page-link {
        background-color: #198754 !important;
        border-color: #198754 !important;
      }

      /* Premium Green Background & Styling */
      body {
        background-color: #f0f7f0 !important; /* Soft mint green canvas */
      }

      .content-wrapper {
        background-color: transparent !important;
      }

      .card {
        border: none !important;
        border-top: 4px solid #198754 !important; /* Green Accent on Top */
        border-radius: 12px !important;
        box-shadow: 0 4px 15px rgba(25, 135, 84, 0.05) !important;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        overflow: hidden; /* Ensure content stays within rounded corners with border-top */
      }

      .card-header {
        background-color: rgba(25, 135, 84, 0.03) !important;
        padding: 1.25rem !important;
      }

      .card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 30px rgba(25, 135, 84, 0.12) !important;
      }

      .layout-navbar {
        background-color: rgba(255, 255, 255, 0.9) !important;
        backdrop-filter: blur(8px);
      }

      .bg-menu-theme {
        background-color: #ffffff !important;
        border-right: 1px solid rgba(25, 135, 84, 0.1);
      }

      .bg-footer-theme {
        background-color: transparent !important;
      }

      /* Scrollbar Customization */
      ::-webkit-scrollbar {
        width: 8px;
      }
      ::-webkit-scrollbar-track {
        background: #f0f7f0;
      }
      ::-webkit-scrollbar-thumb {
        background: #198754;
        border-radius: 10px;
      }
      ::-webkit-scrollbar-thumb:hover {
        background: #157347;
      }
    </style>
  </head>

  <body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
      <div class="layout-container">
        @auth
        @include('layouts.sidebar')
        @endauth

        <!-- Layout container -->
        <div class="layout-page">
          @auth
          @include('layouts.navbar')
          @endauth

          <!-- Content wrapper -->
          <div class="content-wrapper">
            <!-- Content -->
            @yield('content')
            <!-- / Content -->

            <!-- Footer -->
            <footer class="content-footer footer bg-footer-theme">
              <div class="container-xxl d-flex flex-wrap justify-content-between py-2 flex-md-row flex-column">
                <div class="mb-2 mb-md-0">
                  ©
                  <script>
                    document.write(new Date().getFullYear());
                  </script>
                  SMK Negeri 1 Curugbitung
                </div>
                <div>
                </div>
              </div>
            </footer>
            <!-- / Footer -->

            <div class="content-backdrop fade"></div>
          </div>
          <!-- Content wrapper -->
        </div>
        <!-- / Layout page -->
      </div>

      <!-- Overlay -->
      <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    <!-- / Layout wrapper -->

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <script src="{{ asset('assets/') }}/vendor/libs/jquery/jquery.js"></script>
    <script src="{{ asset('assets/') }}/vendor/libs/popper/popper.js"></script>
    <script src="{{ asset('assets/') }}/vendor/js/bootstrap.js"></script>
    <script src="{{ asset('assets/') }}/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>

    <script src="{{ asset('assets/') }}/vendor/js/menu.js"></script>
    <!-- endbuild -->

    <!-- Vendors JS -->
    <script src="{{ asset('assets/') }}/vendor/libs/apex-charts/apexcharts.js"></script>

    <!-- Main JS -->
    <script src="{{ asset('assets/') }}/js/main.js"></script>

    <!-- Page JS -->
    <script src="{{ asset('assets/') }}/js/dashboards-analytics.js"></script>

    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>

    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/2.3.8/js/dataTables.min.js"></script>

    <script>
        $(document).ready(function() {
            $('.datatable').DataTable({
                "language": {
                    "decimal": "",
                    "emptyTable": "Tidak ada data yang tersedia pada tabel ini",
                    "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
                    "infoEmpty": "Menampilkan 0 sampai 0 dari 0 entri",
                    "infoFiltered": "(disaring dari _MAX_ entri keseluruhan)",
                    "infoPostFix": "",
                    "thousands": ",",
                    "lengthMenu": "Tampilkan _MENU_ entri",
                    "loadingRecords": "Sedang memproses...",
                    "processing": "Sedang memproses...",
                    "search": "Cari:",
                    "zeroRecords": "Tidak ditemukan data yang sesuai",
                    "paginate": {
                        "first": "Pertama",
                        "last": "Terakhir",
                        "next": "Selanjutnya",
                        "previous": "Sebelumnya"
                    },
                    "aria": {
                        "sortAscending": ": aktifkan untuk mengurutkan kolom ke atas",
                        "sortDescending": ": aktifkan untuk mengurutkan kolom ke bawah"
                    }
                }
            });
        });
    </script>
    @stack('scripts')
  </body>
</html>
