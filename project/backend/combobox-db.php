<?php
include "db.php";
$query = "SELECT * FROM categories";
$result = mysqli_query($conn, $query);
?>
<select name="combobox" id="combobox">
    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
        <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
    <?php } ?>
</select>

<?php

function createSlug(string $title) : string {
    // Convert to lowercase
    $title = strtolower($title);
    // Replace non-alphanumeric characters with hyphens
    $title = preg_replace('/[^a-z0-9]+/i', '-', $title);
    // Trim hyphens from the beginning and end
    $title = trim($title, '-');
    return $title;
    
}

echo createSlug("Hello World! This is a Test.");
?>


<input type="text" name="autocomplete" id="autocomplete">