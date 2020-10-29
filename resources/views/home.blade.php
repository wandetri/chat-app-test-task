@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="chat-container col-md-9 col-lg-10 p-3">
            <div class="row chat-row">
                <div class="chat-bubble p-3 col-md-5">
                    <strong>Username</strong>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Velit, consequuntur reprehenderit itaque
                        pariatur minus iusto, voluptas corporis dolore dolorem qui, cupiditate a sit aperiam enim eius
                        illo rerum omnis quod.</p>
                </div>
            </div>
            <div class="row chat-row">
                <div class="chat-bubble p-3 col-md-5">
                    <strong>Username</strong>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Velit, consequuntur reprehenderit itaque
                        pariatur minus iusto, voluptas corporis dolore dolorem qui, cupiditate a sit aperiam enim eius
                        illo rerum omnis quod.</p>
                </div>
            </div>
            <div class="row chat-row-self">
                <div class="chat-bubble p-3 of offset-md-7 col-md-5 ">
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Velit, consequuntur reprehenderit itaque
                        pariatur minus iusto, voluptas corporis dolore dolorem qui, cupiditate a sit aperiam enim eius
                        illo rerum omnis quod.</p>
                </div>
            </div>
            <div class="row chat-row-self">
                <div class="chat-bubble p-3 of offset-md-7 col-md-5 ">
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Velit, consequuntur reprehenderit itaque
                        pariatur minus iusto, voluptas corporis dolore dolorem qui, cupiditate a sit aperiam enim eius
                        illo rerum omnis quod.</p>
                </div>
            </div>
            <div class="row chat-row">
                <div class="chat-bubble p-3 col-md-5">
                    <strong>Username</strong>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Velit, consequuntur reprehenderit itaque
                        pariatur minus iusto, voluptas corporis dolore dolorem qui, cupiditate a sit aperiam enim eius
                        illo rerum omnis quod.</p>
                </div>
            </div>
        </div>
        <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
            <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
                <span>Online Member</span>
            </h6>
            <div class="sidebar-sticky pt-3">
                @if($users->count())
                    @foreach($users as $user)
                        <a class="contact-link" href="{{route('message.conversation',$user->id)}}">
                            <div class="media contact p-2 border-bottom border-gray">
                                <div class="ava-bg bg-success text-light ">
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
    <textarea class="form-control" placeholder="Type a Message"></textarea>
    <div class="input-group-append">
        <button type="submit" class="btn btn-lg btn-send"><i class="fas fa-paper-plane"></i></button>
    </div>
</div>
@endsection
