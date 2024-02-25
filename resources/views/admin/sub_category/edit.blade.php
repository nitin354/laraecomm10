@extends('admin.layouts.app')
@section('content')
<section class="content-header">					
					<div class="container-fluid my-2">
						<div class="row mb-2">
							<div class="col-sm-6">
								<h1>Edit Sub Category</h1>
							</div>
							<div class="col-sm-6 text-right">
								<a href="{{route('sub-category.index')}}" class="btn btn-primary">Back</a>
							</div>
						</div>
					</div>
					<!-- /.container-fluid -->
				</section>
				<!-- Main content -->
				<section class="content">
					<!-- Default box -->
					<div class="container-fluid">
                        <form action="" id="subCategoryForm" name="subCategoryForm" method="post">
						<div class="card">
							<div class="card-body">								
								<div class="row">
                                    <div class="col-md-12">
										<div class="mb-3">
											<label for="name">Category</label>
											<select name="category" id="category" class="form-control">
                                                @if($category->isNotEmpty())
                                                @foreach($category as $cat)
                                                <option {{ ($subCategory->category_id == $cat->id) ? 'selected':'' }} value="{{$cat->id}}">{{$cat->name}}</option>
                                                
                                                @endforeach
                                                @endif
                                            </select>
                                            <p></p>
										</div>
									</div>
									<div class="col-md-6">
										<div class="mb-3">
											<label for="name">Name</label>
											<input type="text" value="{{$subCategory->name}}" name="name" id="name" class="form-control" placeholder="Name">	
                                            <p></p>
                                        </div>
									</div>
									<div class="col-md-6">
										<div class="mb-3">
											<label for="slug">Slug</label>
											<input type="text" value="{{$subCategory->slug}}" readonly name="slug" id="slug" class="form-control" placeholder="Slug">	
                                            <p></p>
                                        </div>
									</div>		
                                    <div class="col-md-6">
										<div class="mb-3">
											<label for="status">Status</label>
											<select name='status' id='status' class='form-control'>
												<option {{ ($subCategory->status == 1) ? 'selected':'' }} value="1">Active</option>
												<option {{ ($subCategory->status == 0) ? 'selected':'' }} value="0">deactive</option>
											</select>
										</div>
									</div>								
								</div>
							</div>							
						</div>
						<div class="pb-5 pt-3">
							<button class="btn btn-primary" type="submit">Update</button>
							<a href="{{route('sub-category.index')}}" class="btn btn-outline-dark ml-3">Cancel</a>
						</div>
                    </form>
					</div>
					<!-- /.card -->
				</section>
	

@endsection

@section('customjs')
<script>
	$("#subCategoryForm").submit(function(event){
		event.preventDefault();
		var element = $(this);
		$.ajax({
			url:'{{route('sub-category.update',$subCategory->id)}}',
			type: 'put',
			data:element.serializeArray(),
			dataType:'json',
			success:function(response){

                  if(response['status'] == true){
				    window.location.href="{{ route('sub-category.index') }}"

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