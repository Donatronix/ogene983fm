<div class="bs-component">
    <!-- Modal -->
    <div class="modal fade" id="profileModal" style="display: block;">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title pull-left" id="exampleModalLabel">Online Handle</h1>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                </div>
                <form action="{{ route('user.socialmediahandle.update', ['socialMediaHandle' => $socialMediaHandle->slug]) }}"
                      method="POST">
                    <div class="modal-body">
                        <div class="modal-product clearfix">
                            @csrf
                            @method('PUT')
                            <div class="row mb-20">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="form-group">
                                            {{Form::select('platform', ['Website'=>'Website','Facebook'=>'Facebook','Twitter'=>'Twitter','LinkedIn'=>'LinkedIn'], old('platform', $socialMediaHandle->platform),
                                                    [
                                                    "class" => "custom-select",
                                                    "placeholder" => "Select platform...",
                                                    'required'=>'required'
                                                    ]
                                                )
                                            }}
                                            @error('platform')
                                            <div class="invalid-feedback text-danger" role="alert">
                                                <strong>{!! $message !!}</strong>
                                            </div>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <input type="text" name="handle"
                                                   value="{{ old('handle', $socialMediaHandle->handle) }}"
                                                   placeholder="Enter handle here..." required>
                                            @error('handle')
                                            <div class="invalid-feedback text-danger" role="alert">
                                                <strong>{!! $message !!}</strong>
                                            </div>
                                            @enderror
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div><!-- .modal-product -->

                    </div><!-- .modal-body -->
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success" data-dismiss="modal">Save</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div><!-- .modal-content -->
        </div><!-- .modal-dialog -->
    </div>
    <!-- END Modal -->
</div>
<!-- END QUICKVIEW PRODUCT -->

@push('js')
    <script src="{{ asset('backend/js/jquery.address.js') }}"></script>
@endpush
