@extends('admin.layouts.master')

@section('content')

         <!-- Content Wrapper. Contains page content -->
         <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
               <div class="header-icon">
                  <i class="fa fa-product-hunt"></i>
               </div>
               <div class="header-title">
                  <h1>View Products</h1>
                  <small>Products List</small>
               </div>
            </section>
            <!-- Main content -->
            <section class="content">
               <div class="row">
                  <div class="col-sm-12">
                     <div class="panel panel-bd lobidrag">
                        <div class="panel-heading">
                           <div class="btn-group" id="buttonexport">
                              <a href="#">
                                 <h4>View Products</h4>
                              </a>
                           </div>
                        </div>
                        <div class="panel-body">
                        <!-- Plugin content:powerpoint,txt,pdf,png,word,xl -->
                           <div class="btn-group">
                              <div class="buttonexport" id="buttonlist"> 
                                 <a class="btn btn-add" href="{{url('admin/add-product')}}"> <i class="fa fa-plus"></i>Add Product</a>  
                              </div>
                           </div>
                           <!-- Plugin content:powerpoint,txt,pdf,png,word,xl -->
                           <div class="table-responsive">
                              <table id="table_id" class="table table-bordered table-striped table-hover">
                                 <thead>
                                    <tr class="info">
                                       <th>Product Name</th>
                                       <th>Product Code</th>
                                       <th>Product Color</th>
                                       <th>Image</th>
                                       <th>Price</th>
                                       <th>Status</th>
                                       <th>Action</th>
                                    </tr>
                                 </thead>
                                 <tbody>
                                    @foreach($products as $product)
                                    <tr>
                                       <td>{{$product->name}}</td>
                                       <td>{{$product->code}}</td>
                                       <td>{{$product->color}}</td>
                                       <td>
                                            @if (!empty($product->image))
                                            <img src="{{asset('/uploads/products/'.$product->image)}}" alt="" style="width: 100px;">
                                            @endif
                                        </td>
                                       <td>{{$product->price}}</td>
                                       <td><span class="label-custom label label-default">Active</span></td>
                                       <td>
                                            <a href="{{url('/admin/edit-product/'.$product->id)}}" class="btn btn-add btn-sm"><i class="fa fa-pencil"></i></a>
                                            <a href="#" class="btn btn-danger btn-sm"><i class="fa fa-trash-o"></i> </a>
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
            </section>
            <!-- /.content -->
         </div>
         <!-- /.content-wrapper -->

@endsection