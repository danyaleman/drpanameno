@extends('layouts.master-layouts')
@section('title')
    {{ __('Front Setting') }}
@endsection
@section('content')
    <!-- start page title -->
    @component('components.breadcrumb')
        @slot('title')
            Front Setting
        @endslot
        @slot('li_1')
            Dashboard
        @endslot
        @slot('li_2')
            Setting
        @endslot
        @slot('li_3')
            Front Setting
        @endslot
    @endcomponent
    <!-- end page title -->

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <blockquote>Enable/Disable Views</blockquote>

                    <div class="table-responsive">
                        <table class="table table-bordered nowrap">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Title</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($sections as $key => $section)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $section->title }}</td>
                                        <td>
                                            @if ($section->is_enable == 0)
                                                <span class="badge bg-success">Enable</span>
                                            @else
                                                <span class="badge bg-warning">Disable</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($section->is_enable == 0)
                                                <a href="{{ route('section.disable', ['id' => $section->id]) }}"
                                                    class="btn btn-sm btn-warning">Disable</a>
                                            @else
                                                <a href="{{ route('section.enable', ['id' => $section->id]) }}"
                                                    class="btn btn-sm btn-success">Enable</a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
