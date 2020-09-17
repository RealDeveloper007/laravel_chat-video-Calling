<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/chat.css') }}" rel="stylesheet">
    <link href="{{ asset('css/toastr.css') }}" rel="stylesheet">

    
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/toastr.min.js') }}"></script>

   </head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>
</body>
</html>

<script>
var ImagePath = "{{ asset('images')}}";

// Open the Chat Window  Function
function openChatWindow(user) 
    {        
        $('#userNameList li a.active.show').removeClass('active show').css('display', 'none');
        $('.tab-content .tab-pane.active.show').removeClass('active show').css('visibility', 'visible');
        $('#tab-' + user).addClass('active show').css('visibility', 'visible');
        $('#user-' + user).addClass('active show').show();
        scroll_message_window(user);
    }


// Scroll Down Function
function scroll_message_window(user)
{
     $('#view_user'+user).hide();
     $('#sharefileaction_'+user).hide();
     $('.chat-tab.tabs-container').css({"float": "none", "width": "100%"});
     $('#bodyId_' + user).attr("placeholder", "Type your answer here");
     $('#bodyId_' + user).removeClass('placeholder');
     var length = $('#chat-discussion_'+ user +' > div').length;
     var height = $("#chat-discussion_" + user).height();
     length = length * 300 ;
     $("#chat-discussion_" + user).animate({
        scrollTop: height + length
     }, 300);
}

// Send Message Function
    function send(id)
    {
        var username = "{{ isset(\Auth::User()->name) ? \Auth::User()->name : ''}}";
        var toUsername = $('#user-' +id).text();
        var text = $('#bodyId_' + id).val();
        if(text!='')
        {
            $('#bodyId_' + id).attr("placeholder", "Type your answer here");
            $('#bodyId_' + id).removeClass('placeholder');
            $.ajax({
                type: 'POST',
                url: "{{ route('SendMessage')}}",
                data: {
                    'to_id' : id,
                    'body' : text,
                    'fromUsername' : username,
                    'toUsername' : toUsername,
                    "_token": "{{ csrf_token() }}"
                },
                dataType: 'json',
                success: function (data) 
                {
                    $('#bodyId_' + id).val('');
                    $('#chat-discussion_' + id).append( '<div class="chat-message right">' +
                        '<div class="message">' +
                        '<span class="message-content">' + text + '</span>' +                               
                        '<span class="message-date">'+ data.date +' '+ data.time +'</span>' +
                        '</div>' +
                        '</div>');
                     scroll_message_window(id);
                }
            });
        } else {
            
            $('#bodyId_' + id).attr("placeholder", "Please Type message ");
            $('#bodyId_' + id).addClass('placeholder');
            
        }
    }

    // Get New Message Function

    function getNewMessage()
	{
        $.ajax({
            type: 'GET',
            url: "{{ route('GetNewMessage') }}",
            data: {
                "_token": "{{ csrf_token() }}"
            },
            dataType: 'json',
            success: function (data) 
            {
			  if(data!='')
              {	
                  
                $.each(data, function(K,V) 
                {
                        
                        $('#chat-discussion_' + V.from_id).append( '<div class="chat-message left"> ' +
                            '<img class="message-avatar" src="'+ ImagePath +'/'+V.profile_img+'" alt="">' +
                            '<div class="message"> <span>' +V.from_user_info+
                            '</span><span class="message-content"> ' + V.body + '</span> ' +                               
                            '<span class="message-date">  '+ V.date +' '+ V.time +'</span> ' +
                            '</div>' +
                            '</div>');

                            toastr.success(V.from_user_info+' : '+V.body);
                });
                       
                scroll_message_window(data[0].from_id);     
			 }
            }
        });
    }

// Set the interval for New Message Function

setInterval(getNewMessage,3000);
 
</script>