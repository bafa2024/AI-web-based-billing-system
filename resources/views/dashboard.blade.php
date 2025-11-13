@extends('layouts.app')

@section('content')
<div class="container-fluid mt-4 px-0 mx-0">
    <h2 class="mb-4 px-4">Dashboard</h2>

    <div class="row px-4">
        <!-- Invoice Summary -->
        <div class="col-md-6 mb-4">
            <h4 class="mb-3">Invoice Summary</h4>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="card shadow border-0" style="border-radius: 8px; overflow: hidden;">
                        <div class="p-3 text-white" style="background-color:#f39c12; min-height:150px;">
                            <div class="d-flex flex-column justify-content-between h-100">
                                <div class="d-flex align-items-start justify-content-between">
                                    <div>
                                        <h3 class="mb-1 fw-bold" style="font-size: 1.75rem;">${{ number_format($draftInvoices ?? 0, 2) }}</h3>
                                        <p class="mb-0" style="font-size: 0.9rem;">Draft Invoices</p>
                                    </div>
                                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width:44px;height:44px;background-color:rgba(255,255,255,0.25);">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 16 16" fill="#ffffff">
                                            <path d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793l.646.647a.5.5 0 0 1-.708.708L9.5 2.5l-.646.647a.5.5 0 0 1-.708-.708l.647-.647-.647-.646a.5.5 0 0 0-.707 0l-.293.293a.5.5 0 0 0 0 .707l.647.646-.647.646a.5.5 0 0 0 0 .707l.293.293a.5.5 0 0 0 .707 0l.646-.647.646.647a.5.5 0 0 0 .707 0l.293-.293a.5.5 0 0 0 0-.707l-.647-.646.647-.646a.5.5 0 0 0 0-.707L12.854.146zM11.207 2.5L13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V13h1.793l6.5-6.5zm0 1L11.5 6.207 4.707 13H6v.5a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1-.5-.5v-3a.5.5 0 0 1 .5-.5H4v.793L10.793 4.5z"/>
                </svg>
            </div>
                                </div>
                                <div class="mt-3">
                                    <a href="/invoices?status=draft" class="btn btn-light btn-sm w-100">View Draft Invoices →</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 mb-3">
                    <div class="card shadow border-0" style="border-radius: 8px; overflow: hidden;">
                        <div class="p-3 text-white" style="background-color:#00a8e6; min-height:150px;">
                            <div class="d-flex flex-column justify-content-between h-100">
                                <div class="d-flex align-items-start justify-content-between">
            <div>
                                        <h3 class="mb-1 fw-bold" style="font-size: 1.75rem;">${{ number_format($sentInvoices ?? 0, 2) }}</h3>
                                        <p class="mb-0" style="font-size: 0.9rem;">Sent Invoices</p>
                                    </div>
                                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width:44px;height:44px;background-color:rgba(255,255,255,0.25);">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 16 16" fill="#ffffff">
                                            <path d="M15.964.686a.5.5 0 0 0-.65-.65L.767 5.855H.766l-.452.18a.5.5 0 0 0-.082.887l.41.26.001.002 4.995 3.178 3.178 4.995.002.002.26.41a.5.5 0 0 0 .886-.083l6-15Zm-1.833 1.89L6.637 10.07l-.215-.338a.5.5 0 0 0-.154-.154l-.338-.215 7.494-7.494 1.178-.471-.47 1.178Z"/>
                                        </svg>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <a href="/invoices?status=sent" class="btn btn-light btn-sm w-100">View Sent Invoices →</a>
                                </div>
                            </div>
            </div>
        </div>
    </div>

                <div class="col-md-6 mb-3">
                    <div class="card shadow border-0" style="border-radius: 8px; overflow: hidden;">
                        <div class="p-3 text-white" style="background-color:#e74c3c; min-height:150px;">
                            <div class="d-flex flex-column justify-content-between h-100">
                                <div class="d-flex align-items-start justify-content-between">
                                    <div>
                                        <h3 class="mb-1 fw-bold" style="font-size: 1.75rem;">${{ number_format($overdueInvoices ?? 0, 2) }}</h3>
                                        <p class="mb-0" style="font-size: 0.9rem;">Overdue Invoices</p>
                                    </div>
                                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width:44px;height:44px;background-color:rgba(255,255,255,0.25);">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 16 16" fill="#ffffff">
                                            <path d="M8.982 1.566a1.13 1.13 0 0 0-1.964 0L.165 13.233c-.457.778.091 1.767.982 1.767h13.706c.89 0 1.438-.99.982-1.767zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                </svg>
            </div>
                                </div>
                                <div class="mt-3">
                                    <a href="/invoices?status=overdue" class="btn btn-light btn-sm w-100">View Overdue Invoices →</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 mb-3">
                    <div class="card shadow border-0" style="border-radius: 8px; overflow: hidden;">
                        <div class="p-3 text-white" style="background-color:#27ae60; min-height:150px;">
                            <div class="d-flex flex-column justify-content-between h-100">
                                <div class="d-flex align-items-start justify-content-between">
            <div>
                                        <h3 class="mb-1 fw-bold" style="font-size: 1.75rem;">${{ number_format($paymentsCollected ?? 0, 2) }}</h3>
                                        <p class="mb-0" style="font-size: 0.9rem;">Payments Collected</p>
                                    </div>
                                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width:44px;height:44px;background-color:rgba(255,255,255,0.25);">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 16 16" fill="#ffffff">
                                            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                            <path d="M4.285 9.567a.5.5 0 0 1 .683.183A3.498 3.498 0 0 0 8 11.5a3.498 3.498 0 0 0 3.032-1.75.5.5 0 1 1 .866.5A4.498 4.498 0 0 1 8 12.5a4.498 4.498 0 0 1-3.898-2.25.5.5 0 0 1 .183-.683zM7 6.5C7 7.328 6.552 8 6 8s-1-.672-1-1.5S5.448 5 6 5s1 .672 1 1.5zm4 0c0 .828-.448 1.5-1 1.5s-1-.672-1-1.5S9.448 5 10 5s1 .672 1 1.5z"/>
                                        </svg>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <a href="/payments" class="btn btn-light btn-sm w-100">View Payments →</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quote Summary -->
        <div class="col-md-6 mb-4">
            <h4 class="mb-3">Quote Summary</h4>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="card shadow border-0" style="border-radius: 8px; overflow: hidden;">
                        <div class="p-3 text-white" style="background-color:#9b59b6; min-height:150px;">
                            <div class="d-flex flex-column justify-content-between h-100">
                                <div class="d-flex align-items-start justify-content-between">
                                    <div>
                                        <h3 class="mb-1 fw-bold" style="font-size: 1.75rem;">${{ number_format($draftQuotes ?? 0, 2) }}</h3>
                                        <p class="mb-0" style="font-size: 0.9rem;">Draft Quotes</p>
                                    </div>
                                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width:44px;height:44px;background-color:rgba(255,255,255,0.25);">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 16 16" fill="#ffffff">
                                            <path d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793l.646.647a.5.5 0 0 1-.708.708L9.5 2.5l-.646.647a.5.5 0 0 1-.708-.708l.647-.647-.647-.646a.5.5 0 0 0-.707 0l-.293.293a.5.5 0 0 0 0 .707l.647.646-.647.646a.5.5 0 0 0 0 .707l.293.293a.5.5 0 0 0 .707 0l.646-.647.646.647a.5.5 0 0 0 .707 0l.293-.293a.5.5 0 0 0 0-.707l-.647-.646.647-.646a.5.5 0 0 0 0-.707L12.854.146zM11.207 2.5L13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V13h1.793l6.5-6.5zm0 1L11.5 6.207 4.707 13H6v.5a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1-.5-.5v-3a.5.5 0 0 1 .5-.5H4v.793L10.793 4.5z"/>
                                        </svg>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <a href="/quotes?status=draft" class="btn btn-light btn-sm w-100">View Draft Quotes →</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 mb-3">
                    <div class="card shadow border-0" style="border-radius: 8px; overflow: hidden;">
                        <div class="p-3 text-white" style="background-color:#16a085; min-height:150px;">
                            <div class="d-flex flex-column justify-content-between h-100">
                                <div class="d-flex align-items-start justify-content-between">
                                    <div>
                                        <h3 class="mb-1 fw-bold" style="font-size: 1.75rem;">${{ number_format($sentQuotes ?? 0, 2) }}</h3>
                                        <p class="mb-0" style="font-size: 0.9rem;">Sent Quotes</p>
                                    </div>
                                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width:44px;height:44px;background-color:rgba(255,255,255,0.25);">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 16 16" fill="#ffffff">
                                            <path d="M15.964.686a.5.5 0 0 0-.65-.65L.767 5.855H.766l-.452.18a.5.5 0 0 0-.082.887l.41.26.001.002 4.995 3.178 3.178 4.995.002.002.26.41a.5.5 0 0 0 .886-.083l6-15Zm-1.833 1.89L6.637 10.07l-.215-.338a.5.5 0 0 0-.154-.154l-.338-.215 7.494-7.494 1.178-.471-.47 1.178Z"/>
                                        </svg>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <a href="/quotes?status=sent" class="btn btn-light btn-sm w-100">View Sent Quotes →</a>
                                </div>
                            </div>
            </div>
        </div>
    </div>

                <div class="col-md-6 mb-3">
                    <div class="card shadow border-0" style="border-radius: 8px; overflow: hidden;">
                        <div class="p-3 text-white" style="background-color:#e67e22; min-height:150px;">
                            <div class="d-flex flex-column justify-content-between h-100">
                                <div class="d-flex align-items-start justify-content-between">
                                    <div>
                                        <h3 class="mb-1 fw-bold" style="font-size: 1.75rem;">${{ number_format($rejectedQuotes ?? 0, 2) }}</h3>
                                        <p class="mb-0" style="font-size: 0.9rem;">Rejected Quotes</p>
                                    </div>
                                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width:44px;height:44px;background-color:rgba(255,255,255,0.25);">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 16 16" fill="#ffffff">
                                            <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8 2.146 2.854Z"/>
                </svg>
            </div>
                                </div>
                                <div class="mt-3">
                                    <a href="/quotes?status=rejected" class="btn btn-light btn-sm w-100">View Rejected Quotes →</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 mb-3">
                    <div class="card shadow border-0" style="border-radius: 8px; overflow: hidden;">
                        <div class="p-3 text-white" style="background-color:#2980b9; min-height:150px;">
                            <div class="d-flex flex-column justify-content-between h-100">
                                <div class="d-flex align-items-start justify-content-between">
            <div>
                                        <h3 class="mb-1 fw-bold" style="font-size: 1.75rem;">${{ number_format($approvedQuotes ?? 0, 2) }}</h3>
                                        <p class="mb-0" style="font-size: 0.9rem;">Approved Quotes</p>
                                    </div>
                                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width:44px;height:44px;background-color:rgba(255,255,255,0.25);">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 16 16" fill="#ffffff">
                                            <path d="M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0z"/>
                                        </svg>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <a href="/quotes?status=approved" class="btn btn-light btn-sm w-100">View Approved Quotes →</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Client Activity -->
    <div class="row mt-4 px-4">
        <div class="col-12">
            <div class="card shadow border-0">
                <div class="card-body">
                    <h4 class="mb-3">Recent Client Activity</h4>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Activity</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentActivity ?? [] as $activity)
                                <tr>
                                    <td>{{ $activity->created_at instanceof \Illuminate\Support\Carbon ? $activity->created_at->format('d/m/Y') : \Carbon\Carbon::parse($activity->created_at)->format('d/m/Y') }}</td>
                                    <td>{{ $activity->description }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="2" class="text-center text-muted">No recent activity to display.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
