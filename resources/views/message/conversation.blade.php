@extends('layouts.app')
@section('contact-init',initials($friendInfo->name))
@section('contact-name',$friendInfo->name)
@section('content')
<div class="container-fluid">
    <div class="row p-2 justify-content-md-center chat-loading">
        <div clas="col-md-12">
            <span class="spinner-border text-secondary" role="status">
            </span>
        </div>
    </div>
    <div class="row">
        <div class="chat-container col-md-9 col-lg-10 p-3">
        @if($messages->count())
            @foreach($messages as $message)
                <div class="row {{($message->user_message->sender_id==$userId)?'chat-row':'chat-row-self'}}">
                    <div class="chat-bubble p-3 col-md-5 {{($message->user_message->sender_id!=$userId)?'offset-md-7':''}}">
                        <p>{{$message->message}}</p>
                    </div>
                </div>
            @endforeach
        @endif
        </div>
        <nav id="sidebarMenu" class="col-md-3 col-lg-2 bg-light sidebar ">
            <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
                <span>Online Member</span>
            </h6>
            <div class="sidebar-sticky pt-3">
                @if($users->count())
                    @foreach($users as $user)
                        <a class="contact-link"
                            href="{{ route('message.conversation',$user->id) }}">
                            <div
                                class="media contact @if($user->id == $userId) active @endif p-2 border-bottom border-gray">
                                <div id="contact-id-{{ $user->id }}" class="ava-bg text-light ">
                                    <span class="ava-init">{{ initials($user->name) }}</i></span>
                                </div>
                                <p class="media-body py-2 px-3 mb-0">
                                    {{ $user->name }}
                                </p>
                            </div>
                        </a>
                    @endforeach
                @endif
            </div>
        </nav>
    </div>
</div>
<div class="message-field bg-light flex-md-nowrap p-3 col-md-9 col-lg-10 input-group">
    <textarea id="text-message-input" class="form-control" placeholder="Type a Message"></textarea>
    <div class="input-group-append">
        <button type="submit" class="btn btn-lg btn-send"><i class="fas fa-paper-plane"></i></button>
    </div>
</div>
@endsection

@push('scripts')
    <script>
        $(function () {
            let ipaddr = '127.0.0.1';
            let port = '4848';
            let socket = io(ipaddr + ':' + port);
            let user_id = "{{ auth()->user()->id }}";

            let $textMessage = $('#text-message-input');
            let $chatContainer =$('.chat-container');
            let $messageLoading = $('.chat-loading');
            moveScrollTo();

            $chatContainer.scroll(function(e){
                let scrollPosition = $chatContainer.scrollTop();
                if(scrollPosition==0){
                    $messageLoading.slideDown( 1000, function() {
                        getMessage();
                    });;
                }
            })
            function moveScrollTo(half=false){
                $chatContainer.scrollTop($chatContainer.height()/(half?2:1));
            }
            
            function chatBubbleGenerator(message,self=false){
                return `
                <div class="row chat-row${self?'-self':''}">
                    <div class="chat-bubble ${self?'offset-md-7':''} p-3 col-md-5">
                        <p>${message.message}</p>
                    </div>
                </div>`;
            }



            socket.on('connect', function () {
                socket.emit('user_conn', user_id)
            });

            socket.on('updateUserStatus', function (data) {
                let $avaBgColor = $('.ava-bg');
                $avaBgColor.removeClass('bg-success');
                $avaBgColor.attr('title', 'Offline');

                $.each(data, function (key, val) {
                    if (val !== null && val !== 0) {
                        let $avaBg = $('#contact-id-' + key);
                        $avaBg.addClass('bg-success');
                        $avaBg.attr('title', 'Online');
                    }
                })
            });

            $textMessage.keypress(function (e) {
                let message = $textMessage.val();
                if (e.keyCode == 13 && !e.shiftKey) {
                    $textMessage.val('');
                    event.preventDefault();
                    sendMessage(message);
                }
            });

            function getMessage(lastDate){
                let url = "{{ route('message.conversation-messages',['userId'=>$friendInfo->id,'lastDate'=>'1']) }}";
                let form = $(this);
                let formData = new FormData();
                let token = "{{ csrf_token() }}";
                let friendId = "{{ $friendInfo->id }}";

                $.ajax({
                    url: url,
                    type: 'GET',
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: 'JSON',
                    success: function (response) {
                        if (response.success) {
                            console.log(response);
                            $.each(response.data, function (key, message) {
                                $chatContainer.prepend(chatBubbleGenerator(message,message.user_message.receiver_id==friendId));
                            })
                            $messageLoading.slideUp();
                            moveScrollTo(true);
                        }
                    }
                })
            }

            function sendMessage(msg) {
                let url = "{{ route('message.send-message') }}";
                let form = $(this);
                let formData = new FormData();
                let token = "{{ csrf_token() }}";
                let friendId = "{{ $friendInfo->id }}";

                formData.append('message', msg);
                formData.append('_token', token);
                formData.append('receiver_id', friendId);

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: 'JSON',
                    success: function (response) {
                        if (response.success) {
                            $chatContainer.append(chatBubbleGenerator(response.data,true));
                            moveScrollTo();
                        }
                    }
                })
            }

            socket.on("private-channel:App\\Events\\PrivateMessageEvent", function (message) {
                $chatContainer.append(chatBubbleGenerator(message));
                moveScrollTo();
            });

        });

    </script>
@endpush
