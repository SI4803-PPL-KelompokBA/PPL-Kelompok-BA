@props([
    'name',
    'label' => 'Bukti dokumen',
])

@php
    $inputId = $attributes->get('id', $name);
@endphp

<div
    x-data="{
        fileName: '',
        fileSize: '',

        formatSize(bytes) {
            if (bytes < 1024) {
                return bytes + ' B';
            }

            if (bytes < 1048576) {
                return (bytes / 1024).toFixed(2) + ' KB';
            }

            return (bytes / 1048576).toFixed(2) + ' MB';
        },

        updateFile(event) {
            const file = event.target.files[0];

            this.fileName = file ? file.name : '';
            this.fileSize = file ? this.formatSize(file.size) : '';
        }
    }"
>
    <x-input-label :for="$inputId" :value="$label" />

    <input
        type="file"
        id="{{ $inputId }}"
        name="{{ $name }}"
        accept=".pdf,.jpg,.jpeg,.png"
        x-on:change="updateFile($event)"
        class="mt-1 block w-full text-sm text-gray-600
               file:mr-4 file:rounded-md file:border-0
               file:bg-indigo-50 file:px-4 file:py-2
               file:text-sm file:font-semibold
               file:text-indigo-700 hover:file:bg-indigo-100"
        {{ $attributes->except(['id', 'label']) }}
    />

    <div class="mt-2 text-sm text-gray-500">
        <span x-text="fileName || 'Belum ada file dipilih'"></span>

        <span
            x-show="fileSize"
            x-text="'(' + fileSize + ')'"
        ></span>
    </div>

    <x-input-error
        :messages="$errors->get($name)"
        class="mt-2"
    />
</div>