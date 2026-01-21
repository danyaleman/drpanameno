<footer class="footer">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                {{date('Y')}} © {{ AppSetting('footer_left'); }}
            </div>
            <div class="col-sm-6">
                <div class="text-sm-end d-none d-sm-block">
                    {{ AppSetting('footer_right'); }}
                </div>
            </div>
        </div>
    </div>
</footer>
