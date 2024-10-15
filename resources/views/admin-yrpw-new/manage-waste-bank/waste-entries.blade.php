<!-- Modal -->
<div class="modal fade" id="modal-form-tonase" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <ul id="error_list_tonase">

                </ul>
                <form action="" method="post">
                    @csrf
                    @method('post')
                    <div class="form-group">
                        <label for="waste_organic">Sampah Organik</label>
                        <input autocomplete="off" type="text" class="form-control" id="waste_organic"
                            name="waste_organic" value="{{ old('waste_organic') }}">
                    </div>
                    <input type="hidden" id="waste_bank_id">
                    <div class="form-group">
                        <label for="waste_anorganic">Sampah Anorganik</label>
                        <input autocomplete="off" type="text" class="form-control" id="waste_anorganic"
                            name="waste_anorganic" value="{{ old('waste_anorganic') }}">
                    </div>
                    <div class="form-group">
                        <label for="waste_residue">Sampah Residu</label>
                        <input autocomplete="off" type="text" class="form-control" id="waste_residue"
                            name="waste_residue" value="{{ old('waste_residue') }}">
                    </div>
                    <div class="form-group">
                        <label for="date_entri">Tanggal</label>
                        <input type="date" class="form-control" id="date_entri" name="date_entri">
                    </div>
                    <div class="modal-footer">
                        <button id="save-project-tonase" type="submit" class="btn btn-sm btn-primary">Simpan</button>
                        <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
