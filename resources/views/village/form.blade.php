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
                        <label for="village_name">Nama Desa</label>
                        <input autocomplete="off" type="text" class="form-control" id="village_name"
                            name="village_name" value="{{ old('village_name') }}">
                    </div>
                    <div class="form-group">
                        <label for="village_code">Kode Desa</label>
                        <input autocomplete="off" type="text" class="form-control" id="village_code"
                            name="village_code" value="{{ old('village_code') }}">
                    </div>
                    <input type="hidden" name="update_id" id="update_id">
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" id="save-project-btn">Simpan</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
