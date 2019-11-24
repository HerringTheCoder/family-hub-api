<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Gallery;

class GalleryController extends Controller
{
    public function index()
    {
        $gallery = new Gallery();
        $gallery->setTable(Auth::User()->prefix.'_gallery');
        $gallery = Gallery::all();

        return response()->json([
            'message' => 'Success',
            'data' => $gallery
        ], 201); 
        
    }

    public function store(StoreGallery $request)
    {
        $photo = $request->file('photo_input');
        $extension = $photo->getClientOriginalExtension();
        Storage::disk('public')->put($photo->getFilename().'.'.$extension,  File::get($photo));
        $filename = $photo->getFilename().'.'.$extension;

        $gallery = new Gallery([
            'mime' => $photo->getClientMimeType(),
            'original_filename' => $photo->getClientOriginalName(),
            'filename' => $photo->getFilename().'.'.$extension,
            'description' => $request->description
        ]);
        $gallery->setTable(Auth::User()->prefix.'_gallery');
        $gallery->save();
        return response()->json([
            'message' => 'Success, data inserted!'
        ], 201);  
        
    }

    public function edit($id)
    {
        
        $gallery = new Gallery();
        $gallery->setTable(Auth::User()->prefix.'_gallery');
        $gallery = Gallery::find($id);
        return response()->json([
            'message' => 'Success, found data!',
            'data' => $gallery
        ], 201);  
    }

    public function update(UpdateGallery $request, $id)
    {
        $photo = $request->file('photo_input');
        if($photo){
            $extension = $photo->getClientOriginalExtension();
            Storage::disk('public')->put($photo->getFilename().'.'.$extension,  File::get($photo));

            $filename = $photo->getFilename().'.'.$extension;
            $mime = $photo->getClientMimeType();
            $original_filename = $photo->getClientOriginalName();
        }else{
            $filename = $request->photo;
            $mime = $request->mime;
            $original_filename = $request->original_filename;
        }

        $gallery = new Gallery();
          
        $gallery->setTable(Auth::User()->prefix.'_gallery');
        $gallery->where('author_id',Auth::User()->id)
                ->update([
                'mime' => $mime,
                'original_filename' => $original_filename,
                'filename' => $filename,
                'description' => $request->description
                ]);

        return response()->json([
            'message' => 'Success, data updated!'], 201);
        
    }

    public function delete($id)
    {
        $gallery = new Gallery();
        $gallery->setTable(Auth::User()->prefix.'_gallery');
        $gallery->delete();
        return response()->json([
            'message' => 'Success, data deleted'], 201);
        
    }
}
