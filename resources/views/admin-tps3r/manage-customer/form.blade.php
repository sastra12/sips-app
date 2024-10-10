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
                    <input type="hidden" name="update_id" id="update_id">
                    <div class="form-group">
                        <label for="customer_name">Nama Pelanggan</label>
                        <input autocomplete="off" type="text" class="form-control" id="customer_name"
                            name="customer_name" value="{{ old('customer_name') }}">
                    </div>
                    <div class="form-group">
                        <label for="customer_address">Alamat</label>
                        <input autocomplete="off" type="text" class="form-control" id="customer_address"
                            name="customer_address" value="{{ old('	customer_address') }}">
                    </div>
                    <div class="form-group">
                        <label for="customer_neighborhood">RT</label>
                        <input autocomplete="off" type="text" class="form-control" id="customer_neighborhood"
                            name="customer_neighborhood" value="{{ old('customer_neighborhood') }}">
                    </div>
                    <div class="form-group">
                        <label for="customer_community_association">RW</label>
                        <input autocomplete="off" type="text" class="form-control"
                            id="customer_community_association" name="customer_community_association"
                            value="{{ old('customer_community_association') }}">
                    </div>
                    <div class="form-group">
                        <label for="rubbish_fee">rubbish_fee</label>
                        <input autocomplete="off" type="text" class="form-control" id="rubbish_fee"
                            name="rubbish_fee" value="{{ old('rubbish_fee') }}">
                    </div>
                    <div class="form-group">
                        <label for="customer_status">Status</label>
                        <select id='customer_status' class="form-control" name='customer_status'>
                            <option value="">Pilih Status</option>
                            @foreach ($customer_status as $status)
                                <option value="{{ $status }}">{{ $status }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" id="save-project-btn" class="btn btn-primary">Simpan</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
