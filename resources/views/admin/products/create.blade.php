@extends('admin.layouts.app')
@section('content')

		<!-- Content Header (Page header) -->
        <section class="content-header">					
					<div class="container-fluid my-2">
						<div class="row mb-2">
							<div class="col-sm-6">
								<h1>Create Product</h1>
							</div>
							<div class="col-sm-6 text-right">
								<a href="products.html" class="btn btn-primary">Back</a>
							</div>
						</div>
					</div>
					<!-- /.container-fluid -->
				</section>
				<!-- Main content -->
				<section class="content">
					<!-- Default box -->
                    
					<div class="container-fluid">
                    <form action="" method="Post" id="productform" name="productform" >
                        <div class="row">
                            <div class="col-md-8">
                                <div class="card mb-3">
                                    <div class="card-body">								
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label for="title">Title</label>
                                                    <input type="text" name="title" id="title" class="form-control" placeholder="Title">	
                                                    <p class="error"></p>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label for="title">slug</label>
                                                    <input type="text" readonly name="slug" id="slug" class="form-control" placeholder="Slug">	
                                                    <p class="error"></p>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label for="description">Description</label>
                                                    <textarea name="description" id="description" cols="30" rows="10" class="summernote" placeholder="Description"></textarea>
                                                </div>
                                            </div>                                            
                                        </div>
                                    </div>	                                                                      
                                </div>
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <h2 class="h4 mb-3">Media</h2>								
                                        <div id="image" class="dropzone dz-clickable">
                                            <div class="dz-message needsclick">    
                                                <br>Drop files here or click to upload.<br><br>                                            
                                            </div>
                                        </div>
                                    </div>	                                                                      
                                </div>
                                <div class="row" id="product-gallery">


                                </div>
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <h2 class="h4 mb-3">Pricing</h2>								
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label for="price">Price</label>
                                                    <input type="text" name="price" id="price" class="form-control" placeholder="Price">	
                                                    <p class="error"></p>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label for="compare_price">Compare at Price</label>
                                                    <input type="text" name="compare_price" id="compare_price" class="form-control" placeholder="Compare Price">
                                                    <p class="text-muted mt-3">
                                                        To show a reduced price, move the product’s original price into Compare at price. Enter a lower value into Price.
                                                    </p>	
                                                </div>
                                            </div>                                            
                                        </div>
                                    </div>	                                                                      
                                </div>
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <h2 class="h4 mb-3">Inventory</h2>								
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="sku">SKU (Stock Keeping Unit)</label>
                                                    <input type="text" name="sku" id="sku" class="form-control" placeholder="sku">	
                                                    <p class="error"></p>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="barcode">Barcode</label>
                                                    <input type="text" name="barcode" id="barcode" class="form-control" placeholder="Barcode">	
                                                </div>
                                            </div>   
                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="hidden" name="track_qty"  value="No">
                                                        <input class="custom-control-input" type="checkbox" id="track_qty" name="track_qty" value="Yes" checked>
                                                        <label for="track_qty" class="custom-control-label">Track Quantity</label>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <input type="number" min="0" name="qty" id="qty" class="form-control" placeholder="Qty">	
                                                </div>
                                            </div>                                         
                                        </div>
                                    </div>	                                                                      
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card mb-3">
                                    <div class="card-body">	
                                        <h2 class="h4 mb-3">Product status</h2>
                                        <div class="mb-3">
                                            <select name="status" id="status" class="form-control">
                                                <option value="1">Active</option>
                                                <option value="0">Block</option>
                                            </select>
                                        </div>
                                    </div>
                                </div> 
                                <div class="card">
                                    <div class="card-body">	
                                        <h2 class="h4  mb-3">Product category</h2>
                                        <div class="mb-3">
                                            <label for="category">Category</label>
                                            <select name="category" id="category" class="form-control">
                                            <option value="">Select a Category</option>
                                            @if($category->isNotEmpty())
                                                @foreach($category as $cat)
                                                <option value="{{$cat->id}}">{{$cat->name}}</option>
                                                @endforeach
                                            @endif
                                            </select>
                                            <p class="error"></p>
                                        </div>
                                        <div class="mb-3">
                                            <label for="category">Sub category</label>
                                            <select name="sub_category" id="sub_category" class="form-control">
                                            <option value="">Select a Sub Category</option>
                                               
                                            </select>
                                        </div>
                                    </div>
                                </div> 
                                <div class="card mb-3">
                                    <div class="card-body">	
                                        <h2 class="h4 mb-3">Product brand</h2>
                                        <div class="mb-3">
                                            <select name="brand" id="brand" class="form-control">
                                            <option value="">Select a Brand</option>
                                            @if($brand->isNotEmpty())
                                                @foreach($brand as $brands)
                                                <option value="{{$brands->id}}">{{$brands->name}}</option>
                                                @endforeach
                                            @endif
                                            </select>
                                        </div>
                                    </div>
                                </div> 
                                <div class="card mb-3">
                                    <div class="card-body">	
                                        <h2 class="h4 mb-3">Featured product</h2>
                                        <div class="mb-3">
                                            <select name="is_featured" id="is_featured" class="form-control">
                                                <option value="No">No</option>
                                                <option value="Yes">Yes</option>                                                
                                            </select>

                                        </div>
                                    </div>
                                </div>                                 
                            </div>
                        </div>
						
						<div class="pb-5 pt-3">
							<button class="btn btn-primary" type="submit">Create</button>
							<a href="products.html" class="btn btn-outline-dark ml-3">Cancel</a>
						</div>
                    </form>
					</div>
					<!-- /.card -->
				</section>
				<!-- /.content -->

@endsection

@section('customjs')
<script>
	$("#productform").submit(function(event){
		event.preventDefault();
		var element = $(this);
		$.ajax({
			url:'{{route('product.store')}}',
			type: 'post',
			data:element.serializeArray(),
			dataType:'json',
			success:function(response){

                  if(response['status'] == true){
				    window.location.href="{{ route('category.index') }}"


				  }else{

					var error =  response['errors'];
                        
                        $(".error").removeClass('invalid-feedback').html("");
                        $("input[type='text'],select,input[type='number']").removeClass('is-invalid');

                        $.each(error,function(key,value){
                            $(`#${key}`).addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(value);
                        });


				  }


			},error:function(jqxhr,exception){

				console.log('somthing went wrong');

			}
			
		});

	});

	$('#title').change(function(){
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

    $('#category').change(function(){
		 element = $(this);
		$.ajax({
			url:'{{route("product-subCategory.index")}}',
			type: 'get',
			data:{category_id:element.val()},
			dataType:'json',
			success:function(response){
				
				console.log(response)
				if(response['status'] == true){
                    $('#sub_category').find("option").not(":first").remove();

                    $.each(response['subCategory'] ,function(key,item){
                        $('#sub_category').append(`<option value="${ item.id}">${item.name}</option>`);


                    });
					
				}
			}
		});

	})
	
	Dropzone.autoDiscover = false;    
	const dropzone = $("#image").dropzone({ 
		
		url:  "{{ route('temp-images.create') }}",
		maxFiles: 10,
		paramName: 'image',
		addRemoveLinks: true,
		acceptedFiles: "image/jpeg,image/png,image/gif",
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}, success: function(file, response){
			$("#image_id").val(response.image_id);
                        var htmml=`<div class="col-md-3" id="image-row-${response.image_id}"><div class="card">
                        <input type="hidden" name="image_array[]" value="${response.image_id}">
                        <img src="${response.imagepath}" class="card-img-top" alt="">
                        <div class="card-body">
                            <a href="javascript:void(0)" onclick="deleteimage(${response.image_id})" class="btn btn-danger">delete</a>
                        </div>
                        </div></div>`;

                        $("#product-gallery").append(htmml);
		},complete:function(file){
            this.removeFile(file);

        }
	});

    function deleteimage(id){

        $("#image-row-"+id).remove();

    }

</script>

@endsection