@extends('admin.admin_app')
@push('styles')
@endpush
@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
	<div class="col-lg-8 col-sm-8 col-xs-8">
		<h2> User Details </h2>
		<ol class="breadcrumb">
			<li class="breadcrumb-item">
				<a href="{{ url('admin') }}">Dashboard</a>
			</li>
			<li class="breadcrumb-item active">
				<strong> User Details </strong>
			</li>
		</ol>
	</div>
	<div class="col-lg-4 col-sm-4 col-xs-4 text-right">
		<a class="btn btn-primary t_m_25" href="{!! URL::previous() !!}">
			<i class="fa fa-arrow-left" aria-hidden="true"></i> Go Back
		</a>
	</div>
</div>
<div class="wrapper wrapper-content animated fadeInRight">
	<div class="row">
		<div class="col-md-12">
			<div class="ibox">
				<div class="ibox-title">
					<h5>User Details</h5>
					<div class="ibox-tools">

					</div>
				</div>
				<div class="ibox-content">
					<div class="row">
						<div class="col-md-6">
							<div class="row">
								<label class="col-4 col-form-label">
									<strong> Name :</strong>
								</label>
								<label class="col-8 col-form-label">{{ $user->fname . ' ' . $user->lname}}
								</label>

							</div>
						</div>
						<div class="col-md-6">
							<div class="row">
								<label class="col-4 col-form-label">
									<strong> Email :</strong>
								</label>
								<label class="col-8 col-form-label">{{ $user->email }}</label>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="row">
								<label class="col-4 col-form-label">
									<strong> Title :</strong>
								</label>
								<label class="col-8 col-form-label">{{ $user->title ?? 'N/A'}}</label>
							</div>
						</div>
						<div class="col-md-6">
							<div class="row">
								<label class="col-4 col-form-label">
									<strong> Company :</strong>
								</label>
								<label class="col-8 col-form-label">
									<a href="{{ url('admin/companies/detail') }}/{{ $user->company->id }}" target="_blank">{{ $user->company->name }}</a>
								</label>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="row">
								<label class="col-4 col-form-label">
									<strong> Phone :</strong>
								</label>
								<label class="col-8 col-form-label">{{ $user->phone}}</label>
							</div>
						</div>
						<div class="col-md-6">
							<div class="row">
								<label class="col-4 col-form-label">
									<strong> Fax :</strong>
								</label>
								<label class="col-8 col-form-label">{{ $user->fax ?? 'N/A' }}</label>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
@push('scripts')
<script>
	$(document).ready(function() {
		$('#image').change(function() {
			var file = this.files[0];
			var reader = new FileReader();
			reader.onload = function(e) {
				$('#perviewImg').attr('src', e.target.result);
			}
			reader.readAsDataURL(file);
		});
	});

	var session = "{{Session::has('error') ? 'true' : 'false'}}";
	if (session == 'true') {
		toastr.options = {
			"closeButton": true,
			"progressBar": true,
			"positionClass": "toast-top-right"
		}
		toastr.error("{{Session::get('error')}}");
	}
	var session = "{{Session::has('success') ? 'true' : 'false'}}";
	if (session == 'true') {
		toastr.options = {
			"closeButton": true,
			"progressBar": true,
			"positionClass": "toast-top-right"
		}
		toastr.success("{{Session::get('success')}}");

	}
</script>
@endpush