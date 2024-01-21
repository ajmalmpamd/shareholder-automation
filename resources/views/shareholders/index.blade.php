@extends('layouts.admin')

@section('content')
<!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Shareholders </h1>
          </div>
          <div class="col-sm-6">
                <a class="btn btn-primary float-sm-right" href="{{ route('shareholders.create') }}"> Add Shareholders </a>
           
            
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
     @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif
      <!-- Default box -->
      <div class="card">
        
        <div class="card-body">
          <table class="table table-bordered">
        <tr>
            <th>No</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Country</th>
            <th>Action</th>
        </tr>
        @foreach ($rows as $i => $item)
        <tr>
            
            <td>{{ $loop->iteration}}</td>
            <td>{{ $item->name }} </td>
            <td>{{ $item->email }}</td>
            <td>{{ $item->mobile }}</td>
            <td>{{ $item->country }}</td>
            <td>
              @if ($item->installment_type)
                <a href="{{ route('shareholders.payments', encrypt($item->id)) }}" class="btn btn-primary">Payments</a>
              @else
                   <a href="{{ route('shareholders.create-payments', encrypt($item->id)) }}" class="btn btn-primary">Create Payment Schedule</a>                
              @endif
            </td> 
        </tr>
        @endforeach
    </table>
  
    </div>
        <!-- /.card-body -->
        <div class="card-footer">
          {!! $rows->links('pagination::bootstrap-4') !!}
        </div>
        <!-- /.card-footer-->
      </div>
      <!-- /.card -->

    </section>
    <!-- /.content -->
@endsection
