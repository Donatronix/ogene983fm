<form id="socialmediahandleForm" action="{{ route('user.socialmediahandle.store', ['user' => $user->slug]) }}"
      method="POST">
    @csrf
    <div class="row mb-20">
        <div class="col-md-6">
            {{Form::select('platform', ['Website'=>'Website','Facebook'=>'Facebook','Twitter'=>'Twitter','LinkedIn'=>'LinkedIn'], old('platform'),
                    [
                    "placeholder" => "select platform...",
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
        <div class="col-md-6">
            <input type="text" name="handle" value="{{ old('handle') }}" placeholder="Enter handle here..." required>
            @error('handle')
            <div class="invalid-feedback text-danger" role="alert">
                <strong>{!! $message !!}</strong>
            </div>
            @enderror
        </div>
    </div>
</form>
