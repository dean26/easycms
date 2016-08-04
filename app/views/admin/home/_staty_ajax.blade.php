<?php if(count($lista) == 0): ?>
Brak odwiedzin w tym dniu...
<?php else: ?>
<div class="table-responsive">
    <table class="table table-bordered table-striped">
    <thead>
    <tr>
        <th>Dzień</th><th>Zakładka</th><th>Przeglądarka<br/>Ip</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($lista as $l): ?>
    <tr>
        <td><b><?php echo date('d.m.Y H:i:s', strtotime($l['created_at']))?></b></td>
        <td><i><?php echo $l['ref'] ?></i></td>
        <td>
            <?php echo $l['browser'] ?><br/>
            <?php
            echo $l['ip'];
            ?>
        </td>
    </tr>
    <?php endforeach; ?>
    </tbody>
</table>
</div>
<?php endif;?>