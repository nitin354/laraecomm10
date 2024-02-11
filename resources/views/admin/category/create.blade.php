@extends('admin.layouts.app')
@section('content')

	<!-- Content Header (Page header) -->
				<section class="content-header">					
					<div class="container-fluid my-2">
						<div class="row mb-2">
							<div class="col-sm-6">
								<h1>Create Category</h1>
							</div>
							<div class="col-sm-6 text-right">
								<a href="{{ route('category.index')}}" class="btn btn-primary">Back</a>
							</div>
						</div>
					</div>
					<!-- /.container-fluid -->
				</section>
				<!-- Main content -->
				<section class="content">
					<!-- Default box -->
					<div class="container-fluid">
						<form action="" method="post" id="categoryform" name="categoryform">
							
						<div class="card">
							<div class="card-body">								
								<div class="row">
									<div class="col-md-6">
										<div class="mb-3">
											<label for="name">Name</label>
											<input type="text" name="name" id="name" class="form-control" placeholder="Name">	
											<p></p>
										</div>
										
									</div>
									<div class="col-md-6">
										<div class="mb-3">
											<label for="email">Slug</label>
											<input type="text" readonly name="slug" id="slug" class="form-control" placeholder="Slug">	
											<p></p>
										</div>
										
									</div>	
									<div class="col-md-6">
										<div class="mb-3">
											<label for="email">Status</label>
											<select name='status' id='status' class='form-control'>
												<option value="1">Active</option>
												<option value="0">deactive</option>
											</select>
										</div>
									</div>								
								</div>
							</div>							
						</div>
						<div class="pb-5 pt-3">
							<button type="submit" class="btn btn-primary">Create</button>
							<a href="{{ route('category.index')}}" class="btn btn-outline-dark ml-3">Cancel</a>
						</div>
					</form>
					</div>
					<!-- /.card -->
				</section>
				<!-- /.content -->

@endsection

@section('customjs')
<script>
	$("#categoryform").submit(function(event){
		event.preventDefault();
		var element = $(this);
		$.ajax({
			url:'{{route('category.store')}}',
			type: 'post',
			data:element.serializeArray(),
			dataType:'json',
			success:function(response){

                  if(response['status'] == true){
				    window.location.href="{{ route('category.index') }}"

					$("#name").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
					$("#slug").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");

				  }else{

					var error =  response['error'];
				console.log(error['name']);

				if(error['name']){

					$("#name").addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(error['name']);

				}else{
					$("#name").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
				}

				if(error['slug']){

					$("#slug").addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(error['slug']);

				}else{
					$("#slug").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
				}

				  }


			},error:function(jqxhr,exception){

				console.log('somthing went wrong');

			}
			
		});

	});

	$('#name').change(function(){
		 element = $(this);
		$.ajax({
			url:'{{route("getslug")}}',
			type: 'get',
			data:{title:element.val()},
			dataType:'json',
			success:function(response){
				
				console.log(response)
				if(response['status'] == true){
					$('#slug').val(response['slug']);
				}
			}
		});

	})
	


</script>

@endsection