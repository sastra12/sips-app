@extends('layouts-new.master')

@section('content')
    <div class="main-title">
        <span class="material-icons-outlined"> space_dashboard </span>
        <span class="title">Dashboard</span>
    </div>
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body table-responsive">
                        @if (session('batchId') && session('message'))
                            <div class="alert alert-info">
                                {{ session('message') }}
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <h4>Status In The Background</h4>
                                    <p id="status"></p>
                                    <p id="processed"></p>
                                </div>
                            </div>
                        @else
                            <h4>Sedang tidak ada file yang di upload</h4>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script>
        async function checkBatchStatus() {
            const url = "{{ route('batch-status-tps3r') }}";
            try {
                const response = await fetch(url);
                const json = await response.json();
                if (json.status) {
                    document.getElementById('status').innerText = `Status: ${json.status}`;
                    document.getElementById('processed').innerText =
                        `Diproses: ${json.processed_jobs} dari ${json.total_jobs} ${(json.progress)}%`;
                }
                if (json.status === 'Selesai') {
                    clearInterval(interval);
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                }
            } catch (error) {
                console.log(error)
            }
        }
        const interval = setInterval(checkBatchStatus, 3000);
    </script>
@endpush
