{{--
 * @author Archie, Disono (webmonsph@gmail.com)
 * @git https://github.com/disono/Laravel-Template
 * @copyright Webmons Development Studio. (webmons.com), 2016-2017
 * @license Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
--}}
@extends('admin.layout.master')

@section('title', $title)

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <h3>Create Authorization</h3>

                <form action="{{url('admin/authorization/store')}}" method="post" role="form">
                    {{csrf_field()}}

                    <div class="form-group">
                        <label for="name">Name*</label>
                        <input type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                               name="name" id="name" value="{{old('name')}}"
                               placeholder="Name">

                        @if ($errors->has('name'))
                            <div class="invalid-feedback">{{ $errors->first('name') }}</div>
                        @endif
                    </div>

                    <div class="form-group">
                        <label for="identifier">Identifier/Access Route*</label>
                        <select name="identifier" id="identifier"
                                class="form-control{{ $errors->has('identifier') ? ' is-invalid' : '' }}">
                            <option value="">Select Identifier</option>
                            @foreach($route_names as $key => $value)
                                <option value="{{$key}}" {{is_selected($key, old('identifier'))}}>{{$value}}</option>
                            @endforeach
                        </select>

                        @if ($errors->has('identifier'))
                            <div class="invalid-feedback">{{ $errors->first('identifier') }}</div>
                        @endif
                    </div>

                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea name="description" id="description"
                                  class="form-control{{ $errors->has('description') ? ' is-invalid' : '' }}"
                                  rows="4"
                                  placeholder="Description">{{old('description')}}</textarea>

                        @if ($errors->has('description'))
                            <div class="invalid-feedback">{{ $errors->first('description') }}</div>
                        @endif
                    </div>

                    <button type="submit" class="btn btn-primary"><i class="fa fa-floppy-o"></i> Create</button>
                </form>
            </div>
        </div>
    </div>
@endsection