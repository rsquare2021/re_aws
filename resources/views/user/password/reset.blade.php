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
                            <h3>パスワードの再設定</h3>
                            <form class="text-left" action="{{ route('campaign.password.email', request()->route('campaign_id')) }}" method="post">
                                @csrf
                                <div class="form p-3">
                                    <p>パスワード再設定用URLを送るメールアドレスを入力してください。</p>
                                    <div id="username-field" class="field-wrapper input">
                                        <label for="username">メールアドレス</label>
                                        <div class="relative">
                                            @error('email')
                                                <p class="alert alert-danger">{{ $message }}</p>
                                            @enderror
                                            <input id="username" name="email" type="text" class="form-control" placeholder="yourname@next-cp.com">
                                            <check class="mail_check_mark"></check>
                                        </div>
                                    </div>
                                    <div class="d-sm-flex justify-content-between mt-4">
                                        <div class="field-wrapper flex_btns">
                                            <a class="back" onclick="history.back()">戻る</a>
                                            <button type="submit" class="blue_btn submit register_confirm_btn noevent" value="">送信</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                    </div>                    
                </div>
            </div>
        </div>
    </div>

@endsection