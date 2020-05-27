<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use RealRashid\SweetAlert\Facades\Alert;
use App\Products;
use App\Category;
use App\ProductsAttributes;

// use Laravel\Ui\Presets\React;

class ProductsController extends Controller
{
    public function addProduct (Request $request) {
        if ($request->isMethod('post')){
            $data = $request->all();
            // echo "<pre>";print_r($data);die;
            $product = new Products;
            $product->category_id = $data['category_id'];
            $product->name = $data['product_name'];
            $product->code = $data['product_code'];
            $product->color = $data['product_color'];
            if (!empty($data['description'])) {
                $product->description = $data['product_description'];

            } else {
                $product->description = '';
            }
            $product->price = $data['product_price'];

                //Upload image
                if ($request->hasFile('image')) {
                    //echo $img_tmp = Input::file('image');
                    echo $img_tmp = $request->image;
                    //image path code
                    if ($img_tmp->isValid()) {
                        $extension = $img_tmp->getClientOriginalExtension();
                        $filename = rand(111,99999).'.'.$extension;
                        $img_path = 'uploads/products/'.$filename;

                        //imageresize
                        Image::make($img_tmp)->resize(500, 500)->save($img_path);

                        $product->image = $filename;
                    }
                }
                $product->save();
                return redirect('/admin/view-products')->with('flash_message_success', 'Product has been added successfully!');

            }
            $categories = Category::where(['parent_id'=>0])->get();
            $categories_dropdown = "<option value='' selected disabled>Select</option>";
            foreach($categories as $cat) {
                $categories_dropdown .="<option value='".$cat->id."'>".$cat->name."</option>";
                $sub_categories = Category::where(['parent_id'=>$cat->id])->get();
                foreach($sub_categories as $sub_cat) {
                    $categories_dropdown.="<option value='".$sub_cat->id."'>&nbsp;--&nbsp".$sub_cat->name."</option>";
                }
            }
            return view('admin.products.add_product')->with(compact('categories_dropdown'));
        }
        
        public function viewProducts() {
            $products = Products::get();
            return view('admin.products.view_products')->with(compact('products'));
        }

        public function editProduct(Request $request, $id=null) {
            if ($request->isMethod('post')) {
                $data = $request->all();
                if ($request->hasFile('image')) {
                    //echo $img_tmp = Input::file('image');
                    echo $img_tmp = $request->image;
                    //image path code
                    if ($img_tmp->isValid()) {
                        $extension = $img_tmp->getClientOriginalExtension();
                        $filename = rand(111,99999).'.'.$extension;
                        $img_path = 'uploads/products/'.$filename;

                        //imageresize
                        Image::make($img_tmp)->resize(500, 500)->save($img_path);
                    } 
                } else {
                    $filename = $data['current_image'];
                }
                if (empty($data['product_description'])){
                    $data['product_description'] = '';
                }
                Products::where(['id'=>$id])->update([
                    'name'=>$data['product_name'],
                    'category_id'=>$data['category_id'],
                    'code'=>$data['product_code'],
                    'color'=>$data['product_color'],
                    'description'=>$data['product_description'],
                    'price'=>$data['product_price'],
                    'image'=>$filename,
                ]);
                return redirect('/admin/view-products')->with('flash_message_success', "Product has been edited!");
            }
            $productDetails = Products::where(['id'=>$id])->first();

            //Category dropdown code
            $categories = Category::where(['parent_id'=>0])->get();
            $categories_dropdown = "<option value='' selected disabled>Select</option>";
            foreach($categories as $cat) {
                if ($cat->id==$productDetails->category_id) {
                    $selected = "selected";
                } else {
                    $selected ="";
                }
                $categories_dropdown.="<option value='".$cat->id."' ".$selected.">".$cat->name."</option>";
            }

            //Code for su categories
            $sub_categories= Category::where(['parent_id'=>$cat->id])->get();
            foreach($sub_categories as$sub_cat) {
                if ($cat->id==$productDetails->category_id) {
                    $selected = "selected";
                } else {
                    $selected ="";
                }
                $categories_dropdown.="<option value='".$cat->id."' ".$selected.">&nbsp;--&nbsp".$cat->name."</option>";
            }

            return view('admin.products.edit_product')->with(compact('productDetails', "categories_dropdown"));
        }

        public function deleteProduct($id=null) {
            Products::where(['id'=>$id])->delete();
            Alert::success('Deleted Successfully', 'Success Message');
            return redirect()->back()->with('flash_message_success', 'Product has been deleted!');
        }

        public function updateStatus(Request $request, $id=null) {
            $data = $request->all();
            Products::where('id', $data['id'])->update(['status'=>$data['status']]);
        }

        public function products($id=null) {
            $productDetails = Products::where('id', $id)->first();
            // echo $productDetails;die;
            return view('pershop.product_details')->with(compact('productDetails'));
        }

        public function addAttributes(Request $request, $id=null) {
            $productDetails = Products::with('attributes')->where(['id'=>$id])->first();
            if($request->isMethod('post')) {    
                $data = $request->all();
                // echo "<pre>"; print_r($data); die;
                foreach($data['sku'] as $key =>$val) {
                    if (!empty($val)) {
                        //Prevent duplicate Sku Record
                        $attrCountSKU = ProductsAttributes::where('sku', $val)->count();
                        if ($attrCountSKU>0) {
                            return redirect('/admin/add-attributes/'.$id)->with('flash_message_error', 'SKU is already exist please select another sku');
                        }
                        //Prevent duplicate Size Record
                        $attrCountSizes = ProductsAttributes::where(['product_id'=>$id, 'size'=>$data['size'][$key]])->count();
                        if ($attrCountSizes>0) {
                            return redirect('/admin/add-attributes/'.$id)->with('flash_message_error', ''.$data['size'][$key].'Size is already exist please select anothersize');
                        }

                        $attributes = new ProductsAttributes;
                        $attributes->product_id = $id;
                        $attributes->sku = $val;
                        $attributes->size = $data['size'][$key];
                        $attributes->price = $data['price'][$key];
                        $attributes->stock = $data['stock'][$key];
                        $attributes->save();
                    }
                }
                return redirect('/admin/add-attributes/'.$id)->with('flash_message_success', 'Product Attributes added Successfully!');
            }
            return view('admin.products.add_attributes')->with(compact('productDetails'));
        }

        public function deleteAttributes($id=null) {
            ProductsAttributes::where(['id'=>$id])->delete();
            return redirect()->back()->with('flash_message_error', 'Product Attributes has been deleted!');
        }

        public function editAttributes(Request $request, $id=null) {
            if ($request->isMethod('post')) {
                $data = $request->all();
                foreach ($data['attr'] as $key=>$attr) {
                    ProductsAttributes::where(['id'=>$data['attr'][$key]])->update([
                        'sku'=>$data['sku'][$key],
                        'size'=>$data['size'][$key],
                        'price'=>$data['price'][$key],
                        'stock'=>$data['stock'][$key]
                    ]);
                }
                return redirect()->back()->with('flash_message_success', 'Product Attributes Updated!');
            }
        }

        public function addImages(Request $request, $id=null) {
            return view('admin.products.add_images');
        }
    }