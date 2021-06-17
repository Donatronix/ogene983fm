@php
use App\Helpers\Helper;
$helper=new Helper;
$userRoles=$user->roles()->pluck('id')->toArray();
@endphp
<!-- Modal -->
<div class="modal fade" id="roleModal" tabindex="-1" role="dialog" aria-labelledby="roleModalLabel">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        {!! Form::open(['method' => 'post', 'id'=>'roleForm', 'url' => route("roles.update.user", ['user' => $user->slug])]) !!}
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="roleModalLabel">Roles</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- name Form Input -->
                <div class="container">
                    <div class="form-group row">
                        @foreach ($roles as $role)
                        <div class="col-sm-4">
                            <p> {{ $helper->uppercaseWords($role->name) }}</p>
                            <div class="toggle-flip">
                                <label>
                                    {!! Form::checkbox('roles[]', $role->name, in_array($role->id, $userRoles) , []) !!}
                                    <span class="flip-indecator" data-toggle-on="ON" data-toggle-off="OFF"></span>
                                </label>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <!-- Submit Form Button -->
                {!! Form::submit('Update', ['class' => 'btn btn-primary pull-left','id' => 'update_role']) !!}
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                <div class="clearfix"></div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
