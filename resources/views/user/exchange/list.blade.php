@extends('layouts.user')

@section('content')

    <h2 class="mypage_tit">景品交換履歴</h2>
        <div class="user_wrap">
            <div class="product_search mt-3 mb-4">
                <dl>
                    <dt>ステータス</dt>
                    <dd>
                        <ul class="ex_status">
                            <li><a data-status="0">全て</a></li>
                            <li><a data-status="13">確認中</a></li>
                            <li><a data-status="31">発送済み</a></li>
                            <!-- <li><a data-status="13">宛先入力待ち</a></li> -->
                            <li><a data-status="21">キャンセル</a></li>
                        </ul>
                    </dd>
                </dl>
            </div>
            @forelse ($applies as $apply)
                <div class="mb-4 ex_lists status{{ $apply->status->id }}">
                    <table>
                        <tr>
                            <th>交換ID</th>
                            <td>{{ $apply->id }}</td>
                        </tr>
                        <tr>
                            <th>交換申請日時</th>
                            <td>{{ $apply->created_at }}</td>
                        </tr>
                        <tr>
                            <th>ステータス</th>
                            <td>
                                @if($apply->status->name == "宛先入力待ち")
                                    確認中
                                @else
                                    {{ $apply->status->name }}
                                @endif    
                            </td>
                        </tr>
                        <tr>
                            <th>景品名</th>
                            <td>{{ $apply->product->name }}</td>
                        </tr>
                        <tr>
                            <th>数量</th>
                            <td>{{ $apply->quantity }}</td>
                        </tr>
                        <tr>
                            <th>消費ポイント</th>
                            <td>{{ $apply->getTotalPoint() }} pts</td>
                        </tr>
                        <tr>
                            <th>発送先</th>
                            <td>
                                @php
                                    $ad = $apply->shipping_address;
                                @endphp
                                @if ($ad)
                                    {{ $ad->last_name . " " . $ad->first_name }}<br>
                                    {{ $ad->post_code }}<br>
                                    {{ $ad->prefectures . $ad->municipalities . $ad->address_code . $ad->building }}<br>
                                    {{ $ad->tel }}
                                @else
                                @endif
                                @if ($apply->canEditAddress())
                                    <div class="w100 text-right">
                                        <a href="{{ route('campaign.apply.address.edit', [$campaign_id, $apply->id]) }}" class="back blue_btn">発送先の変更</a>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    </table>
                    @if ($apply->canEditAddress() or $apply->canCancel())
                        <div class="d-sm-flex justify-content-between mt-3 mb-2">
                            <div class="field-wrapper flex_btns">
                                @if ($apply->canCancel())
                                    <a href="{{ route('campaign.apply.cancel.confirm', [$campaign_id, $apply->id]) }}" class="back red_btn">交換のキャンセル</a>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            @empty
                <p class="mt-5 mb-3 text-center">履歴交換がありません</p>
            @endforelse
        </div>
    <p class="attention red">※ステータスが確認中の場合のみ発送先の変更、交換のキャンセルができます。</p>

@endsection