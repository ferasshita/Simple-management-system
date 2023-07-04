<?php
if (is_dir("imgs/")) {
    $dircheckPath = "";
}elseif (is_dir("../imgs/")) {
    $dircheckPath = "../";
}elseif (is_dir("../../imgs/")) {
    $dircheckPath = "../../";
}
?>
<footer class="main-footer">
    <div class="pull-right d-none d-sm-inline-block">
        <ul class="nav nav-primary nav-dotted nav-dot-separated justify-content-center justify-content-md-end">
            <li class="nav-item">
                <a class="nav-link" accesskey="h" href="javascript:alert('اتصل بنا: 0913691247')"><?php echo lang('help'); ?> </a>
            </li>
        </ul>
    </div>
    &copy; <?php echo date('Y'); ?> <?php echo lang('All_Rights_Reserved'); ?> <a href="#">Almaqar</a>.
</footer>

<!-- Control Sidebar -->
<aside class="control-sidebar">



</aside>
<!-- /.control-sidebar -->
<!-- Add the sidebar's background. This div must be placed immediately after the control sidebar -->
<div class="control-sidebar-bg"></div>
