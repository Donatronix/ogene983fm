<form id="registrationForm" action="{{ route('user.registration.store', ['user' => $user->slug]) }}" method="POST">
    @csrf
    <div class="row mb-20">
        <div class="col-md-12">
            <input type="text" placeholder="Registration number..." name="registration_number"
                   value="{{ old('registration_number') }}" required>
            @error('registration_number')
            <div class="invalid-feedback text-danger" role="alert">
                <strong>{!! $message !!}</strong>
            </div>
            @enderror
        </div>
        <div class="col-md-12">
            <input type="text" placeholder="Registration body..." name="registration_body"
                   value="{{ old('registration_body') }}" required>
            @error('registration_body')
            <div class="invalid-feedback text-danger" role="alert">
                <strong>{!! $message !!}</strong>
            </div>
            @enderror
        </div>
        <div class="col-md-12">
            {{Form::select('registration_country', $registration_countries, old('registration_country'),
                    [
                    "id" => "registration_country",
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
        <div class="col-md-12">
            <input type="text" placeholder="Registration year (YYYY)" name="registration_year"
                   class="allowNumericWithoutDecimal" value="{{ old('registration_year') }}" required>
            @error('registration_year')
            <div class="invalid-feedback text-danger" role="alert">
                <strong>{!! $message !!}</strong>
            </div>
            @enderror
        </div>
    </div>
</form>
