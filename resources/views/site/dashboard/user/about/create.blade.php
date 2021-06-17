<form id="aboutForm" action="{{ route('user.about.store', ['user' => $user->slug]) }}" method="POST">
    @csrf
    <div class="form-group">
        {{Form::textarea("description", old("description"),
            [
                "placeholder" => "About",
                "required"    => "required",
                'style'       => 'height:210px; resize:none',
            ])
        }}
        @error('description')
        <div class="invalid-feedback text-danger" role="alert">
            <strong>{!! $message !!}</strong>
        </div>
        @enderror
    </div>
</form>
