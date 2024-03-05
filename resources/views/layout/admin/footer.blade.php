<!-- JavaScript Libraries -->
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script src="{{ asset('assets/lib/chart/chart.min.js') }}"></script>
<script src="{{ asset('assets/lib/easing/easing.min.js') }}"></script>
<script src="{{ asset('assets/lib/waypoints/waypoints.min.js') }}"></script>
<script src="{{ asset('assets/lib/owlcarousel/owl.carousel.min.js') }}"></script>
<script src="{{ asset('assets/lib/tempusdominus/js/moment.min.js') }}"></script>
<script src="{{ asset('assets/lib/tempusdominus/js/moment-timezone.min.js') }}"></script>
<script src="{{ asset('assets/lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js') }}"></script>
<script src="{{ asset('assets/js/toastr.min.js') }}"></script>
<script src="{{ asset('assets/js/custom/master.js') }}?v=0.02"></script>
<!-- Template Javascript -->
<script src="{{ asset('assets/js/main.js?v=0.01') }}"></script>






{{-- tollge check all checkboxes inputs --}}
<script>
    $(document).on('click', ' #check_all', function() {
        $('input[type="checkbox"]').prop('checked', this.checked);
    });


    // Get the selected checkboxes values
    function getIdsArray() {
        var ids = [];
        $('input:checked').each(function() {
            ids.push($(this).val());
        });
        return ids;
    }
</script>

<script>
    @if (Session::has('success'))
        toastr.success('{{ Session::get('success') }}');
    @endif
    @if (Session::has('error'))
        toastr.error('{{ Session::get('error') }}');
    @endif
</script>

@if ($errors->any())
    <script>
        @foreach ($errors as $error)
            toastr.error("{{ $error }}");
        @endforeach
    </script>
@endif


@yield('js')
</body>

</html>
