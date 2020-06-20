<!--------------SIDE BAR PEMBELIAN------------>
<div class="card my-4 shadow-lg p-3 mb-5 bg-white rounded border-0">
  <div class="card-header">
    Pembelian
  </div>
  <a href="/order/daftar_pembelian_preorder/{{Auth::user()->id}}" class="list-group-item border-0">Produk</a>
  <a href="/bulkbuy/daftar_pembelian_preorder/{{Auth::user()->id}}" class="list-group-item border-0">Produk Bulk Buy</a>
  <a href="/pembelian-request/daftar_pembelian_request/{{Auth::user()->id}}" class="list-group-item border-0">Request</a>
</div>