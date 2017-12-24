
<?php

          if(isset($add_js)):

            foreach($add_js as $js):

        ?>

        <script src="<?=$js?>"></script>

        <?php

            endforeach;

          endif;

        ?>








        <?php



           if (isset($additional)) {

                        foreach ($additional as $value) {

                            echo $value;

                        }

                    }

          ?>



        <?php



   if (isset($data)) {

                foreach ($data as $value) {

                    echo $value;

                }

            }

  ?>
