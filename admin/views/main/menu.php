<nav class="menu">
  <div class="logo">
    <img src="<?php echo PADRE."/".PRINCIPAL."/assets/img/pegaso.png"?>" alt="Pegaso">
    <br>
    <h4>PINV<?php 
      echo !empty(USER["nick"]) ? " ".texto(USER["nick"]) : "";
    ?></h4>
  </div>
    <ul>
      <?php 
      foreach(USER["access"] as $data) {
        ?>

        <li>
          <a href="<?php echo PADRE."/".PRINCIPAL."/".texto($data["route"])?>"><?php echo texto($data["name"])?></a>
        </li>

        <?php
      }
      ?>
    </ul>
    <div class="burger">
      <div></div>
      <div></div>
      <div></div>
    </div>
</nav>