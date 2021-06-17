<form id="addressForm" action="{{ route('user.address.store', ['user' => $user->slug]) }}" method="POST">
    @csrf
    <div class="row mb-20">
        <div class="col-md-6">
            {{Form::textarea("address", old("address"),
                [
                    "placeholder" => "Address",
                    "required"    => "required",
                    'style'       => 'height:210px; resize:none',
                ])
            }}
            @error('address')
            <div class="invalid-feedback text-danger" role="alert">
                <strong>{!! $message !!}</strong>
            </div>
            @enderror
        </div>

        <div class="col-md-6">
            <div class="col-md-12">
                {{Form::select('country', $countries, old('country'),
                        [
                        "id" => "country",
                        "placeholder" => "Select Country...",
                        'required'=>'required'
                        ]
                    )
                }}
                @error('country')
                <div class="invalid-feedback text-danger" role="alert">
                    <strong>{!! $message !!}</strong>
                </div>
                @enderror
            </div>

            <div class="col-md-12">
                {{Form::select('state', $countries, old('state'),
                        [
                        "id" => "state",
                        "placeholder" => "Select State...",
                        'required'=>'required'
                        ]
                    )
                }}
                @error('state')
                <div class="invalid-feedback text-danger" role="alert">
                    <strong>{!! $message !!}</strong>
                </div>
                @enderror
            </div>

            <div class="col-md-12">
                {{Form::select('city', [], old('city'),
                        [
                        "id" => "city",
                        "placeholder" => "Select city...",
                        'required'=>'required'
                        ]
                    )
                }}
                @error('city')
                <div class="invalid-feedback text-danger" role="alert">
                    <strong>{!! $message !!}</strong>
                </div>
                @enderror
            </div>
            <div class="col-md-12">
                <input type="text" name="post_code" value="{{ old('post_code') }}" placeholder="Post Code">
                @error('post_code')
                <div class="invalid-feedback text-danger" role="alert">
                    <strong>{!! $message !!}</strong>
                </div>
                @enderror
            </div>
        </div>
    </div>
</form>
