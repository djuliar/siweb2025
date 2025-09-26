<!DOCTYPE html>
<html lang="en">
    <?php include "db.php"; ?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Text Autocomplete</title>
    <style rel="stylesheet" href="styles.css"></style> 
</head>
<body>
    <h1>Text Autocomplete</h1>
    <input type="text" id="search" placeholder="Ketik nama produk..." autocomplete="off">
    <div id="result"></div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#search').on('input', function() {
                var query = $(this).val();
                if (query.length > 0) {
                    $.ajax({
                        url: 'search.php',
                        method: 'POST',
                        data: {query: query},
                        success: function(data) {
                            $('#result').html(data);
                        }
                    });
                } else {
                    $('#result').empty();
                }
            });

            $(document).on('click', '.item', function() {
                $('#search').val($(this).text());
                $('#result').empty();
            });
        });
    </script>
</body>
</html>