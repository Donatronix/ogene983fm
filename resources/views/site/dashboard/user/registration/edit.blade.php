<div class="bs-component">
    <!-- Modal -->
    <div class="modal fade" id="profileModal" style="display: block;">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title pull-left" id="exampleModalLabel">Update Registration Info</h1>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                </div>
                <form action="{{ route('user.registration.update', ['registration' => $registration->slug, 'user' => $user->slug]) }}" method="POST">
                    <div class="modal-body">
                        <div class="modal-product clearfix">
                            @method('PUT')
                            @csrf
                            <div class="row mb-20">
                                <div class="row">
                                    <div class="col-md-12">
                                        <input type="text" placeholder="Registration number..." name="registration_number" value="{{ old('registration_number', $registration->registration_number) }}" required>
                                        @error('registration_number')
                                        <span class="invalid-feedback text-danger" role="alert">
                                                <strong>{!! $message !!}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="col-md-12">
                                        <input type="text" placeholder="Registration body..." name="registration_body" value="{{ old('registration_body', $registration->registration_body) }}" required>
                                        @error('registration_body')
                                        <span class="invalid-feedback text-danger" role="alert">
                                                <strong>{!! $message !!}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="col-md-12">
                                        {{Form::select('registration_country', $countries, old('registration_country', $registration->registration_country),
                                                [
                                                "id" => "country",
                                                "class" => "custom-select",
                                                "placeholder" => "Select Country...",
                                                'required'=>'required'
                                                ]
                                            )
                                        }}
                                        @error('registration_country')
                                        <div class="invalid-feedback text-danger" role="alert">
                                            <strong>{!! $message !!}</strong>
                                        </div>
                                        @enderror
                                    </div>

                                </div>
                            </div>
                        </div><!-- .modal-product -->

                    </div><!-- .modal-body -->
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success" data-dismiss="modal">Update</button>
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
<script>
    jQuery(function () {

        loadCountries();

        function loadCountries() {
            e.preventDefault();
            //get user roles

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: '/location/countries',
                method: 'post',
                dataType: 'json',
                success: function (data) {
                    var dropdown = $('#country');

                    dropdown.empty();

                    dropdown.append('<option selected="true" disabled>Choose Country</option>');
                    dropdown.prop('selectedIndex', 0);

                    $.each(data, function (key, entry) {
                        dropdown.append($('<option></option>').attr('value', entry.id).text(entry.name));
                    });
                },
                error: function (data) {
                    var errors = $.parseJSON(data.responseText);
                    console.log(errors);
                }
            });
        }
    });

</script>
@endpush
