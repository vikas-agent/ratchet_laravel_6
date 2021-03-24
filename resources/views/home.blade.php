@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Dashboard</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        You are logged in!
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('scripts')

    <script>
        var conn = new WebSocket('ws://localhost:8091/', ['wamp']);
        console.log(conn);
        console.log(conn.protocol);
        conn.onopen = function(e) {
            console.log("Connection established!");
            conn.send(JSON.stringify([5, "kittensCategory"]));
        };
        conn.onmessage = function(e) {
            console.log(e.data);
        };
        conn.onerror = function(error) {
            console.log(error);
        }

    </script>
    {{-- <script>
        var conn = new ab.Session('ws://localhost:8091',
            function() {
                conn.subscribe('kittensCategory', function(topic, data) {
                    // This is where you would add the new article to the DOM (beyond the scope of this tutorial)
                    console.log('New article published to category "' + topic + '" : ' + data.title);
                });
            },
            function() {
                console.warn('WebSocket connection closed');
            }, {
                'skipSubprotocolCheck': true
            }
        );

    </script> --}}
@endsection
