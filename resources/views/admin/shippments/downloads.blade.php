@extends('layout.admin.master')
@section('content')
    <!-- Recent Sales Start -->
    <div class="container-fluid pt-4 px-4">
        <div class="bg-light text-center rounded p-4">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h6 class="mb-0">Recent Shipments Downloads</h6>
            </div>
            <div class="d-flex align-items-center justify-content-between mb-4">
                <form name="search-from" action="{{ route('shippment.recents') }}" method="GET">
                    @csrf
                    <div class="d-flex">
                        <input type="date" name="from_date" class="form-control" min="2023-01-01"
                            value="{{ $from_date ?? \Carbon\Carbon::today()->toDateString() }}">
                        &nbsp;
                        <i class="fa fa-arrow-right mt-2"></i>
                        &nbsp;
                        <input type="date" name="to_date" class="form-control" min="2023-01-01"
                            value="{{ $to_date ?? \Carbon\Carbon::today()->toDateString() }}">
                        <button type="submit" class="btn-xs btn-primary"><i class="fa fa-search"></i></button>
                    </div>
                </form>
            </div>
            <div class="table-responsive">
                <table class="table text-start align-middle table-bordered table-hover mb-0" id="myTable">
                    <thead>
                        <tr class="text-dark">
                            <th scope="col">File</th>
                            <th scope="col">Date</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($files as $file)
                        <tr>
                            <td>{{basename($file->file_name)}}</td>
                            <td>{{$file->created_at}}</td>
                            <td><a href="{{route('shippment.recents.download' , $file->id)}}" class="btn  btn-outline-info"><i class="fa fa-download"></i></a></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                {!! $files->links() !!}
            </div>
        </div>


    </div>
    <!-- Recent Sales End -->

@endsection
