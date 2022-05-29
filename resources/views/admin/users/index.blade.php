@extends('layouts.admin')

@section('content')
	<div class="page-breadcrumb">
        <div class="row">
        	<div class="col-4 align-self-center">
        	
            </div>

            <div class="col-8 align-self-center">
                <a class="btn btn-primary float-right btn-view-all" href="{{ route('add-user') }}" role="button"><i data-feather="plus" class="width15"></i> Add User</a>
            </div>
            
        </div>
    </div>
	<div class="container-fluid">
		<div class="row">
			<div class="col-12">
				<div class="card">
				    <div class="card-body">
				        <div class="mb-4">
				            <h4 class="card-title">User </h4>
				            
				            	<div id="button" class="datatable-btns"></div>
				            	<div class="col-3 float-right search-csv">
					            	<div class="customize-input">
					                    <input class="form-control custom-shadow custom-radius border-0 bg-white myInputTextField" type="text" placeholder="Search" aria-label="Search">
					                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search form-control-icon"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
					                </div>
				            	</div>
				            
				            <!-- <input type="text" class="myInputTextField"> -->
				        </div>
						<div class="table-responsive">
							<table id="users"  class="table no-wrap v-middle mb-0 dashboard-table responsive nowrap">
								<thead>
									<tr class="border-0">
										<th class="border-0 font-14 font-weight-medium text-muted">Sr.</th>
										<th class="border-0 font-14 font-weight-medium text-muted">Name</th>
										<th class="border-0 font-14 font-weight-medium text-muted">Email</th>
										<th class="border-0 font-14 font-weight-medium text-muted">Joining Date</th> 
										<th class="border-0 font-14 font-weight-medium text-muted noExport">Action</th>
									</tr>
								</thead>
								<tbody>
									@foreach ($users as $key => $user)  
									<tr>
										<td class="border-top-0 text-muted px-2 py-4 font-14">{{ $key+1 }}</td>
										<td class="border-top-0 text-muted px-2 py-4 font-14">
											{{ $user->name }}  {{ isset($user->last_name)? $user->last_name: '' }}
										</td>
										<td class="border-top-0 text-muted px-2 py-4 font-14">
											{{ $user->email }}
										</td>
										<td class="border-top-0 text-muted px-2 py-4 font-14">
											{{ date('d/m/Y' ,strtotime($user->created_at)) }}
										</td>
										 
										 <td class="border-top-0 px-2 py-4">
												<a href="javascript:void(0);" data-url="{{ route('delete-user', ['id' => $user->id])  }}" class="change-psd-btn px-2 delete_user" title="Delete User"><i class="fas fa-trash"></i></a>
												<a href="{{ route('user-change-password', ['id' => $user->id])  }}" class="change-psd-btn" title="Change Password"><i class="fas fa-unlock-alt"></i></a>
												<a href="{{ route('user-activities', ['id' => $user->id]) }}" class="change-psd-btn"><i class="fas fa-eye"></i></a>
											</td>
									</tr>
									@endforeach 
								</tbody>
								
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection