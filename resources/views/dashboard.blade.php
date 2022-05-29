@extends('layouts.admin')

@section('content')
    <!-- Container fluid  -->
    <!-- ============================================================== -->
    <div class="container-fluid">
        <!-- *************************************************************** -->
        <!-- Start First Cards -->
        <!-- *************************************************************** -->
        <div class="card-group">
            <div class="card border-right">
                <div class="card-body">
                    <div class="d-flex d-lg-flex d-md-block align-items-center">
                        <div>
                            <div class="d-inline-flex align-items-center">
                                <h2 class="text-dark mb-1 font-weight-medium"><a href="{{ route('activities') }}">{{ $stats['activitiesCount'] }}</a></h2>
                            </div>
                            <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Activities</h6>
                        </div>
                        <div class="ml-auto mt-md-6 mt-lg-0">
                            <span class="opacity-7 text-muted"><i data-feather="tag" class="feather-icon"></i></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-right">
                <div class="card-body">
                    <div class="d-flex d-lg-flex d-md-block align-items-center">
                        <div>
                            <div class="d-inline-flex align-items-center">
                                <h2 class="text-dark mb-1 font-weight-medium"><a href="{{ route('users') }}">{{ $stats['usersCount'] }}</a></h2>
                            </div>
                            <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Users</h6>
                        </div>
                        <div class="ml-auto mt-md-6 mt-lg-0">
                            <span class="opacity-7 text-muted"><i data-feather="users" ></i></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- *************************************************************** -->
        <!-- End First Cards -->
        <!-- *************************************************************** -->
        <!-- *************************************************************** -->
        <div id='calendar'></div>
    </div>
    <!-- ============================================================== -->
    <!-- End Container fluid  -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
@endsection