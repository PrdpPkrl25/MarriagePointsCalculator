@extends('layout')

@section('content')
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card " style="margin-top: 80px;padding: 20px">
                    <div class="card-header">{{ __('Enter Players Detail:') }}</div>
                    <div class="card-body" style="background-color: rgba(0,0,0,.75);">
                        <form method="POST" action="{{route('players.store')}}">
                            @csrf
                            @for($i=1;$i<=$game->number_of_players;$i++)
                                <div class="form-group row mt-2 text-center">

                                    <label for="name"
                                           class="col-md-2 col-form-label text-md-right" style="color: white">Player {{$i}} Name:</label>

                                    <div class="col-md-3">

                                        <input id="name" type="text"
                                               class="form-control"
                                               name="name[]" required autocomplete="name"
                                               autofocus>

                                    </div>

                                    <label for="email"
                                           class="col-md-3 col-form-label text-md-right" style="color: white">Player {{$i}} Email(optional):</label>

                                    <div class="col-md-3">

                                        <input id="email" type="text"
                                               class="form-control"
                                               name="email[]" autocomplete="email"
                                               autofocus>

                                    </div>
                                </div>
                            @endfor

                            <div class="form-group row mb-0">
                                <div class="col-md-3 offset-md-5  text-center">
                                    <button type="submit" class="btn btn-block btn-primary">
                                        {{ __('Next') }}
                                    </button>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

