<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSubCategoryRequest;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $subCategory = SubCategory::all()->load('category');
        return view('subcategory.index', compact('subCategory'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        return view('subcategory.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSubCategoryRequest $request)
    {
        $validated = $request->validated();
        $attr = $request->only('name', 'category_id');

        try {
            DB::beginTransaction();

            $subCategory = new SubCategory();
            $subCategory->fill($attr);
            $subCategory->save();

            DB::commit();
        } catch (\Exception $th) {
            DB::rollBack();
            return $th;
        }

        return redirect()->route('subcategory.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(SubCategory $subCategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SubCategory $subcategory)
    {
        $categories = Category::all();
        return view('subcategory.edit', compact('subcategory', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreSubCategoryRequest $request, SubCategory $subcategory)
    {
        $validated = $request->validated();
        $attr = $request->only('name', 'category_id');

        try {
            DB::beginTransaction();

            $subcategory->fill($attr);
            $subcategory->save();

            DB::commit();
        } catch (\Exception $th) {
            DB::rollBack();
            return $th;
        }

        return redirect()->route('subcategory.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SubCategory $subcategory)
    {
        $subcategory->delete();
        return redirect()->route('subcategory.index');
    }
}
