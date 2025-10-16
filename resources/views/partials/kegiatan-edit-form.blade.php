<div id="edit-form-{{ $kegiatan->id }}" class="hidden mt-2">
    <form method="POST" action="{{ route('setting.task-list.update', $kegiatan->id) }}" class="space-y-2 bg-white border border-gray-200 p-4 rounded shadow-sm">
        @csrf @method('PUT')

        {{-- Nama Kegiatan --}}
        <div class="flex flex-col">
            <label class="text-sm font-medium text-gray-700 mb-1">Nama Kegiatan</label>
            <input type="text" name="nama_kegiatan" value="{{ $kegiatan->nama_kegiatan }}" class="text-sm border border-gray-300 px-3 py-2 rounded focus:ring-2 focus:ring-blue-200 focus:outline-none" required>
        </div>

        {{-- Poin --}}
        <div class="flex flex-col">
            <label class="text-sm font-medium text-gray-700 mb-1">Poin</label>
            <input type="number" name="poin" value="{{ $kegiatan->poin }}" class="text-sm border border-gray-300 px-3 py-2 rounded focus:ring-2 focus:ring-blue-200 focus:outline-none" min="0" max="100" required>
        </div>

        {{-- Status Kegiatan --}}
        <div class="flex flex-col">
            <label class="text-sm font-medium text-gray-700 mb-1">Status</label>
            <select name="status_kegiatan" class="text-sm border border-gray-300 px-3 py-2 rounded focus:ring-2 focus:ring-blue-200 focus:outline-none" required>
                <option value="Aktif" {{ $kegiatan->status_kegiatan === 'Aktif' ? 'selected' : '' }}>Belum</option>
                <option value="Selesai" {{ $kegiatan->status_kegiatan === 'Selesai' ? 'selected' : '' }}>Selesai</option>
            </select>
        </div>

        <div>
            <button type="submit" class="w-full text-sm bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600 transition duration-200">Simpan</button>
        </div>
    </form>
</div>
