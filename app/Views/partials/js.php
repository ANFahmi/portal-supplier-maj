<?php
foreach ($js as $script) {
    echo '<script src="' . base_url('public/plugins/' . $script . '.js') . '"></script>';
}
?>