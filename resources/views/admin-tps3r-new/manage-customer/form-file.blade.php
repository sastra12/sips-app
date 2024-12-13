<!-- Modal -->
<div class="modal fade" id="modal-form-file" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <ul id="error_list_file">

                </ul>
                <form action="">
                    @csrf
                    @method('post')
                    <div class="form-group">
                        <label for="customer_file">Upload File</label>
                        <input autocomplete="off" type="file" class="form-control" id="customer_file"
                            name="customer_file">
                    </div>
                    <div class="modal-footer">
                        <button type="submit" id="save-file-btn" class="btn btn-primary">Simpan</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
