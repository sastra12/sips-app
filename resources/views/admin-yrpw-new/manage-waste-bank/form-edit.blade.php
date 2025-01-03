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
                        <label for="waste_bank_name">Nama TPS3R</label>
                        <input autocomplete="off" type="text" class="form-control" id="waste_bank_name_edit"
                            name="waste_bank_name" value="{{ old('waste_bank_name') }}">
                        <input type="hidden" id="update_id">
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-sm btn-primary" id="save-project-edit-btn">Simpan</button>
                        <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
