<!-- ========== Footer Start ========== -->
<footer class="footer">
    <div class="container-fluid">
        <div class="row">
            <div class="text-center col-12">
                برمجة وتصميم
                <a href="https://palslt.com/" class="fw-bold footer-text" target="_blank"> palslt.com</a>
            </div>
        </div>
    </div>
</footer>
<!-- ========== Footer End ========== -->

</div>


</div>
<!-- END Wrapper -->

<!-- Vendor Javascript (Require in all Page) -->
<script src="{{ asset('assets/admin/js/vendor.js') }}"></script>

<!-- App Javascript (Require in all Page) -->
<script src="{{ asset('assets/admin/js/app.js') }}"></script>

<!-- Vector Map Js -->
{{-- <script src="{{asset('assets/admin/vendor/jsvectormap/js/jsvectormap.min.js')}}"></script> --}}
{{-- <script src="{{asset('assets/admin/vendor/jsvectormap/maps/world-merc.js')}}"></script> --}}
{{-- <script src="{{asset('assets/admin/vendor/jsvectormap/maps/world.js')}}"></script> --}}

<!-- Dashboard Js -->

<script src="{{ asset('assets/admin/js/pages/dashboard.js') }}"></script>

@toastifyJs
@yield('js')
</body>

</html>
