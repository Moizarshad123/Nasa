<script src="{{ asset('admin/assets/vendorr/libs/jquery/jquery.js') }}"></script>
<script src="{{ asset('admin/assets/vendorr/libs/popper/popper.js') }}"></script>
<script src="{{ asset('admin/assets/vendorr/js/bootstrap.js') }}"></script>
<script src="{{ asset('admin/assets/vendorr/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
<script src="{{ asset('admin/assets/vendorr/js/menu.js') }}"></script>
<!-- Helpers -->
<script src="{{ asset('admin/assets/vendorr/js/helpers.js')}}"></script>
<script src="{{ asset('admin/assets/js/config.js') }}"></script>

<!-- endbuild -->

<!-- Vendors JS -->
<script src="{{ asset('admin/assets/vendorr/libs/apex-charts/apexcharts.js') }}"></script>

<!-- Main JS -->
<script src="{{ asset('admin/assets/js/main.js') }}"></script>

<!-- Page JS -->
<script src="{{ asset('admin/assets/js/dashboards-analytics.js') }}"></script>

<!-- Place this tag in your head or just before your close body tag. -->
<script async defer src="https://buttons.github.io/buttons.js"></script>
<script src="{{URL:: asset('admin/plugins/toastr/toastr.min.js')}}"></script>
@if(session()->has('success'))
    <script type="text/javascript">  toastr.success('{{ session('success')}}');</script>
@endif
@if(session()->has('error'))
    <script type="text/javascript"> toastr.error('{{ session('error')}}');</script>
@endif

<script src="{{asset('admin/datatables/datatables.net/js/jquery.dataTables.min.js')}}"></script>
<script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>