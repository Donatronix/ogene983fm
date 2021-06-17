<?php

namespace App\Http\Livewire\Dashboard\Discussion;

use App\Helpers\Helper;
use App\Models\Discussion\Discussion;
use App\Models\Upload\Upload;
use Livewire\Component;
use Livewire\WithFileUploads;

class UploadFile extends Component
{

    use WithFileUploads;

    public $description;
    public $discussion;
    public $preview;
    public $title;
    public $upload;
    public $iteration;

    protected $listeners = ['render'];

    /**
     * Initialise variables
     *
     * @param \App\Models\Discussion\Discussion $discussion //model to upload files to
     *
     * @return void
     */
    public function mount(Discussion $discussion)
    {
        $this->discussion = $discussion;
    }

    /**
     * Render component view
     *
     * @return void
     */
    public function render()
    {
        return view('livewire.dashboard.discussion.upload-file', ['files' => $this->discussion->uploads, 'helper' => (new Helper)]);
    }


    /**
     * Upload file
     *
     * @return void
     */
    public function uploadFile()
    {
        if (isset($this->uploaded)) {
            $this->discussion->updateUpload($this->uploaded, $this->upload, $this->title, $this->description);
        } else {
            $this->discussion->upload($this->upload, $this->title, $this->description);
        }
        $this->description = "";
        $this->title = "";
        $this->upload = null;
        $this->iteration = rand();
        $this->uploaded = "";
        $this->preview = null;
        $this->emitSelf('render');
    }

    /**
     * Delete selected file
     *
     * @param integer $id //model Id
     *
     * @return void
     */
    public function delete(int $id)
    {
        Upload::findOrFail($id)->delete();
        $this->emitSelf('render');
    }

    public $uploaded;

    /**
     * Delete selected file
     *
     * @param integer $id //model Id
     *
     * @return void
     */
    public function edit(int $id)
    {
        $this->uploaded = Upload::findOrFail($id);
        $this->title = $this->uploaded->title;
        $this->description = $this->uploaded->description;
        $this->preview = $this->uploaded->upload;
    }
}
