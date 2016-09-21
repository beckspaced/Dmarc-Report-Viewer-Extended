<?php echo $this->includeTemplate('header.tpl.php')?>
<h1><?php echo $this->heading; ?></h1>

<?php if( $this->status != ""): ?>
<div class="status"><?php echo $this->status; ?></div>
<?php endif;?>

<form id="form" action="index.php" method="POST" class="form">

<div class="menu-top-container">
    <p>Total Reports: <?php echo $this->records_total; ?> | Currently Showing: <?php echo $this->records_from; ?> - <?php echo $this->records_to; ?></p>
    <p>Select Page:&nbsp;&nbsp;&nbsp; <?php echo $this->pager; ?></p>
    <p>
    <label for="perpage">Items per page</label>
    <select name="perpage" id="perpage" onchange="this.form.submit()">
        <option value="5"<?php if ($this->perpage == "5") echo " selected" ?>>5</option>
        <option value="10"<?php if ($this->perpage == "10") echo " selected" ?>>10</option>
        <option value="20"<?php if ($this->perpage == "20") echo " selected" ?>>20</option>
        <option value="30"<?php if ($this->perpage == "30") echo " selected" ?>>30</option>
        <option value="40"<?php if ($this->perpage == "40") echo " selected" ?>>40</option>
        <option value="50"<?php if ($this->perpage == "50") echo " selected" ?>>50</option>
        <option value="75"<?php if ($this->perpage == "75") echo " selected" ?>>75</option>
        <option value="100"<?php if ($this->perpage == "100") echo " selected" ?>>100</option>
    </select>
    <input type="button" id="report-checkall" class="check" value="Check" />
    <button type="submit" name="action" value="delete-reports">Delete selected reports</button>
    </p>

</div>


<table class='reportlist'>
    <thead>
        <tr>
            <th>-</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Domain</th>
            <th>Reporting Organization</th>
            <th>Report ID</th>
            <th>Messages</th>
        </tr>
    </thead>
    
    <tbody>
    
    <?php foreach ($this->reports as $r): //var_dump($r) ?>

        <tr>
            <td class='center'><input type="checkbox" name="report[]" value="<?php echo $r['serial']; ?>" class="report-checkbox" /></td>
            <td class='center'><?php echo date("D, d M Y - H:i", strtotime($r['mindate'])) ?></td>
            <td class='center'><?php echo date("D, d M Y - H:i", strtotime($r['maxdate'])) ?></td>
            <td class='center'><?php echo $r['domain'] ?></td>
            <td class='center'><?php echo $r['org'] ?></td>
            <td class='center'><a href='?report=<?php echo $r['serial']; ?>' class="ajax"><?php echo $r['reportid'] ?></a></td>
            <td class='center'><?php echo $r['rcount']; ?></td>
        </tr>
        <tr>
            <td colspan="6"><div id="report-serial-<?php echo $r['serial']; ?>" class="report-item report-placeholder"></div></td>
        </tr>    
    
    <?php endforeach; ?>
    
    </tbody>

</table>

</form>

<div class="footer">
    <p><?php echo $this->footer_credit; ?></p>
</div>

<?php echo $this->includeTemplate('footer.tpl.php')?>
