<div class="tile user-settings">
    <h4 class="line-head">Uploads</h4>
    <div class="container">
        <div class="row">
            <div class="container mb-3" style="{{ $files!==null ? "display:block;" : "display:none;" }}">
                <div class="row line-head">
                    @foreach ($files as $file)

                    @if ($helper->isImage($file->upload))
                    <div class="mb-3 card">
                        <div class="row no-gutters">
                            <div class="col-md-4">
                                <img src="{{ asset($file->upload) }}" alt="" class="card-img">
                            </div>
                            <div class="col-md-8">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $file->title }}</h5>
                                    <p class="card-text">{{ nl2br($file->description) }}</p>
                                    <button class="btn btn-danger" wire:click="delete({{ $file->id }})">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                    <button class="btn btn-primary" wire:click="edit({{ $file->id }})">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    @elseif($helper->isVideo($file->upload))
                    <div class="mb-3 card">
                        <div class="row no-gutters">
                            <div class="col-md-4">
                                <video controls preload="auto" class="card-img">
                                    <source src="{{ asset($file->upload) }}">
                                </video>
                            </div>
                            <div class="col-md-8">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $file->title }}</h5>
                                    <p class="card-text">{{ nl2br($file->description) }}</p>
                                    <button class="btn btn-danger" wire:click="delete({{ $file->id }})">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                    <button class="btn btn-primary" wire:click="edit({{ $file->id }})">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    @elseif($helper->isAudio($file->upload))
                    <div class="mb-3 card">
                        <div class="row no-gutters">
                            <div class="col-md-4">
                                <audio controls preload="auto" class="card-img">
                                    <source src="{{ asset($file->upload) }}" type="{{ $helper->getMimeType($file->upload) }}">
                                </audio>
                            </div>
                            <div class="col-md-8">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $file->title }}</h5>
                                    <p class="card-text">{{ nl2br($file->description) }}</p>
                                    <button class="btn btn-danger" wire:click="delete({{ $file->id }})">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                    <button class="btn btn-primary" wire:click="edit({{ $file->id }})">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    @elseif ($helper->isDocument($file->upload))
                    <div class="mb-3 card">
                        <div class="row no-gutters">
                            <div class="col-md-4">
                                <img src="{{ $helper->getFileIcon($file->upload) }}" alt="" class="card-img">
                            </div>
                            <div class="col-md-8">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $file->title }}</h5>
                                    <p class="card-text">{{ nl2br($file->description) }}</p>
                                    <button class="btn btn-danger" wire:click="delete({{ $file->id }})">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                    <button class="btn btn-primary" wire:click="edit({{ $file->id }})">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    @endforeach
                </div>
            </div>


            <div class="container">
                @include('errors.list')
                <form wire:submit.prevent="uploadFile" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="exampleInputTitle">Title</label>
                            <input name="title" class="form-control" id="exampleInputTitle" type="text" placeholder="Enter title" wire:model.lazy="title">
                            @error('title')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">

                        <div class="col-md-4" style="{{ $preview!==null ? "display:block;" : "display:none;" }}">
                            <div class="container mb-3">
                                <div class="row line-head">
                                    @if ($helper->isImage($preview))
                                    <div class="mb-3 card">
                                        <div class="row no-gutters">
                                            <div class="col-md-12">
                                                <img src="{{ asset($preview) }}" alt="" class="card-img">
                                            </div>
                                        </div>
                                    </div>

                                    @elseif($helper->isVideo($preview))
                                    <div class="mb-3 card">
                                        <div class="row no-gutters">
                                            <div class="col-md-12">
                                                <video controls preload="auto" class="card-img">
                                                    <source src="{{ asset($preview) }}">
                                                </video>
                                            </div>
                                        </div>
                                    </div>

                                    @elseif($helper->isAudio($preview))
                                    <div class="mb-3 card">
                                        <div class="row no-gutters">
                                            <div class="col-md-12">
                                                <audio controls preload="auto" class="card-img">
                                                    <source src="{{ asset($preview) }}" type="{{ $helper->getMimeType($preview) }}">
                                                </audio>
                                            </div>
                                        </div>
                                    </div>

                                    @elseif ($helper->isDocument($preview))
                                    <div class="mb-3 card">
                                        <div class="row no-gutters">
                                            <div class="col-md-12">
                                                <img src="{{ $helper->getFileIcon($preview) }}" alt="" class="card-img">
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-md-8">
                            <label class="control-label">Select file to upload</label>
                            <input class="form-control" type="file" wire:model.lazy='upload' id="upload{{ $iteration }}">
                            @error('upload')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="description">Description</label>
                            <textarea wire:model.lazy="description" class="form-control" id="description" rows="3" style="resize:none;"></textarea>
                            @error('description')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>

                    <div class="clear-fix"></div>
                    <div class="tile-footer">
                        <button class="btn btn-primary" type="submit">Upload</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
