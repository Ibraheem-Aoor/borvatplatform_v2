@include('layout.admin.head')
@auth
    @include('layout.admin.sidebar')
@endauth

<div class="container-fluid position-relative bg-white d-flex p-0">

    <!-- Content Start -->
    <div class="content">
        @auth
            @include('layout.admin.navbar')
        @endauth

        @yield('content')

        {{-- <!-- Footer Start -->
        <div class="container-fluid pt-4 px-4">
            <div class="bg-light rounded-top p-4">
                <div class="row">
                    <div class="col-12 col-sm-6 text-center text-sm-start">
                        &copy; <a href="#">Your Site Name</a>, All Right Reserved.
                    </div>
                    <div class="col-12 col-sm-6 text-center text-sm-end">
                        <!--/*** This template is free as long as you keep the footer author’s credit link/attribution link/backlink. If you'd like to use the template without the footer author’s credit link/attribution link/backlink, you can purchase the Credit Removal License from "https://htmlcodex.com/credit-removal". Thank you for your support. ***/-->
                        Designed By <a href="https://htmlcodex.com">HTML Codex</a>
                    </div>
                </div>
            </div>
        </div> --}}
        <!-- Footer End -->

    </div>
    <!-- Content End -->

    <!-- Back to Top -->
    <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>
</div>




@include('layout.admin.footer')
@if ($errors->any())
    @foreach ($errors as $error)
        <script>
            toastr.error('ERRRRRR');
            console.log('ERRRRRR');
        </script>
    @endforeach
@endif
