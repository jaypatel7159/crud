@extends('layouts.app')

@section('title', 'Create/Edit Item')

@section('content')
    <h1>{{ isset($item) ? 'Edit' : 'Create' }} Item</h1>
    <form action="{{ isset($item) ? route('items.update', $item->id) : route('items.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @if (isset($item))
            @method('PUT')
        @endif

        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $item->name ?? '') }}" required>
            @error('name')
                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
        </div>
        <div class="form-group">
            <label for="category_id">Category</label>
            <select name="category_id" class="form-control @error('category_id') is-invalid @enderror" required>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ (old('category_id', $item->category_id ?? '') == $category->id) ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
            @error('category_id')
                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
        </div>
        <div class="form-group">
            <label for="price">Price</label>
            <input type="text" name="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price', $item->price ?? '') }}" required>
            @error('price')
                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
        </div>
        <div class="form-group">
            <label for="features">Features</label>
            <select name="features" class="form-control @error('features') is-invalid @enderror" required>
                <option value="1" {{ old('features', $item->features ?? '') ? 'selected' : '' }}>Yes</option>
                <option value="0" {{ !old('features', $item->features ?? '') ? 'selected' : '' }}>No</option>
            </select>
            @error('features')
                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea name="description" class="form-control @error('description') is-invalid @enderror">{{ old('description', $item->description ?? '') }}</textarea>
            @error('description')
                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
        </div>
        <div class="form-group">
            <label for="status">Status</label>
            <select name="status" class="form-control @error('status') is-invalid @enderror" required>
                <option value="1" {{ old('status', $item->status ?? '') ? 'selected' : '' }}>Active</option>
                <option value="0" {{ !old('status', $item->status ?? '') ? 'selected' : '' }}>Inactive</option>
            </select>
            @error('status')
                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
        </div>
        <div class="form-group">
            <label for="images">Images</label>
            <input type="file" name="images[]" class="form-control @error('images.*') is-invalid @enderror" multiple>
            <div class="mt-2">
                @isset($images)
                    @foreach($images as $image)
                        <div class="d-inline-block mr-2">
                            <img src="{{ asset('storage/' . $image) }}" alt="Image" class="img-thumbnail" style="width: 100px; height: 100px;">
                        </div>
                    @endforeach
                @endisset
            </div>
            @error('images.*')
                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary">{{ isset($item) ? 'Update' : 'Create' }}</button>
    </form>
@endsection
