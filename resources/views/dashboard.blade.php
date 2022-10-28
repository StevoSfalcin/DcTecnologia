@extends('main')

@section('content')
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
</head>

<div class="container">
    <div class="row">
        <div class="col-12">
          <a href="javascript:void(0)" class="btn btn-success mb-2" id="register-sale">Cadastrar Venda</a>

          <table class="table table-bordered" id="laravel_crud">
           <thead>
              <tr>
                 <th>Id</th>
                 <th>Cliente</th>
                 <th>Item(s)</th>
                 <th>Valor</th>
                 <th>Metodo de Pagamento</th>
                 <td colspan="2">Ações</td>
              </tr>
           </thead>
           <tbody id="posts-crud">
              @foreach($sales as $sale)
              <tr id="sale_id_{{ $sale->id }}">
                 <td>{{ $sale->id  }}</td>
                 <td>{{ $sale->client_cod }}</td>
                 <td>{{ $sale->item_description }}</td>
                 <td>{{ $sale->value }}</td>
                 @if($sale->payment_method == 'sight')
                    <td>À vista</td>
                 @elseif($sale->payment_method == 'installments')
                    <td>Parcelado</td>
                 @endif
                 <td><a href="javascript:void(0)" id="edit-sale" data-id="{{ $sale->id }}" class="btn btn-info">Editar Venda</a></td>
                 <td>
                  <a href="javascript:void(0)" id="delete-post" data-id="{{ $sale->id }}" class="btn btn-danger delete-post">Deletar</a></td>
              </tr>
              @endforeach
           </tbody>
          </table>

       </div>
    </div>
</div>
<div class="modal fade" id="ajax-crud-modal" aria-hidden="true">
<div class="modal-dialog">
<div class="modal-content">
    <div class="modal-header">
        <h4 class="modal-title" id="modalSale"></h4>
    </div>
    <div class="modal-body">
        <form id="saleForm" name="saleForm" class="form-horizontal">
           <input type="hidden" name="sale_id" id="sale_id">

            <div class="form-group">
                <label for="name" class="col-sm-6 control-label">Nome ou codigo do cliente:</label>
                <div class="col-sm-12">
                    <input type="text" class="form-control" id="client_cod" name="client_cod" value="">
                </div>
            </div>

            <div class="form-group">
                <label for="name" class="col-sm-6 control-label">Items vendidos</label>
                <div class="col-sm-12">
                    <input type="text" class="form-control" id="item_description" name="item_description" value="" required="">
                </div>
            </div>

            <div class="form-group">
                <label for="name" class="col-sm-6 control-label">Valor:</label>
                <div class="col-sm-12">
                    <input type="number" step="any" class="form-control" id="value" name="value" value="" required="">
                </div>
            </div>

            <div class="form-group">
                <input type="radio" id="sight" name="payment_method" value="sight" checked>
                <label for="sight">À vista</label><br>
                <input type="radio" id="installments" name="payment_method" value="installments">
                <label for="installments">Parcelado</label><br>
            </div>

            <div class="col-sm-offset-2 col-sm-10">
             <button type="submit" class="btn btn-primary" id="btn-save" value="create">Salvar
             </button>
            </div>
        </form>
    </div>
    <div class="modal-footer">

    </div>
</div>
</div>
</div>
</body>
</html>
<script>
  $(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $('#register-sale').click(function () {
        $('#btn-save').val("create-post");
        $('#saleForm').trigger("reset");
        $('#modalSale').html("Cadastrar Venda");
        $('#ajax-crud-modal').modal('show');
    });

    $('body').on('click', '#edit-sale', function () {
      var sale_id = $(this).data('id');
      $.get('sales/'+sale_id+'/edit', function (data) {
         $('#modalSale').html("Editar Venda");
          $('#btn-save').val("edit-sale");
          $('#ajax-crud-modal').modal('show');
          $('#client_cod').val(data.client_cod);
          $('#item_description').val(data.item_description);
          $('#value').val(data.value);
          $('#payment_method').val(data.payment_method);
          $('#sale_id').val(sale_id);
      })
   });
    $('body').on('click', '.delete-post', function () {
        var sale_id = $(this).data("id");
        if(confirm("Voce realmente deseja deletar esse registro?")) {
            $.ajax({
            type: "DELETE",
            url: "{{ url('sales')}}"+'/'+sale_id,
            success: function (data) {
                $("#sale_id_" + sale_id).remove();
            },
            error: function (data) {
                console.log('Error:', data);
            }
            });
        }
    });
  });

 if ($("#saleForm").length > 0) {
      $("#saleForm").validate({

     submitHandler: function(form) {
      var actionType = $('#btn-save').val();
      $('#btn-save').html('Enviando..');

      $.ajax({
          data: $('#saleForm').serialize(),
          url: "{{ route('sales.store') }}",
          type: "POST",
          dataType: 'json',
          success: function (data) {
            location.reload();
          },
          error: function (data) {
              console.log('Error:', data);
              $('#btn-save').html('Salvar');
          }
      });
    }
  })
}


</script>

@endsection('content')
