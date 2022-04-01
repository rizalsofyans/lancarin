    <?php if($show){?>
            <section class="subscribe-area ptb-100">
            
            <div class="bg-top"></div>
            <div class="bg-bottom"></div>
           
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 col-md-12">
                        <div class="newsletter">
                            <h4>Daftar Lancarin Sekarang Juga</h4>  
                                <a class="btn btn-primary btn-signup" href="<?=BASE?>#pricing">Daftar Sekarang</a>
                        </div>
                    </div>
                </div>
            </div>
            
        </section>
        <!-- End Subscribe Area-->
        
        <!-- Start Footer Area -->
        <footer class="footer-area bg-gray">
            
            <div class="copyright-area" style="background:#fff !important;">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-7 col-md-7">
                            <p>Copyright <i class="icofont-copyright"></i> 2018 All Rights Reserved.</p>
                        </div>
                        
                        <div class="col-lg-5 col-md-5">
                            <ul>
                                <li><a href="https://instagram.com/lancarin.id" class="icofont-instagram"></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
        <!-- End Footer Area -->
    <?php }?>


	<!--Javascript-->
	<script type="text/javascript" src="<?=BASE?>themes/aruba/assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript" src="<?=BASE?>themes/aruba/assets/plugins/ladda/spin.min.js"></script>
    <script type="text/javascript" src="<?=BASE?>themes/aruba/assets/plugins/ladda/ladda.min.js"></script>
	<script type="text/javascript" src="<?=BASE?>themes/aruba/assets/js/jquery.aniview.js"></script>
	<script type="text/javascript" src="<?=BASE?>themes/aruba/assets/js/particles.min.js"></script>
	<script type="text/javascript" src="<?=BASE?>themes/aruba/assets/js/main.js"></script>
    <?=htmlspecialchars_decode(get_option('embed_javascript', ''), ENT_QUOTES)?>
</body>
</body>
</html>