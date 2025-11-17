@extends('layout.template')

@section('content')
    <div class="main-content-inner">
        <div class="row">
            <div class="col-12 mt-1">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title">Daftar Umpan Balik</h4>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Feedback ID</th>
                                    <th>User ID</th>
                                    <th>Laporan ID</th>
                                    <th>Rating</th>
                                    <th>Komentar</th>
                                    <th>Created At</th>
                                    <th>Updated At</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($feedbacks->isEmpty())
                                    <tr>
                                        <td colspan="7"><p>Tidak ada umpan balik yang tersedia.</p></td>
                                    </tr>
                                @else
                                    @foreach ($feedbacks as $feedback)
                                        <tr>
                                            <td>{{ $feedback->feedback_id }}</td>
                                            <td>{{ $feedback->user_id }}</td>
                                            <td>{{ $feedback->laporan_id }}</td>
                                            <td>{{ $feedback->rating }}</td>
                                            <td>{{ $feedback->komentar }}</td>
                                            <td>{{ $feedback->created_at }}</td>
                                            <td>{{ $feedback->updated_at }}</td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection