<!-- Modal -->
<div class="modal fade" id="modal-form" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <ul id="error_list">

                </ul>
                <form action="" method="post">
                    @csrf
                    @method('post')
                    <div class="form-group">
                        <label for="name">Nama</label>
                        <input autocomplete="off" type="text" class="form-control" id="name" name="name"
                            value="{{ old('name') }}">
                    </div>
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input readonly autocomplete="off" type="text" class="form-control" id="username"
                            name="username" value="{{ old('username') }}">
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input readonly autocomplete="off" type="text" class="form-control" id="password"
                            name="password" value="{{ old('password') }}">
                        <p>
                            Default password sama dengan username.
                        </p>
                    </div>
                    <div class="form-group">
                        <label for="role_user">Role User</label>
                        <select id='role_user' class="form-control" name='role_user' required>
                            @foreach ($roles as $role)
                                <option value="{{ $role->role_id }}">{{ $role->role_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group" id="waste_name_group" style="display: none">
                        <label for="waste_name">Nama TPS3R</label>
                        <select id='waste_name' class="form-control" name='waste_name' required>
                            <option value=""></option>
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" id="save-btn" class="btn btn-primary">Simpan</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
