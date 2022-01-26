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
                            <h3>メールアドレス確認の完了</h3>
                            <div class="p-4">
                                <p class="mb-3">メールアドレスの確認ができました。<br>引き続きキャンペーンをお楽しみください。</p>
                            </div>
                            <div class="registration_alert">
                                <h4>【重要：登録完了メールは必ず保管ください】</h4>
                                <p class="mb-3">メールアドレスを忘れてしまった場合、事務局にお問い合わせいただいてもお答えできず、再登録いただく必要がございます。予めご了承ください。</p>
                            </div>
                        </div>

                    </div>                    
                </div>
            </div>
        </div>
    </div>

@endsection