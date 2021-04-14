
<!DOCTYPE html>
<html>
<head>
 <link rel="stylesheet" type="text/css" href="<?= base_url() ?>/assets/styles/popup.css">   
 <link rel="stylesheet" type="text/css" href="<?= base_url() ?>/assets/styles/cart_items.css">   

<style type="text/css">
.popup {
  margin: 70px auto;
  padding: 20px;
  background: rgba(0, 0, 0, 0.0);
  border-radius: 5px;
  width: 40%;
  position: relative;
  transition: all 5s ease-in-out;
}

@media(max-width:1000px) {
    .popup {
       width: 70%;
    }
}

@media(max-width:750px) {
    .popup {
       width: auto;
    }
}


.popup .close {
  cursor: pointer;
  position: absolute;
  top: -10px;
  right: 5px;
  transition: all 200ms;
  font-size: 30px;
  font-weight: bold;
  text-decoration: none;
  color: #333;
}

.popup .content {
  max-height: 30%;
  overflow: auto;
}


</style>
</head>
<body>
    <div id="popup1" class="overlay">
      <div class="popup">
        <div class="container-fluid">
          <h2>Selected Items</h2>
          <div class="card">
            <a class="close">×</a>
            <div class="cart-items">
                  
            </div>

            </div>
              <div class="d-flex  bg-dark text-white p-2">
                 <table class="table table-dark total-detail">
                    <thead>
                      <tr class="total-amount">
                        <th class="border-0 text-uppercase small font-weight-bold">Total Amount</th> <td class="text-center" id="total"> </td>
                      </tr>
                      <tr class="discount">
                          <th class="border-0 text-uppercase small font-weight-bold">Discount</th> <td class="text-center" id="discount"></td>
                      </tr>
                      <tr class="grand-total">
                        <th class="border-0 text-uppercase small font-weight-bold">Grand Total</th> <td class="text-center" id="grand-total"></td>
                      </tr>
                      <tr class="text-center">
                          <th colspan="2" class="pt-4 border-0 text-uppercase small font-weight-bold"><a href="<?= base_url('order_book') ?>" class="btn btn-success order_book">Book Order</a></th>
                      </tr>
                    </thead>
                  </table>
                </div>
          </div>                   
    </div>
  </div>
</div>

</body>
<script type="text/javascript">
  $(document).ready(function()
    {
      $('.cart-items').on('click','.increment', function() {
        var id = $(this).data("id");
        
        $('.'+id+' input').val(parseInt($('.'+id+' input').val(), 10) + 1)
        var quantity = $('.'+id+' input').val();
        edit(id, quantity);
  });
  $('.cart-items').on('click','.decrement',function() {
    var id = $(this).data("id");
    var quantity = $('.'+id+' input').val();
    if (quantity > 1) {
      $('.'+id+' input').val(parseInt($('.'+id+' input').val(), 10) - 1)
    quantity = $('.'+id+' input').val();

      edit(id, quantity);
  }
    
  });
        let products = [];
        PopUp();

      $(".close").click(function(){
        popUpClose();
       })

  $( ".cart-items" ).on('change','input',function(){
    var id = $(this).data("id");
    var quantity = this.value;
    edit(id, quantity);
      });

  $( ".cart-items" ).on('click','.remove',function(){

    var item_id = this.value;
    removeItem(item_id);
      });

// ........................................................ Functions
  // Show data in table
  

  async function DataShow()
  {
    cart_items()
    for (i = 0; i < localStorage.length; i++)
         {
          x = localStorage.key(i);
          if(x[0] == 'm' && x[1] == 'h' && x[2] == 'g')
          {
            item = await JSON.parse(localStorage.getItem([x]))
            var total = item['price']*item['quantity'];
            total = total.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");

            var html =  '<div class="row">'
                          
                          +'<div class="col-6">'
                            +'<img class="pull-right" src="<?= base_url() ?>/uploads/items/'+item['image']+'">'
                          +'</div>'

                          +'<div class="col-4">'
                            +'<div class="input-group '+item['_id']+'">'
                              +'<div class="input">'
                                +'<input data-id="'+item['_id']+'" type="number" value="'+item['quantity']+'" class="form-control text-center"/>'
                              +'</div>'
                              +'<div class="input-group-btn-vertical">'
                                +'<button class="btn btn-default increment" type="button" data-id="'+item['_id']+'"><i class="fa fa-caret-up"></i></button>'
                                +'<button class="btn btn-default decrement" type="button" data-id="'+item['_id']+'"><i class="fa fa-caret-down"></i></button>'
                              +'</div>'

                            +'</div>'
                          +'</div>'

                        +'</div>'

                        +'<div class="row">'
                          +'<div class="col-12 text-center about">'
                            +'<h5 id="title">'+item['title']+'</h5>'
                            +'<h6 id="price">'+total+'</h6>'
                          +'</div>'
                        +'</div>'

                        +'<div class="row">'
                          +'<div class="col-12 text-center" >'
                            +'<button class="btn btn-danger remove" value="'+item['_id']+'">remove</button>'
                          +'</div>'
                        +'</div>'
                            
            $(".cart_box").append(html)
              
            if (i < items+1) { $(".cart_box").append('<hr>')}
           
          }
        }
  }

  async function PopUp()
  {
          $('#popup1').css("visibility", "visible"); 
          $('#popup1').css("opacity", 1);
          $('body').css("overflow-y" , "hidden");
          $('header').css("position", 'static');
          $('.pager').css("visibility", "hidden");
          $('.list').css("position", "sticky");

          
  

         await DataShow();
          total();
  }
  
  async function update()
  {
    $(".cart-items").html("");
    await DataShow();
    total();
  }

  async function popUpClose()
  {
    $('header').css("position", 'sticky');
    $('body').css("overflow-y" , "auto");
    $('.pager').css("visibility", "visible");
    $('.list').css("position", "relative");
    $('#popup1').remove();   
      cart_items()
      document.getElementById("cart_basket").innerHTML = '<i class="fa fa-shopping-cart text-dark" aria-hidden="true"><small>'+items+'</small></i>'; 
  }

  async function removeItem(item_id)
  {
    window.localStorage.removeItem(['mhg',item_id]);
    update();
  }

  async function edit(id , quantity)
  {
    var item = await JSON.parse(localStorage.getItem(['mhg',id]))
    item['quantity'] = quantity;
    window.localStorage.removeItem(['mhg',id]);
    await localStorage.setItem(['mhg',id], JSON.stringify(item));
    update();
  }

  async function total()
  {
    var sub_total = 0;
    var grand_total = 0;
    var discount = 0;
    for (i = 0; i < localStorage.length; i++)
         {
          x = localStorage.key(i);
         if(x[0] == 'm' && x[1] == 'h' && x[2] == 'g')
          {
            item = await JSON.parse(localStorage.getItem([x]))
            sub_total = (item['price']*item['quantity'])+sub_total;
            grand_total = (item['total']*item['quantity'])+grand_total;
          }
        }
        discount = ((grand_total/sub_total)*100)
        discount = 100-discount

        sub_total = sub_total.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        grand_total = grand_total.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","); 
        $("#table_total_data").html('<tr> <td>'+sub_total+'</td> <td>'+discount.toFixed(2)+'%</td> <td>'+grand_total+'</td></tr>')
        $('.total-detail .total-amount #total').html(''+sub_total+'')
        $('.total-detail .discount #discount').html(''+discount.toFixed(2)+' %')
        $('.total-detail .grand-total #grand-total').html(''+grand_total+'')

  }
    });
</script>
</html>