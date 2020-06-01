@extends('layouts.pesanview')
@section('content')
<!-- =========================================================== -->
<div class="container mt-4">
    @if ($cek === 'user')
    <div class="card  shadow-lg p-3 mb-5 bg-white rounded border-0">
        <div class="card-header bg-white">
            <h3>Chatting</h3>
        </div>
        <div class="card-body">
            <div class="box-body">
                <!-- Conversations are loaded here -->
                <div class="direct-chat-messages">
                    <!-- Message. Default to the left -->
                    <!-- Message. Default to the left -->
                    <div class="direct-chat-msg">
                        <div class="direct-chat-info clearfix">
                            <span class="direct-chat-name pull-left"></span>
                            <span class="direct-chat-timestamp pull-right"></span>
                        </div>
                        <!-- /.direct-chat-info -->


                        <!-- /.direct-chat-text -->
                    </div>
                    <!-- /.direct-chat-msg -->
                    <!-- Message to the right -->
                    <div class="direct-chat-msg right">
                        <div class="direct-chat-info clearfix">
                            <span class="direct-chat-name pull-right"></span>
                            <span class="direct-chat-timestamp pull-left"></span>
                        </div>
                        <!-- /.direct-chat-info -->

                        <!-- /.direct-chat-text -->
                    </div>
                    <!-- /.direct-chat-msg -->
                </div>
                <!--/.direct-chat-messages-->


                <!-- /.direct-chat-pane -->
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                <form action="" enctype="multipart/form-data" method="post">

                    <div class="input-group">
                        <input type="text" name="isi_pesan" placeholder="Type Message ..." class="form-control" required>
                        <input type="hidden" name="id_penerima" value="" />
                        <span class="input-group-btn">
                            <button disabled type="submit" class="btn btn-primary btn-flat">Send</button>
                        </span>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>
@stop