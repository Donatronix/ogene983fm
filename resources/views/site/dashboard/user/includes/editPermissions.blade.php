@php
use App\Helpers\Helper;
$helper=new Helper;
$userPermissions=$user->permissions()->pluck('id')->toArray();
@endphp
<!-- Modal -->
<div class="modal fade" id="permissionModal" tabindex="-1" role="dialog" aria-labelledby="permissionModalLabel">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        {!! Form::open(['method' => 'post', 'id'=>'permissionForm', 'url' => route("permissions.update.user", ['user' => $user->slug])]) !!}
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="permissionModalLabel">Permissions</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- name Form Input -->
                <div class="container">
                    <div class="form-group row">
                        @foreach ($permissions as $permission)
                        <div class="col-sm-4">
                            <p><b>{{ $helper->uppercaseWords($permission->name) }}</b></p>
                            <div class="toggle-flip">
                                <label>
                                    {!! Form::checkbox('permissions[]', $permission->id, in_array($permission->id, $userPermissions) , ['class' => 'permissions']) !!}<span class="flip-indecator" data-toggle-on="ON" data-toggle-off="OFF"></span>
                                </label>
                            </div>

                        </div>
                        @endforeach
                        <div class="clearfix"></div>
                        <hr>
                        <div class="col-md-6">
                            <div class="animated-checkbox mt-4">
                                <label>
                                    {!! Form::checkbox('select_all', 'Select All', false, ['id' => 'select_all']) !!}
                                    <span class="label-text">Select All</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <!-- Submit Form Button -->
                {!! Form::submit('Update', ['class' => 'btn btn-primary','id' => 'update_permission']) !!}
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
