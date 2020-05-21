<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use App\Products;
// use Laravel\Ui\Presets\React;

class ProductsController extends Controller
{
    public function addProduct (Request $request) {
        if ($request->isMethod('post')){
            $data = $request->all();
            // echo "<pre>";print_r($data);die;
            $product = new Products;
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
                return redirect('/admin/add-product')->with('flash_message_success', 'Product has been added successfully!');

            }
            return view('admin.products.add_product');
        }
        
        public function viewProducts() {
            $products = Products::get();
            return view('admin.products.view_products')->with(compact('products'));
        } 
    }


//