<!-- Modal -->
<div class="modal fade" id="modal-form-edit" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <ul id="error_list_edit">

                </ul>
                <form action="" method="post">
                    @csrf
                    @method('post')
                    <div class="form-group">
                        <label for="role_user_form">Role User</label>
                        <input type="hidden" name="update_id" id="update_id">
                        <select id='role_user_form' class="form-control">
                            @foreach ($roles as $role)
                                <option value="{{ $role->role_id }}">{{ $role->role_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group" id="waste_name_group_edit" style="display: none">
                        <label for="waste_name_edit">Nama TPS3R</label>
                        <select id='waste_name_edit' class="form-control" name='waste_name_edit' required>
                            <option value=""></option>
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button id="save-project-btn" type="submit" class="btn btn-primary">Simpan</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
