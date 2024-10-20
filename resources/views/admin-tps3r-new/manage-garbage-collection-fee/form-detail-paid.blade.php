<!-- Modal -->
<div class="modal fade" id="modal-form-detail-paid" tabindex="-1" role="dialog" aria-hidden="true">
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
                        <label for="year_waste_payment_detail">Tagihan Tahun</label>
                        <input autocomplete="off" type="text" class="form-control" id="year_waste_payment_detail"
                            name="year_waste_payment_detail" value="{{ old('year_waste_payment_detail') }}">
                    </div>
                    <input type="hidden" id="customerId">
                    <div class="modal-footer">
                        <button id="download-detail-paid" type="submit"
                            class="btn btn-sm btn-primary">Download</button>
                        <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
