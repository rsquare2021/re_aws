@extends('layouts.app')

@section('content')

            <div class="layout-px-spacing">
                
                <div class="row layout-top-spacing">
                
                    <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
                        <div class="widget-content widget-content-area br-6">
                            <div class="table-responsive mb-4 mt-4">
                            <form>
                                <h6>権限名</h6>
                                <div class="row mb-4">
                                    <div class="col">
                                        <input type="text" class="form-control" name="role_name" placeholder="権限名">
                                    </div>
                                </div>
                                <input type="submit" name="time" class="btn btn-primary role_create_btn">
                            </form>
                            </div>
                        </div>
                    </div>

                </div>

            </div>

@endsection