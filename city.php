<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Városok</title>
</head>
<body>
<h1>Városok</h1>
    <form>
        <label for="countyDropdown">Megye:</label>
        <select id="countyDropdown" name="countyDropdown">
            <option value="">Válassz megyét</option>

            <?php
            require_once('CountiesDbTools.php');

            $itemRepository = new CountiesDbTools();
            $counties = $itemRepository->getAllCounties();

            foreach ($counties as $county) {
                echo '<option value="' . $county['id'] . '">' . $county['name'] . '</option>';
            }
            ?>

        </select>
    </form>
    
</body>
</html>