<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use App\Models\ItemImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ItemController extends Controller
{
    public function index()
    {
        $items = Item::all();
        return view('items.index', compact('items'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('items.create', compact('categories'));
    }

    public function store(Request $request)
    {
        Log::info('Store method called.');

        $request->validate([
            'name' => 'required',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric',
            'features' => 'required|boolean',
            'description' => 'nullable',
            'status' => 'required|boolean',
            'images.*' => 'image|mimes:jpeg,png,jpg|max:1024',
        ]);

        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                Log::info('Processing image: ' . $image->getClientOriginalName());

                $filename = $image->getClientOriginalName();
                $path = $image->storeAs('public/images', $filename);

                Log::info('Stored image at path: ' . $path);
                $imagePaths[] = 'images/' . $filename;
            }
        }

        $item = Item::create([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'price' => $request->price,
            'features' => $request->features,
            'description' => $request->description,
            'status' => $request->status,
            'images' => json_encode($imagePaths),
        ]);

        return redirect()->route('items.index')->with('success', 'Item created successfully.');
    }


    public function show(Item $item)
    {
        return view('items.show', compact('item'));
    }

    public function edit(Item $item)
    {
        $categories = Category::all();
        $images = json_decode($item->images, true) ?? [];
        return view('items.edit', compact('item', 'categories', 'images'));
    }

    public function update(Request $request, Item $item)
    {
        $request->validate([
            'name' => 'required',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric',
            'features' => 'required|boolean',
            'description' => 'nullable',
            'status' => 'required|boolean',
            'images.*' => 'image|mimes:jpeg,png,jpg|max:1024',
        ]);
    
        $imagePaths = json_decode($item->images, true) ?? [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $filename = $image->getClientOriginalName();
                $path = $image->storeAs('public/images', $filename);
                $imagePaths[] = 'images/' . $filename;
            }
        }
    
        $item->update([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'price' => $request->price,
            'features' => $request->features,
            'description' => $request->description,
            'status' => $request->status,
            'images' => json_encode($imagePaths),
        ]);
    
        return redirect()->route('items.index')->with('success', 'Item updated successfully.');
    }

    public function destroy(Item $item)
{
    // Decode the JSON string to get the array of image paths
    $imagePaths = json_decode($item->images, true) ?? [];

    // Iterate over the array of image paths and delete each one
    foreach ($imagePaths as $imagePath) {
        Storage::delete('public/' . $imagePath);
    }

    // Delete the item record from the database
    $item->delete();

    return redirect()->route('items.index')->with('success', 'Item deleted successfully.');
}
}
