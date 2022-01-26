@extends('layouts.user')

@section('content')
    <div class="form-container outer">
        <div class="form-form">
            <div class="form-form-wrap">
                <div class="form-container">
                    <div class="form-content">

                        <script>
                            console.log($.cookie("_token"));
                        </script>

                        <div class="base_window">
                            <h3>パスワード再設定用URL送信完了</h3>
                            <form class="text-left" action="{{ route('campaign.login', request()->route()->parameter('campaign_id')) }}" method="post">
                                @csrf
                                <div class="form p-3">
                                    <p>お客様のメールアドレス宛にパスワード再設定完了用URLを送信しました。<br>メールアドレスに記載されたURLに遷移し新しいパスワードで再度ログインしてください。</p>
                                </div>
                            </form>
                        </div>

                    </div>                    
                </div>
            </div>
        </div>
    </div>

@endsection