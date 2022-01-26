@extends('layouts.merchant')

@section('content')

            <div class="layout-px-spacing">
                
                <div class="row layout-top-spacing">
                
                    <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
                        <div class="widget-content widget-content-area br-6">
                            <div class="table-responsive mb-4 mt-4">
                                <table id="zero-config" class="table table-hover company_list" style="width:100%">
                                <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>応募者ID</th>
                                            <th>送信日時</th>
                                            <th>レシートID</th>
                                            <th>付与ポイント</th>
                                            <th>ステータス</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="pro_id">1</td>
                                            <td class="pro_name"rowspan="3">rsquare</td>
                                            <td class="pro_open">2021-09-25 00:00:00</td>
                                            <td class="pro_open"><a class="blue mb-2 re_confirm_btn" data-toggle="modal" data-target="#editModal">1234</a></td>
                                            <td class="pro_start">20</td>
                                            <td class="pro_start">不正疑い</td>
                                        </tr>
                                        <tr>
                                            <td class="pro_id">2</td>
                                            <td class="pro_open">2021-09-25 00:00:00</td>
                                            <td class="pro_open"><a class="blue mb-2 re_confirm_btn" data-toggle="modal" data-target="#editModal">1235</a></td>
                                            <td class="pro_start">20</td>
                                            <td class="pro_start">正常処理済</td>
                                        </tr>
                                        <tr>
                                            <td class="pro_id">3</td>
                                            <td class="pro_open">2021-09-25 00:00:00</td>
                                            <td class="pro_open"><a class="blue mb-2 re_confirm_btn" data-toggle="modal" data-target="#editModal">1236</a></td>
                                            <td class="pro_start">20</td>
                                            <td class="pro_start">正常処理済</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>

            </div>

<div class="add_button">
    <a class="bs-tooltip" title="会社追加" href="{{ route('admin.company.create') }}">+</a>
</div>

@endsection