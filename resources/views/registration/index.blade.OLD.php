@extends('layout');

@section('content')

    <div class="starter-template">
        <h1>Register</h1>
        <p class="lead">
            
            {!! Form::open(['route'=>'registration.store']) !!}

                
                <div class="form-group">
                    {!! Form::label('username', 'Username:') !!}
                    {!! Form::text('username', null, ['class' => 'form-control', 'required' => 'required'] )!!}


                </div>



                <div class="form-group">
                    {!! Form::label('email', 'e-mail address:') !!}
                    {!! Form::text('email', null, ['class' => 'form-control', 'required' => 'required'] )!!}
                </div>


                <div class="form-group">
                    {!! Form::label('password', 'Password:') !!}
                    {!! Form::text('password', null, ['class' => 'form-control', 'required' => 'required'] )!!}
                </div>


                <div class="form-group">
                    {!! Form::label('password_confirm', 'Confirm Password:') !!}
                    {!! Form::text('password_confirm', null, ['class' => 'form-control', 'required' => 'required'] )!!}
                </div>


              <div class="form-group">
                    {!! Form::submit('Create Account', ['class' => 'btn btn-primary'])  !!}
                </div>

  

            {!! Form::close() !!}


        </p>
    </div>

@stop