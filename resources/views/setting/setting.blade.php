@extends('layouts.master-layouts')
@section('title')
    {{ __('App Setting') }}
@endsection
    @section('content')
        <!-- start page title -->
        @component('components.breadcrumb')
            @slot('title')
                App Setting
            @endslot
            @slot('li_1')
                Dashboard
            @endslot
            @slot('li_2')
                Setting
            @endslot
            @slot('li_3')
                App Setting
            @endslot
        @endcomponent
        <!-- end page title -->

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <blockquote>{{ __('Setting Instructions') }}</blockquote>

                        <div class="row">
                            <div class="col-lg-2">
                                <div class="card border border-primary">
                                    <img class="card-img-top img-fluid" src="{{ URL::asset('build/images/settings/title.png') }}" alt="Card image cap">
                                    <div class="card-body">
                                        <h4 class="card-title mb-2">App Name</h4>
                                        <p class="card-text">Application name will display on top of the page.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="card border border-primary">
                                    <img class="card-img-top img-fluid" src="{{ URL::asset('build/images/settings/logo-sm.png') }}" alt="Card image cap">
                                    <div class="card-body">
                                        <h4 class="card-title mb-2">Logo Small</h4>
                                        <p class="card-text">Small logo will display on mobile screen.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="card border border-primary">
                                    <img class="card-img-top img-fluid" src="{{ URL::asset('build/images/settings/logo-lg.png') }}" alt="Card image cap">
                                    <div class="card-body">
                                        <h4 class="card-title mb-2">Logo Large</h4>
                                        <p class="card-text">This is lg logo display default on site.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="card border border-primary">
                                    <img class="card-img-top img-fluid" src="{{ URL::asset('build/images/settings/logo-sm-dark.png') }}" alt="Card image cap">
                                    <div class="card-body">
                                        <h4 class="card-title mb-2">Dark Logo Sm</h4>
                                        <p class="card-text">This is sm logo display when topbar is light on mobile screen.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="card border border-primary">
                                    <img class="card-img-top img-fluid" src="{{ URL::asset('build/images/settings/logo-lg-dark.png') }}" alt="Card image cap">
                                    <div class="card-body">
                                        <h4 class="card-title mb-2">Dark Logo Lg</h4>
                                        <p class="card-text">This is sm logo display when topbar is light.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="card border border-primary">
                                    <img class="card-img-top img-fluid" src="{{ URL::asset('build/images/settings/favicon.png') }}" alt="Card image cap">
                                    <div class="card-body">
                                        <h4 class="card-title mb-2">Favicon</h4>
                                        <p class="card-text">This is favicon appear near App title.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <blockquote>{{ __('Setting Details') }}</blockquote>
                        <form action="{{ route('update-setting') }}" method="post" enctype="multipart/form-data">
                        @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="appTitle">App Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="appTitle" value="{{ @$data->title }}" name="title">
                                        <small id="appTitleHelp" class="form-text text-muted">Please Enter minimum 5 or maximum 40 caracters.</small>
                                        @error('title')
                                            <span class="text-danger">{{$message}}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="appLogo">Logo Small</label>
                                        <input type="file" class="form-control" data-allow-reorder="true" data-max-file-size="2MB" data-max-files="1" value="" name="logo_sm">
                                        <small class="form-text text-muted">Please Enter only jpg, png, svg files, a good looking logo dimensions are 64x75.</small>
                                        @error('logo_sm')
                                            <span class="text-danger">{{$message}}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="appLogo">Logo Large</label>
                                        <input type="file" class="form-control" data-allow-reorder="true" data-max-file-size="2MB" data-max-files="1" value="" name="logo_lg">
                                        <small class="form-text text-muted">Please Enter only jpg, png, svg files, a good looking logo dimensions are 440x75.</small>
                                        @error('logo_lg')
                                            <span class="text-danger">{{$message}}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="appLogo">Dark Logo Small</label>
                                        <input type="file" class="form-control" data-allow-reorder="true" data-max-file-size="2MB" data-max-files="1" value="" name="logo_dark_sm">
                                        <small class="form-text text-muted">Please Enter only jpg, png, svg files, a good looking logo dimensions are 64x75.</small>
                                        @error('logo_dark_sm')
                                            <span class="text-danger">{{$message}}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="appLogo">Dark Logo Large</label>
                                        <input type="file" class="form-control" data-allow-reorder="true" data-max-file-size="2MB" data-max-files="1" value="" name="logo_dark_lg">
                                        <small class="form-text text-muted">Please Enter only jpg, png, svg files, a good looking logo dimensions are 440x75.</small>
                                        @error('logo_dark_lg')
                                            <span class="text-danger">{{$message}}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="appFavicon">Favicon</label>
                                        <input type="file" class="form-control" id="appFavicon" data-allow-reorder="true" data-max-file-size="2MB" data-max-files="1" name="favicon">
                                        <small class="form-text text-muted">Please Enter only jpg, png, svg, ico files, a good looking icon dimensions are 128x128.</small>
                                        @error('favicon')
                                            <span class="text-danger">{{$message}}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <blockquote>{{ __('Footer Details') }}</blockquote>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="footerLeft">Footer Left <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="footerLeft" name="footer_left" value="{{ @$data->footer_left }}">
                                        <small class="form-text text-muted">Please Enter minimum 5 or maximum 40 caracters.</small>
                                        @error('footer_left')
                                            <span class="text-danger">{{$message}}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="footerRight">Footer Right <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="footerRight" name="footer_right" value="{{ @$data->footer_right }}">
                                        <small class="form-text text-muted">Please Enter minimum 5 or maximum 80 caracters.</small>
                                        @error('footer_right')
                                            <span class="text-danger">{{$message}}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- end row -->
    @endsection
