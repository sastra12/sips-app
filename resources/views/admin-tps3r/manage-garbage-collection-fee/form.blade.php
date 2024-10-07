<!-- Modal -->
<div class="modal fade" id="modal-form-monthly-bill" tabindex="-1" role="dialog" aria-hidden="true">
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
                        <label for="month_monthly_bill">Tagihan Bulan</label>
                        <select class="form-control" name="month" id="month_monthly_bill">
                            <option value="">Pilih Bulan</option>
                            <option value="01">Januari</option>
                            <option value="02">Februari</option>
                            <option value="03">Maret</option>
                            <option value="04">April</option>
                            <option value="05">Mei</option>
                            <option value="06">Juni</option>
                            <option value="07">Juli</option>
                            <option value="08">Agustus</option>
                            <option value="09">September</option>
                            <option value="10">Oktober</option>
                            <option value="11">November</option>
                            <option value="12">Desember</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="year_waste_payment">Tagihan Tahun</label>
                        <input autocomplete="off" type="text" class="form-control" id="year_waste_payment"
                            name="year_waste_payment" value="{{ old('year_waste_payment') }}">
                    </div>
                    <input type="hidden" id="customerId">
                    <div class="form-group">
                        <label for="amount_due">Jumlah Tagihan</label>
                        <input autocomplete="off" type="text" class="form-control" id="amount_due" name="amount_due"
                            disabled="disabled">
                    </div>
                    <div class="modal-footer">
                        <button id="save-project" type="submit" class="btn btn-primary">Simpan</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
