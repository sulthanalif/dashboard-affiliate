<?php

use Mary\Traits\Toast;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;

new class extends Component {
    use WithFileUploads, Toast;

    public $image;

    public function uploadImage($dataUri)
    {
        // Decode base64 image
        $imageData = base64_decode(explode(',', $dataUri)[1]);

        // Generate a unique filename
        $fileName = 'webcam_' . time() . '.png';

        // Save the file to the 'public/uploads' directory
        $filePath = public_path('uploads/' . $fileName);
        file_put_contents($filePath, $imageData);

        // Optionally store file name to a database or notify the user
        $this->image = '/uploads/' . $fileName;

        $this->success('Image uploaded successfully.', position: 'toast-bottom');
    }

}; ?>

<div>
<div id="webcam-container"></div>
    <button class="inline-flex justify-center px-4 py-2 text-base font-medium text-white bg-gray-600 border border-transparent rounded-md shadow-sm hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500" wire:click="captureImage" onclick="takeSnapshot()">Ambil Gambar</button>

    @if ($image)
        <h4>Hasil Gambar:</h4>
        <img src="{{ $image }}" alt="Captured Image" class="img-thumbnail">
    @endif

    <script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.25/webcam.min.js"></script>
    <script>
        Webcam.set({
            width: 320,
            height: 240,
            image_format: 'jpeg',
            jpeg_quality: 90
        });
        Webcam.attach('#webcam-container');

        function takeSnapshot() {
            Webcam.snap(function(dataUri) {
                Livewire.emit('uploadImage', dataUri);
            });
        }
    </script>
</div>
