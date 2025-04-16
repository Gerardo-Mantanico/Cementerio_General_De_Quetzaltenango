<div class="mb-4">
    <label for="{{ $id ?? $name }}" class="block text-sm font-medium text-gray-700">{{ $label ?? ucfirst($name) }}</label>
    <select name="{{ $name }}" id="{{ $id ?? $name }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
        <option value="">Seleccione una opción</option>
        @foreach($opciones as $opcion)
            <option value="{{ $opcion[$value] }}" {{ $opcion[$value] == old($name) ? 'selected' : '' }}>
                {{ $opcion[$text] }}
            </option>
        @endforeach
    </select>
    @error($name)
        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
    @enderror
</div>
