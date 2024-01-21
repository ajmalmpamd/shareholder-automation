@extends('layouts.admin')

@section('content')
<!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Add Shareholders</h1>
          </div>
          <div class="col-sm-6">
                <a class="btn btn-secondary float-sm-right" href="{{ route('shareholders.index') }}"> Shareholders</a>
           
            
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
     @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
      <!-- Default box -->
      <div class="row">
         <div class="col-md-8 mx-auto">
            <div class="card">
        
              <div class="card-body">
                <form id="shareAmountForm">
            @csrf
            <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="duration">Duration (in years):</label>
                    <select name="duration" id="duration" class="form-control" required>
                    <option value="1">1 Year</option>
                    <option value="2">2 Year</option>
                    <option value="3">3 Year</option>
                    <option value="4">4 Year</option>
                </select>
                </div>
            </div>
            <div class="col-md-6">
            <div class="form-group">
                <label for="annual_amount">Annual Amount:</label>
                <input type="text" name="annual_amount" id="annual_amount" class="form-control" required>
            </div>
            </div>
            <div class="col-md-6">
            <div class="form-group">
                <label for="installment_type">Installment Type:</label>
                <select name="installment_type" id="installment_type" class="form-control" required>
                    <option value="Monthly">Monthly</option>
                    <option value="Quarterly">Quarterly</option>
                    <option value="Annual">Annual</option>
                    <option value="Custom">Custom</option>
                </select>
            </div>
            </div>
            <div class="col-md-6">
            <div class="form-group">
                <label for="start_date">Start Date:</label>
                <input type="date" name="start_date" id="start_date" class="form-control" required>
            </div>
            </div>
            
            <button type="button" class="btn btn-primary" id="generatePayments">Generate Payments</button>
            </div>

                <div id="paymentTableContainer" class="mt-3 " >
                    <!-- Payment details table will be dynamically generated here -->
                </div>
                <div id="customInstallmentContainer"></div>

          </form>
              </div>
              <!-- /.card-body -->
              <div class="card-footer">
                
              </div>
              <!-- /.card-footer-->
            </div>
      <!-- /.card -->
          </div>
      </div>
    </section>
    <!-- /.content -->
@endsection
@section('script')
    <script>
        $(document).ready(function () {
            $('#generatePayments').on('click', function () {
                if (validateForm()) {
                        generatePaymentTable();
                } else {
                    alert('Please fill in all required fields before generating payments.');
                }
                
            });
        });

        function validateForm() {
            var isValid = true;
            $('#shareAmountForm [required]').each(function () {
                if ($(this).val() === '') {
                    isValid = false;
                    return false; // exit loop if any required field is empty
                }
            });
            return isValid;
        }

        function generatePaymentTable() {
            // Get share amount details from the form
            var duration = $('#duration').val();
            var annualAmount = $('#annual_amount').val();
            var installmentType = $('#installment_type').val();
            var startDate = $('#start_date').val();

            // Perform calculation to determine installment amount
            var installmentAmount = annualAmount / (getInstallmentMultiplier(installmentType));
            if (installmentType === 'Custom') {
                generateCustomFields();
            } else { 


            // Generate the dynamic table
            var tableHtml = '<table class="table">';
            tableHtml += '<thead><tr><th>Due Date</th><th>Installation Amount</th></tr></thead>';
            tableHtml += '<tbody>';

            for (var i = 1; i <= duration * getInstallmentMultiplier(installmentType); i++) {
                var dueDate = new Date(startDate);
                dueDate.setMonth(dueDate.getMonth() + i - 1);
                tableHtml += '<tr>';
                tableHtml += '<td><input type="date" name="due_date[]" class="form-control" value="' + dueDate.toISOString().split('T')[0] + '" readonly></td>';
                tableHtml += '<td><input type="text" name="amount_to_pay[]" class="form-control" value="' + installmentAmount.toFixed(2) + '" readonly></td>';
                tableHtml += '</tr>';
                
            }

            tableHtml += '</tbody>';
            tableHtml += '</table>';
            tableHtml += '<button type="button" class="btn btn-primary mt-3" id="createPayments">Create</button>';

            // Display the dynamic table
            $('#paymentTableContainer').html(tableHtml);

            }
            
        }

        function getInstallmentMultiplier(installmentType) {
            switch (installmentType) {
                case 'Monthly':
                    return 12;
                case 'Quarterly':
                    return 4;
                case 'Annual':
                    return 1;
                case 'Custom':                    
                    return 1;
                default:
                    return 1;
            }
        }

        function generateCustomFields(){
            var tableHtml = ''; 
            tableHtml += '<table class="table" >';
            tableHtml += '<thead><tr><th>Due Date</th><th>Installation Amount</th><th><button type="button" class="btn btn-primary mt-2 float-sm-right" id="addCustomInstallment">Add Installment</button></th></tr></thead>';
            tableHtml += '<tbody id="cstmfields"><tr>' +
                '<td>' +
                '<input type="date" name="due_date[]" class="form-control" required>' +
                '</td>' +
                '<td>' +
                '<input type="text" name="amount_to_pay[]" class="form-control" required>' +
                '</td><td>' +
                '<button type="button" class="btn btn-danger remove-custom-installment">Remove</button>' +
                '</td></tr></tbody>';
            tableHtml += '</table>';
            tableHtml += '<button type="button" class="btn btn-primary mt-3" id="createPayments">Create</button>';
            $('#paymentTableContainer').html(tableHtml);
             $(document).on('click', '#addCustomInstallment', function () {
            
                addCustomInstallmentFields();
            });
            

        }
        function addCustomInstallmentFields() {
            console.log('addCustomInstallmentFields');
            var customInstallmentContainer = $('#cstmfields');
            var nextIndex = customInstallmentContainer.find('.custom-installment-group').length + 1;

            var customInstallmentGroup = $(
                '<tr>' +
                '<td>' +
                '<input type="date" name="due_date[]" class="form-control" required>' +
                '</td>' +
                '<td>' +
                '<input type="text" name="amount_to_pay[]" class="form-control" required>' +
                '</td><td>' +
                '<button type="button" class="btn btn-danger remove-custom-installment">Remove</button>' +
                '</td></tr>'
            );

            customInstallmentContainer.append(customInstallmentGroup);
        }

        // Event listener for removing custom installment fields
        $(document).on('click', '.remove-custom-installment', function () {
            $(this).closest('tr').remove();
        });
        

        $(document).on('click', '#createPayments', function (e) {
            e.preventDefault();

            var formData = new FormData(document.getElementById("shareAmountForm"));

            /*
            $('.table tbody tr').each(function (index, element) {
                formData.append('date[]', $(element).find('input[name^="due_date"]').val());
                formData.append('amount[]', $(element).find('input[name^="amount_to_pay"]').val());
            }); */

            $.ajax({
                url: '{{ route("shareholders.store-payments", encrypt($shareholder->id)) }}', 
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function (response) {
                    if (response.redirect_url) {
                        window.location.href = response.redirect_url;
                    }
                    
                },
                error: function (xhr, status, error) {
                    console.error(xhr.responseText);
                    alert('Error adding payment details. Please try again.');
                }
            });
        });
    </script>
    @endsection