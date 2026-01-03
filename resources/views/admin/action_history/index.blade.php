@extends('layouts.app')
@section('action_history')
    active
@endsection
@section('title')
    Action History
@endsection
@section('page-name')
    Action History
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">Action History</li>
@endsection

@section('content')
    <div class="row">

        <!-- Filter + Search -->
        <form method="GET" class="row mb-4">
            <div class="col-md-3">
                <select name="model_type" class="form-control select2" onchange="this.form.submit()">
                    <option value="">All Models</option>
                    @foreach ($models as $model)
                        <option value="{{ $model }}" {{ request('model_type') == $model ? 'selected' : '' }}>
                            {{ class_basename($model) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-5">
                <input type="text" name="search" class="form-control" placeholder="🔍 Search anything..."
                    value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <button class="btn btn-primary w-100">Search</button>
            </div>
        </form>

        <!-- Timeline -->
        <div class="timeline">
            @forelse ($logs as $index => $log)
                <div class="timeline-item mb-4 position-relative">
                    <div
                        class="timeline-line position-absolute top-0 start-2 bottom-0 border-start border-3 border-secondary">
                    </div>

                    <div class="card shadow-sm ms-4">
                        <div class="card-body">
                            <h6>
                                <span class="badge bg-info">{{ ucfirst($log->action_type) }}</span>
                                <small class="text-muted">
                                    {{ class_basename($log->model_type) }} #{{ $log->model_id }}
                                </small>
                            </h6>
                            <p class="mb-1"><strong>User:</strong> {{ optional($log->user)->full_name ?? 'System' }}</p>
                            <p class="mb-1"><strong>Route:</strong> {{ $log->route ?? 'N/A' }}</p>
                            <p class="mb-1 text-muted"><i class="bi bi-clock"></i>
                                {{ $log->created_at->format('Y-m-d H:i:s') }}</p>

                            @if ($log->changes)
                                <ul class="list-group list-group-flush">
                                    @foreach ($log->changes as $field => $values)
                                        @php
                                            $oldValueRaw = $values[0] ?? '';
                                            $newValueRaw = $values[1] ?? '';

                                            // Format datetime-like values
                                            if (preg_match('/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}/', $oldValueRaw)) {
                                                $oldValueRaw = Carbon\Carbon::parse($oldValueRaw)->format('Y-m-d H:i:s');
                                            }

                                            if (preg_match('/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}/', $newValueRaw)) {
                                                $newValueRaw = Carbon\Carbon::parse($newValueRaw)->format('Y-m-d H:i:s');
                                            }

                                            // Preview for long text
                                            $limit = 120;
                                            $oldPreview = \Illuminate\Support\Str::limit($oldValueRaw, $limit, '...');
                                            $newPreview = \Illuminate\Support\Str::limit($newValueRaw, $limit, '...');

                                            // Base64 encode full values
                                            $oldB64 = base64_encode($oldValueRaw);
                                            $newB64 = base64_encode($newValueRaw);

                                            // Unique IDs
                                            $safeField = \Illuminate\Support\Str::slug($field);
                                            $oldId = "chg-{$index}-{$safeField}-old";
                                            $newId = "chg-{$index}-{$safeField}-new";

                                            // Flags for show more
                                            $oldLong = mb_strlen($oldValueRaw) > $limit;
                                            $newLong = mb_strlen($newValueRaw) > $limit;
                                        @endphp

                                        <li class="list-group-item">
                                            <div class="d-flex flex-column">
                                                <div class="d-flex justify-content-between align-items-start mb-1">
                                                    <strong class="me-2">{{ $field }}</strong>
                                                </div>

                                                {{-- OLD --}}
                                                <div class="mb-1">
                                                    <span class="text-danger">Old:</span>
                                                    <span id="{{ $oldId }}"
                                                        class="value-preview">{{ $oldPreview }}</span>
                                                    @if ($oldLong)
                                                        <button class="btn btn-link btn-sm p-0 ms-2 toggle-btn"
                                                            data-target="{{ $oldId }}"
                                                            data-full="{{ $oldB64 }}">
                                                            Show More
                                                        </button>
                                                    @endif
                                                </div>

                                                {{-- NEW --}}
                                                <div>
                                                    <span class="text-success">New:</span>
                                                    <span id="{{ $newId }}"
                                                        class="value-preview">{{ $newPreview }}</span>
                                                    @if ($newLong)
                                                        <button class="btn btn-link btn-sm p-0 ms-2 toggle-btn"
                                                            data-target="{{ $newId }}"
                                                            data-full="{{ $newB64 }}">
                                                            Show More
                                                        </button>
                                                    @endif
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-muted">No actions found.</p>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-4 d-flex justify-content-center">
    @if ($logs->hasPages())
        <ul class="pagination pagination-sm">
            {{-- Previous --}}
            @if ($logs->onFirstPage())
                <li class="page-item disabled"><span class="page-link">« Prev</span></li>
            @else
                <li class="page-item"><a class="page-link" href="{{ $logs->previousPageUrl() }}">« Prev</a></li>
            @endif

            {{-- First Page --}}
            @if ($logs->currentPage() > 2)
                <li class="page-item"><a class="page-link" href="{{ $logs->url(1) }}">1</a></li>
                @if ($logs->currentPage() > 3)
                    <li class="page-item disabled"><span class="page-link">…</span></li>
                @endif
            @endif

            {{-- Main Pages Around Current --}}
            @for ($i = max(1, $logs->currentPage() - 2); $i <= min($logs->lastPage(), $logs->currentPage() + 2); $i++)
                @if ($i == $logs->currentPage())
                    <li class="page-item active"><span class="page-link">{{ $i }}</span></li>
                @else
                    <li class="page-item"><a class="page-link" href="{{ $logs->url($i) }}">{{ $i }}</a></li>
                @endif
            @endfor

            {{-- Last Page --}}
            @if ($logs->currentPage() < $logs->lastPage() - 1)
                @if ($logs->currentPage() < $logs->lastPage() - 2)
                    <li class="page-item disabled"><span class="page-link">…</span></li>
                @endif
                <li class="page-item"><a class="page-link" href="{{ $logs->url($logs->lastPage()) }}">{{ $logs->lastPage() }}</a></li>
            @endif

            {{-- Next --}}
            @if ($logs->hasMorePages())
                <li class="page-item"><a class="page-link" href="{{ $logs->nextPageUrl() }}">Next »</a></li>
            @else
                <li class="page-item disabled"><span class="page-link">Next »</span></li>
            @endif
        </ul>
    @endif
</div>


    </div>

@endsection

@section('script')
    <script>
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('toggle-btn')) {
                const target = document.getElementById(e.target.dataset.target);
                const fullValue = atob(e.target.dataset.full);
                if (target.innerText.endsWith('...')) {
                    target.innerText = fullValue;
                    e.target.innerText = 'Show Less';
                } else {
                    target.innerText = fullValue.substring(0, 120) + '...';
                    e.target.innerText = 'Show More';
                }
            }
        });
    </script>
@endsection
