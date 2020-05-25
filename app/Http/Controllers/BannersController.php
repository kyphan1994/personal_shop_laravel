<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Intervention\Image\Facades\Image;

use App\Banners;

class BannersController extends Controller
{
    public function banners() {
        return view('admin.banner.banners');
    }

    public function addBanner(Request $request) {
        if ($request->isMethod('post')) {
            $data =$request->all();
            $banner = new Banners;
            $banner->name = $data['banner_name'];
            $banner->text_style = $data['text_style'];
            $banner->sort_order = $data['sort_order'];
            $banner->content = $data['banner_content'];
            $banner->link = $data['link'];
            //Upload image
            if ($request->hasFile('image')) {
                //echo $img_tmp = Input::file('image');
                echo $image_tmp = $request->image;
                //image path code
                if ($image_tmp->isValid()) {
                    $extension = $image_tmp->getClientOriginalExtension();
                    $filename = rand(111,99999).'.'.$extension;
                    $banner_path = 'uploads/banners/'.$filename;

                    //image resize
                    Image::make($image_tmp)->resize(500, 500)->save($banner_path);

                    $banner->image = $filename;
                }
            }
            $banner->save();
            return redirect('/admin/banners')->with('flash_message_success','Banners has been updated Successfully!');
        }
        return view('admin.banner.add_banner');
    }
}
