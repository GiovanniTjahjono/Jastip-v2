<div id="copyright text-right">© Copyright 2013 Scotchy Scotch Scotch</div>
<script>
    $(document).ready(function() {
        $('#table_product').DataTable();
    });
    $(document).ready(function() {
        $('#table_order').DataTable();
    });
    $('.datepicker').datepicker({
        autoclose: true,
        format: "dd-mm-yyyy",
        todayHighlight: true,
        orientation: "top auto",
        todayBtn: true,
        endDate: new Date()
    });
</script>