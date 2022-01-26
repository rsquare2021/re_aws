@extends('layouts.app')

@section('content')

            <div class="layout-px-spacing">
                
                <div class="row layout-top-spacing">
                
                    <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
                        <div class="widget-content widget-content-area br-6">
                            <div class="table-responsive mb-4 mt-4">
                                <select class="form-control project_name" id="project_name">
                                    <option value="1">プロジェクト1</option>
                                    <option value="2">プロジェクト2</option>
                                </select>
                                <table id="zero-config" class="table table-hover user_list" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>アカウント名</th>
                                            <th>メールアドレス</th>
                                            <th>所持ポイント</th>
                                            <th>入会日時</th>
                                            <th>レシート</th>
                                            <th>応募</th>
                                            <th class="no-content"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="pro_id">1</td>
                                            <td class="pro_name">rsquare</td>
                                            <td class="pro_open">info@rsquare.co.jp</td>
                                            <td class="pro_open">1000</td>
                                            <td class="pro_start">2021-09-25 00:00:00</td>
                                            <td class="pro_end">
                                                <a href="/test/admin/project/apply?id=1" class="btn btn-outline-primary mb-2 pro_apply_btn">確認</a>
                                            </td>
                                            <td class="pro_end">
                                                <a href="/test/admin/enduser/apply?id=1" class="btn btn-outline-primary mb-2 pro_apply_btn">確認</a>
                                            </td>
                                            <td class="pro_count">
                                                <a href="/test/admin/project/apply?id=1" class="btn btn-outline-primary mb-2 pro_apply_btn">編集</a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>

            </div>

@endsection