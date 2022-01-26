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

<!-- モーダル -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">確認</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>
            <div class="modal-body">
                <div class="layout-px-spacing">
                    <div class="row layout-top-spacing">
                        <div class="col-xl-6 col-lg-6 col-sm-6 gray layout-spacing">
                            <div class="re_head mb-3">
                                <h4>1234</h4>
                                <p class="create_date">作成日：2021-09-25 00:00:00</p>
                                <h5>メモ</h5>
                                <p>メモ内容が入りますメモ内容が入りますメモ内容が入ります</p>
                            </div>
                            <div class="re_body">
                                <img src="{{asset('assets/img/re_sample.jpg')}}">
                                <!-- <div id="original-img">
                                    <img src="{{asset('assets/img/re_sample.jpg')}}">
                                    <span id="zoom-cursor"></span>
                                </div>
                                <div id="zoom-img">
                                    <img src="{{asset('assets/img/re_sample.jpg')}}">
                                </div> -->
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-sm-6 gray_border layout-spacing">
                            <center>ステータス：<span>不正疑い</span></center>
                            <p class="at_detail mb-4">同じユーザーが短期間に複数のレシートを送信しています</p>
                            <h5>読み取り結果の確認</h5>
                            <p class="mt-3">基本情報</p>
                            <table class="table">
                                <tr>
                                    <td>レシート送信日時：</td>
                                    <td> 2021-06-02 09:01:10</td>
                                </tr>
                                <tr>
                                    <td>レシート発行日時：</td>
                                    <td>2021-06-01 17:25</td>
                                </tr>
                                <tr>
                                    <td>レシートNo：</td>
                                    <td>6079-17</td>
                                </tr>
                                <tr>
                                    <td>データNo：</td>
                                    <td>9744-9746</td>
                                </tr>
                                <tr>
                                    <td>SS電話番号：</td>
                                    <td>052-262-4661</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-danger user_delete_btn" data-dismiss="modal"><i class="flaticon-cancel-12"></i> 削除</button>
                <button class="btn" data-dismiss="modal"><i class="flaticon-cancel-12"></i> 閉じる</button>
                <button type="button" class="btn btn-primary user_edit_btn" id="">保存</button>
            </div>
        </div>
    </div>
</div>

@endsection