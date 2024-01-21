@extends('layouts.admin')

@section('content')
<!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>All Payments </h1>
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
            <form action="{{ route('all-payments') }}" method="GET">
            <div class="filter row m-2">
                
                
                    <div class="col-md-3">
                        <label for="country">Filter by Country:</label>
                        <select name="country" id="country" class="form-control" >
                            <option value="">All</option>
                            @foreach ($countries as $country)
                                <option value="{{ $country }}" {{ request('country') == $country ? 'selected' : '' }}>{{ $country }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">                
                        <label for="status">Filter by Status:</label>
                        <select name="status" id="status" class="form-control" >
                            <option value="">All</option>
                            <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                            <option value="Paid" {{ request('status') == 'Paid' ? 'selected' : '' }}>Paid</option>
                        </select>
                </div>

                    <div class="col-md-3 d-flex flex-column align-items-end align-self-end">
                    <button type="submit" class="btn btn-secondary">Apply Filters</button>
                </div>
            </div>
            </form>
          <table class="table table-bordered">
        <tr>
            <th>No</th>            
            <th>Due Date</th>
            <th>Installment Amount</th>
            <th>Payment Date</th>
            <th>Paid Amount</th>
            <th>Status</th>
             <th>Action</th>
            
        </tr>
        @foreach ($payments as $payment)
            <tr>
                <td>{{ $loop->iteration}}</td>
                <td >{{ $payment->due_date }}</td>
                <td >{{ $payment->installment_amount }}</td>
                <td class="payment-date" >{{ $payment->payment_date }}</td>
                <td class="paid_amount">{{ $payment->paid_amount }}</td>
                <td class="payment-status">{{ $payment->status }}</td>
                <td>
                @if ($payment->status == 'Pending')
                    <button class="make-payment-btn btn btn-success btn-sm" data-payment-url="{{ route('shareholders.make-payments', encrypt($payment->id)) }}">Make Payment</button>
                @else
                    
                @endif
            </td>
            </tr>
        @endforeach
        
    </table>
  
    </div>
        <!-- /.card-body -->
        <div class="card-footer">
          
        </div>
        <!-- /.card-footer-->
      </div>
      <!-- /.card -->

    </section>
    <!-- /.content -->
@endsection
@section('script')
    <script>
        $(document).ready(function () {

            $('.make-payment-btn').on('click', function () {
                var btn=$(this);
                var tr= $(this).closest('tr');
                var paymentURL = $(this).data('payment-url');

                $.ajax({
                    url: paymentURL,
                    type: 'POST',
                    data: {_token: '{{ csrf_token() }}'},
                    success: function (response) {
                        var jsonData=response.data;
                        tr.find('.payment-date').text(jsonData.payment_date);
                        tr.find('.paid_amount').text(jsonData.paid_amount);
                        tr.find('.payment-status').text(jsonData.status);
                        btn.remove();
                    },
                    error: function (xhr, status, error) {
                        console.error(xhr.responseText);
                        alert('Error making payment. Please try again.');
                    }
                });
            });
        });
    </script>
@endsection