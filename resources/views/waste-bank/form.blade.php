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
                        <label for="waste_bank_name">Nama TPS3R</label>
                        <input autocomplete="off" type="text" class="form-control" id="waste_bank_name"
                            name="waste_bank_name" value="{{ old('waste_bank_name') }}">
                    </div>
                    <input type="hidden" id="update_id">
                    <div class="form-group">
                        <label for="village">Desa</label>
                        <select id='village_id' class="form-control" name='village_id' required>
                            @foreach ($villages as $village)
                                <option value="{{ $village->village_id }}">{{ $village->village_name }}</option>
                            @endforeach
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
