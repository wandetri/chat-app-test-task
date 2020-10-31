@extends('layouts.app')
@section('contact-init',initials($friendInfo->name))
@section('contact-name',$friendInfo->name)
@section('contact-id',$friendInfo->id)
@section('content')
<div class="container-fluid">
    <div class="row p-2 chat-loading">
        <div class="d-flex col-md-10 justify-content-md-center">
            <span class="spinner-border text-secondary" role="status">
            </span>
        </div>
    </div>
    <div class="row">
        <div class="chat-container col-md-9 col-lg-10 p-3">
        @if($messages->count())
            @foreach($messages as $message)
                <div class="row px-2 py-1 {{($message->user_message->sender_id==$userId)?'flex-row':'flex-row-reverse'}}">
                    <div class="chat-bubble p-2">
                        {{$message->message}}
                    </div>
                </div>
            @endforeach
        @endif
        </div>
        <nav id="sidebarMenu" class="col-md-3 col-lg-2 bg-light sidebar ">
            <a class="contact-group"
        href="{{route('message.group-conversation')}}">
                <div
                    class="media contact p-2 border-bottom border-gray">
                    <div class="ava-bg text-light contact-id">
                        <span class="ava-init"><i class="fa fa-users"></i></span>
                    </div>
                    <p class="media-body py-2 px-3 mb-0">
                        Group Chat
                    </p>
                </div>
            </a>
            <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
                <span id="text-member">All Member</span>
                <div class="custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input" id="onlineSwitch">
                    <label class="custom-control-label" for="onlineSwitch"></label>
                </div>
            </h6>
            <div class="sidebar-sticky pt-3">
                @if($users->count())
                    @foreach($users as $user)
                    <a class="contact-link contact-id-{{ $user->id }}"
                        href="{{ route('message.conversation',$user->id) }}">
                        <div class="media contact @if($user->id == $userId) active @endif p-2 border-bottom border-gray">
                            <div class="ava-bg text-light">
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
        <button disabled id="button-send" type="submit" class="btn btn-lg btn-send"><i class="fas fa-paper-plane"></i></button>
    </div>
</div>
@endsection

@push('scripts')
    <script>
        $(function () {

            let token = "{{ csrf_token() }}";
            let jwt ="{{$token}}";

            let ipaddr = '127.0.0.1';
            let port = '4848';
            let socket = io.connect(ipaddr + ':' + port, {
                query: `token=${jwt}`
            });


            let user_id = "{{ auth()->user()->id }}";
            let lastDate ="{{$lastDate}}";

            let $textMessage = $('#text-message-input');
            let $chatContainer =$('.chat-container');
            let $messageLoading = $('.chat-loading');
            let $buttonSend =$('#button-send');
            let $onlineSwitch = $('#onlineSwitch');
            let $textMember = $('#text-member');
            let $contactLink = $('.contact-link');

            moveScrollTo();

            function htmlEntities(str) {
                return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
            }

            /* move scroll bar to end (true) half (false) */
            function moveScrollTo(half=false){
                $chatContainer.scrollTop($chatContainer.height()/(half?2:1));
            }
            
            /* generate chat bubble component */
            function chatBubbleGenerator(message,self=false){
                return `<div class="row px-2 py-1 ${self?'flex-row-reverse':'flex-row'}">
                    <div class="chat-bubble p-2">
                        ${htmlEntities(message.message)}
                    </div>
                </div>`;
            }

            $onlineSwitch.change(function (e) {
                if (this.checked) {
                    $textMember.text('Online Member');
                } else {
                    $textMember.text('All Member');
                }
                $('.contact-link:not(.contact-online)').toggle()
            });

            $textMessage.keypress(function (e) {
                let message = $textMessage.val();
                if (e.keyCode == 13 && !e.shiftKey) {
                    $textMessage.val('');
                    event.preventDefault();
                    sendMessage(message);
                }
            });

            $textMessage.keyup(function(e){
                if(($textMessage.val().trim().length > 0)){
                    $buttonSend.prop( "disabled", false );
                }else{
                    $buttonSend.prop( "disabled", true );
                }
            })

            $buttonSend.on('click',function(){
                let message = $textMessage.val();
                $textMessage.val('');
                sendMessage(message);
                $buttonSend.prop( "disabled", true );
            });

            $chatContainer.scroll(function(e){
                let scrollPosition = $chatContainer.scrollTop();
                if(scrollPosition==0){
                    $messageLoading.slideDown( 1000, function() {
                        getMessage();
                    });;
                }
            })

            /* load previous conversation messages */
            function getMessage(){
                let url = "{{ route('message.conversation-messages') }}";
                let form = $(this);
                let formData = new FormData();
                let friendId = "{{ $friendInfo->id }}";
                formData.append('userId', friendId);
                formData.append('_token', token);
                formData.append('lastDate', lastDate);
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: 'JSON',
                    success: function (response) {
                        if (response.success) {
                            let datasize=Object.keys(response.data).length;
                            if(datasize>0){ 
                                $.each(response.data, function (key, message) {
                                    $chatContainer.prepend(chatBubbleGenerator(message,message.user_message.receiver_id==friendId));
                                })
                                lastDate=response.data[datasize-1].created_at;
                                moveScrollTo(true);
                            }
                            $messageLoading.slideUp();
                        }
                    }
                })
            }

            /* send message and append chat bubble*/
            function sendMessage(msg) {
                let url = "{{ route('message.send-message') }}";
                let form = $(this);
                let formData = new FormData();
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

            /* socket processes */
            /*------------------*/
            socket.on('connect', function () {
                socket.emit('user_conn', user_id)
            });

            socket.on('updateUserStatus', function (data) {
                let $avaBgColor = $('.ava-bg');
                $avaBgColor.removeClass('bg-success');
                $avaBgColor.attr('title', 'Offline');

                $.each(data, function (key, val) {
                    if (val !== null && val !== 0) {
                        let $avaBg = $('.contact-id-' + key + ' .ava-bg');
                        $('.contact-id-' + key).addClass('contact-online');

                        $avaBg.addClass('bg-success');
                        $avaBg.attr('title', 'Online');
                    }
                })
            });

            socket.on("private-channel:App\\Events\\PrivateMessageEvent", function (message) {
                $chatContainer.append(chatBubbleGenerator(message));
                moveScrollTo();
            });

            /*------------------*/

        });

    </script>
@endpush
