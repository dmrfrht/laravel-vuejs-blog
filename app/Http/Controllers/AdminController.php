<?php

namespace App\Http\Controllers;

use App\Category;
use App\Tag;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function addTag(Request $request)
    {
        // validate request
        $this->validate($request, [
            'tagName' => 'required'
        ]);

        return Tag::create([
            'tagName' => $request->tagName
        ]);
    }

    public function getTags()
    {
        return Tag::orderBy('id', 'DESC')->get();
    }

    public function editTag(Request $request)
    {
        // validate request
        $this->validate($request, [
            'tagName' => 'required',
            'id' => 'required'
        ]);

        return Tag::where('id', $request->id)->update(['tagName' => $request->tagName]);
    }

    public function deleteTag(Request $request)
    {
        // validate request
        $this->validate($request, [
            'id' => 'required'
        ]);

        return Tag::where('id', $request->id)->delete();
    }

    public function upload(Request $request)
    {
        // validate request
        $this->validate($request, [
            'file' => 'required|mimes:jpeg,jpg,png'
        ]);

        $picName = time() . '.' . $request->file->extension();
        $request->file->move(public_path('uploads'), $picName);

        return $picName;
    }

    public function deleteImage(Request $request)
    {
        $fileName = $request->imageName;
        $this->deleteFileFromServer($fileName, false);
        return 'done';
    }

    public function deleteFileFromServer($fileName, $hasFullPath = false)
    {
        $filePath = "";
        
        if ($hasFullPath) {
            $filePath = public_path() . '/uploads/' . $fileName;
        }
        
        if (file_exists($filePath)) {
            @unlink($filePath);
        }
        return;
    }

    public function addCategory(Request $request)
    {
        // validate request
        $this->validate($request, [
            'categoryName' => 'required',
            'iconImage' => 'required'
        ]);

        return Category::create([
            'categoryName' => $request->categoryName,
            'iconImage' => $request->iconImage,
        ]);
    }

    public function getCategories()
    {
        return Category::orderBy('id', 'DESC')->get();
    }

    public function editCategory(Request $request)
    {
        // validate request
        $this->validate($request, [
            'categoryName' => 'required',
            'iconImage' => 'required',
        ]);

        return Category::where('id', $request->id)->update([
            'categoryName' => $request->categoryName,
            'iconImage' => $request->iconImage
        ]);
    }

    public function deleteCategory(Request $request)
    {
        // firts delete the original file from the server
        $this->deleteFileFromServer($request->iconImage);   

        // validate request
        $this->validate($request, [
            'id' => 'required'
        ]);

        return Category::where('id', $request->id)->delete();
    }
}
