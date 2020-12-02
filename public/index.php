<div class="jumbotron">
   <h3>Generate your invoice</h3>
    <form name="frmInvoice" method="post" action="index.php">
        <label class="lead">
            Invoice number:
            <input type="text" name="invoiceNo"/>
        </label>
        <br>
        <button name="button">Send message!</button>
    </form>
</div>

<?php
require_once "../vendor/autoload.php";

use AYakovlev\core\WorkerSender;

$inputFilters = array(
    'invoiceNo' => FILTER_SANITIZE_NUMBER_INT,
);
$input = filter_input_array(INPUT_POST, $inputFilters);
$sender = new WorkerSender();
try {
    $sender->execute($input['invoiceNo']);
} catch (Exception $e) {
    echo $e->getMessage();
}
?>