@if($message = Session::get('success'))
    <div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <h4><i class="icon fa fa-check"></i> {{__('Success')}}!</h4>
        {{ $message }}   
    </div>
@endif

@if($messages = Session::get('warnings'))
    @foreach ($messages as $message)
        <div class="alert alert-warning alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <h4><i class="icon fa fa-check"></i> {{__('Warning')}}!</h4>
            {{ $message }}   
        </div>
    @endforeach                        
@endif

@if (count($errors) > 0)
    @foreach ($errors->all() as $error)
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <h4><i class="icon fa fa-warning"></i> {{__('Error')}}!</h4>
            {{ $error }}   
        </div>
    @endforeach                        
@endif
