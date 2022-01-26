この度は{{ $campaign->name }}にご参加いただきありがとうございます。

下記の内容でお問い合わせを受け付けいたしました。
事務局で内容を確認のうえご連絡させていただきますので、
しばらくお待ちいただきますようよろしくお願いいたします。

・お問い合わせID
{!! $contact_id !!}

・お問い合わせの種類
{!! $contact_type !!}

・お問い合わせの内容
{!! nl2br($contact) !!}

@include('emails.signature', ["campaign" => $campaign])