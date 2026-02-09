
@extends('layouts.app')

@section('title', 'Create Help Request')

@section('content')
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-2xl font-bold text-gray-900">Create New Help Request</h2>
                <p class="text-sm text-gray-600 mt-1">An instructor will be assigned automatically based on availability</p>
            </div>

            <form method="POST" action="{{ route('help-requests.store') }}" class="p-6">
                @csrf

                <!-- Vehicle Selection -->
                <div class="mb-6">
                    <label for="vehicle_id" class="block text-sm font-medium text-gray-700 mb-2">Vehicle *</label>
                    <select name="vehicle_id" id="vehicle_id" required class="w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-blue-500 focus:border-blue-500 @error('vehicle_id') border-red-500 @enderror">
                        <option value="">Select a vehicle</option>
                        @foreach($vehicles as $vehicle)
                            <option value="{{ $vehicle->id }}" {{ old('vehicle_id') == $vehicle->id ? 'selected' : '' }}>
                                {{ $vehicle->year }} {{ $vehicle->make }} {{ $vehicle->model }} ({{ $vehicle->vehicle_vin }})
                            </option>
                        @endforeach
                    </select>
                    @error('vehicle_id')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Bay Selection -->
                <div class="mb-6">
                    <label for="bay_id" class="block text-sm font-medium text-gray-700 mb-2">Work Bay (Optional)</label>
                    <select name="bay_id" id="bay_id" class="w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-blue-500 focus:border-blue-500 @error('bay_id') border-red-500 @enderror">
                        <option value="">Select a bay (optional)</option>
                        @foreach($bays as $bay)
                            <option value="{{ $bay->id }}" {{ old('bay_id') == $bay->id ? 'selected' : '' }}>
                                {{ $bay->bay_number }} - {{ $bay->bay_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('bay_id')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Category Selection -->
                <div class="mb-6">
                    <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">Issue Category *</label>
                    <select name="category_id" id="category_id" required class="w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-blue-500 focus:border-blue-500 @error('category_id') border-red-500 @enderror">
                        <option value="">Select a category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->icon }} {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Priority Level -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Priority Level *</label>
                    <div class="grid grid-cols-4 gap-4">
                        <label class="flex items-center justify-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50 {{ old('priority_level') === 'low' ? 'border-green-500 bg-green-50' : 'border-gray-300' }}">
                            <input type="radio" name="priority_level" value="low" {{ old('priority_level') === 'low' ? 'checked' : '' }} required class="sr-only">
                            <div class="text-center">
                                <div class="text-2xl mb-1">🟢</div>
                                <div class="text-sm font-medium">Low</div>
                            </div>
                        </label>
                        <label class="flex items-center justify-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50 {{ old('priority_level') === 'medium' ? 'border-yellow-500 bg-yellow-50' : 'border-gray-300' }}">
                            <input type="radio" name="priority_level" value="medium" {{ old('priority_level') === 'medium' ? 'checked' : '' }} required class="sr-only">
                            <div class="text-center">
                                <div class="text-2xl mb-1">🟡</div>
                                <div class="text-sm font-medium">Medium</div>
                            </div>
                        </label>
                        <label class="flex items-center justify-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50 {{ old('priority_level') === 'high' ? 'border-orange-500 bg-orange-50' : 'border-gray-300' }}">
                            <input type="radio" name="priority_level" value="high" {{ old('priority_level') === 'high' ? 'checked' : '' }} required class="sr-only">
                            <div class="text-center">
                                <div class="text-2xl mb-1">🟠</div>
                                <div class="text-sm font-medium">High</div>
                            </div>
                        </label>
                        <label class="flex items-center justify-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50 {{ old('priority_level') === 'emergency' ? 'border-red-500 bg-red-50' : 'border-gray-300' }}">
                            <input type="radio" name="priority_level" value="emergency" {{ old('priority_level') === 'emergency' ? 'checked' : '' }} required class="sr-only">
                            <div class="text-center">
                                <div class="text-2xl mb-1">🔴</div>
                                <div class="text-sm font-medium">Emergency</div>
                            </div>
                        </label>
                    </div>
                    @error('priority_level')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Title -->
                <div class="mb-6">
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Title (Optional)</label>
                    <input type="text" name="title" id="title" value="{{ old('title') }}" placeholder="Brief summary of the issue" class="w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-blue-500 focus:border-blue-500 @error('title') border-red-500 @enderror">
                    @error('title')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div class="mb-6">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description *</label>
                    <textarea name="description" id="description" rows="5" required placeholder="Describe the issue you're experiencing..." class="w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                    @error('description')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Buttons -->
                <div class="flex justify-end gap-3">
                    <a href="{{ route('student.dashboard') }}" class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                        Cancel
                    </a>
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 font-medium">
                        Submit Request
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
