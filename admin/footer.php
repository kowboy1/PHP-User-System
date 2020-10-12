<!-- Footer
================================================== -->

            </div> <!-- /.span10 -->
            </div> <!-- /.row -->
            <footer>
                <hr>
                <p>
                  <a href="http://jigowatt.co.uk" target="_TOP">&copy; Jigowatt 2009-<?php echo date('Y');?></a>
                  
                  <?php
                    
                    if (isEmpty($dbh)) return false;
                    
                    $setTranslate->languageSelector();
                  ?>
                </p>
            </footer>

        </div> <!-- /.container -->
        
        <script src="<?php echo BASE_URL . '/assets/js/jquery.jigowatt.js'; ?>"></script>


    </body>

</html>

<?php

ob_flush();
